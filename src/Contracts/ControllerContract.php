<?php

namespace Eighteen73\VirtualPages\Contracts;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
interface ControllerContract {

	/**
	 * Init the controller, fires the hook that allow consumer to add pages
	 */
	function init(): void;

	/**
	 * Register a page object in the controller
	 */
	function addPage(VirtualPageContract $page): VirtualPageContract;

	/**
	 * Run on 'do_parse_request' and if the request is for one of the registerd
	 * setup global variables, fire core hooks, requires page template and exit.
	 */
	function dispatch(): void;

}
