<?php

namespace Eighteen73\VirtualPages;

use Eighteen73\VirtualPages\Contracts\VirtualPageContract;
use Eighteen73\VirtualPages\Contracts\TemplateLoaderContract;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
class TemplateLoader implements TemplateLoaderContract
{

	public function init(VirtualPageContract $page): void
	{
		$this->templates = wp_parse_args(
			['page.php', 'index.php'], (array)$page->getTemplate()
		);
	}

	public function addSuggestions(VirtualPageContract $page): void
	{
		add_filter('page_template_hierarchy', function ($templates) {
			return array_values(array_unique($this->templates));
		}, 0);
	}
}
