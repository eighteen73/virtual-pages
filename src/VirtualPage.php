<?php

namespace Eighteen73\VirtualPages;

use Eighteen73\VirtualPages\Contracts\VirtualPageContract;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
class VirtualPage implements VirtualPageContract {

	private string $url;
	private string $title;
	private string $content;
	private string $template;
	private ?\WP_Post $wp_post = null;

	function __construct($url, $title = 'Untitled', $template = 'page.php') {
		$this->url = filter_var($url, FILTER_SANITIZE_URL);
		$this->setTitle($title);
		$this->setTemplate($template);
	}

	function getUrl(): string
	{
		return $this->url;
	}

	function getTemplate(): string
	{
		return $this->template;
	}

	function getTitle(): string
	{
		return $this->title;
	}

	function setTitle(string $title): static
	{
		$this->title = filter_var( $title, FILTER_SANITIZE_STRING );
		return $this;
	}

	function setContent(string $content): static
	{
		$this->content = $content;
		return $this;
	}

	function setTemplate(string $template): static
	{
		$this->template = $template;
		return $this;
	}

	function asWpPost(): ?\WP_Post
	{
		if ( is_null( $this->wp_post ) ) {
			$post = [
				'ID'             => 0,
				'post_title'     => $this->title,
				'post_name'      => sanitize_title( $this->title ),
				'post_content'   => $this->content ? : '',
				'post_excerpt'   => '',
				'post_parent'    => 0,
				'menu_order'     => 0,
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
				'comment_count'  => 0,
				'post_password'  => '',
				'to_ping'        => '',
				'pinged'         => '',
				'guid'           => home_url( $this->getUrl() ),
				'post_date'      => current_time( 'mysql' ),
				'post_date_gmt'  => current_time( 'mysql', 1 ),
				'post_author'    => is_user_logged_in() ? get_current_user_id() : 0,
				'is_virtual'     => TRUE,
				'filter'         => 'raw'
			];
			$this->wp_post = new \WP_Post( (object) $post );
		}

		return $this->wp_post;
	}
}
