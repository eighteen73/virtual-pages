<?php
namespace Eighteen73\VirtualPages\Contracts;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
interface TemplateLoaderContract
{

	/**
	 * Setup loader for a page objects
	 */
	public function init(VirtualPageContract $page): void;

	public function addSuggestions(VirtualPageContract $page): void;
}
