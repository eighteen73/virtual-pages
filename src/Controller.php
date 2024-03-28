<?php

namespace Eighteen73\VirtualPages;

use Eighteen73\VirtualPages\Contracts\VirtualPageContract;
use Eighteen73\VirtualPages\Contracts\ControllerContract;
use Eighteen73\VirtualPages\Contracts\TemplateLoaderContract;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
class Controller implements ControllerContract
{

	private \SplObjectStorage $pages;
	private TemplateLoaderContract $loader;
	private ?VirtualPageContract $matched = null;

	public function __construct(TemplateLoaderContract $loader)
	{
		$this->pages = new \SplObjectStorage;
		$this->loader = $loader;
	}

	public function init(): void
	{
		do_action('virtual_pages', $this);
	}

	public function addPage(VirtualPageContract $page): VirtualPageContract
	{
		$this->pages->attach($page);
		return $page;
	}

	public function dispatch(): void
	{
		global $wp;

		if ($this->checkRequest() && $this->matched instanceof VirtualPageContract) {
			$this->loader->init($this->matched);
			$wp->virtual_page = $this->matched;
			$this->setupQuery();
			$this->setupTemplates();
		}
	}

	public function disableRedirects($redirect_url, $requested_url): string
	{
		// It's our request, leave it alone
		if ($this->checkRequest()) {
			return "";
		}

		// Else, continue with canonical_redirect
		return $redirect_url;
	}

	private function checkRequest(): bool
	{
		$this->pages->rewind();
		$path = rtrim(strtok($this->getPathInfo(), "?"), "/");;

		while ($this->pages->valid()) {
			if (rtrim($this->pages->current()->getUrl(), '/') === $path) {
				$this->matched = $this->pages->current();
				return TRUE;
			}
			$this->pages->next();
		}

		return false;
	}

	private function getPathInfo(): string
	{
		$home_path = parse_url(home_url(), PHP_URL_PATH);
		return preg_replace("#^/?{$home_path}/#", '/', add_query_arg([]));
	}

	private function setupQuery(): void
	{
		global $wp_query;
		$wp_query->init();
		$wp_query->is_page = TRUE;
		$wp_query->is_singular = TRUE;
		$wp_query->is_home = FALSE;
		$wp_query->found_posts = 1;
		$wp_query->post_count = 1;
		$wp_query->max_num_pages = 1;
		$posts = (array)apply_filters(
			'the_posts', [$this->matched->asWpPost()], $wp_query
		);

		$post = $posts[0];
		$wp_query->posts = $posts;
		$wp_query->post = $post;
		$wp_query->queried_object = $post;
		$GLOBALS['post'] = $post;
		$wp_query->virtual_page = $post instanceof \WP_Post && isset($post->is_virtual)
			? $this->matched
			: NULL;
	}

	private function setupTemplates(): void
	{
		$this->loader->addSuggestions($this->matched);
	}
}
