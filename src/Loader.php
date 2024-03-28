<?php

namespace Eighteen73\VirtualPages;

use Eighteen73\VirtualPages\Contracts\VirtualPageContract;

class Loader
{
	protected static bool $booted = false;

	public static function init(): void
	{
		if (self::$booted) {
			return;
		}

		$controller = new Controller(new TemplateLoader());

		add_action('init', [$controller, 'init']);

		add_filter('parse_query', [$controller, 'dispatch'], PHP_INT_MAX, 2);

		add_filter('redirect_canonical', [$controller, 'disableRedirects'], PHP_INT_MAX, 2);

		add_action('loop_end', function (\WP_Query $query) {
			if (isset($query->virtual_page) && !empty($query->virtual_page)) {
				$query->virtual_page = NULL;
			}
		});

		add_filter('the_permalink', function ($plink) {
			global $post, $wp_query;
			if (
				$wp_query->is_page
				&& isset($wp_query->virtual_page)
				&& $wp_query->virtual_page instanceof VirtualPageContract
				&& isset($post->is_virtual)
				&& $post->is_virtual
			) {
				$plink = home_url($wp_query->virtual_page->getUrl());
			}
			return $plink;
		});

		self::$booted = true;
	}
}
