<?php
namespace Eighteen73\VirtualPages\Contracts;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
interface VirtualPageContract {

	function getUrl(): string;

	function getTemplate(): string;

	function getTitle(): string;

	function setTitle( string $title ): static;

	function setContent( string $content ): static;

	function setTemplate( string $template ): static;

	/**
	 * Get a WP_Post build using viryual Page object
	 *
	 * @return \WP_Post
	 */
	function asWpPost(): ?\WP_Post;
}
