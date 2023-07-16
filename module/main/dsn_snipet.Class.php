<?php
include_once '../lib/FormCleaner.Class.php';
/**
 *
 * @author  Lorand Veres
 */
class dsn_snipet extends FormCleaner {

	use snipetHandler;
	public $modules;
	private $posible_actions = array('add_snipet', 'get_snipet', 'update_snipet', 'delete_snipet', 'snipets');
	private $action;

	function __construct($dard, $tag) {
		FormCleaner::__construct($dard);
		$this -> call_action($dard, $tag);
	}

/**
	 * undocumented function
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	private function get_url_params($dard) {
		if (isset($dard -> url_arguments['a'])) {
			if (in_array(str_replace('-', '_', $dard -> url_arguments['a']), $this -> posible_actions)) {
				$this -> action = str_replace('-', '_', $dard -> url_arguments['a']);
			}
		}
	}


/**
	 * undocumented function
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	private function call_action($dard, $tag) {
		$this -> get_url_params($dard);
		if ($this -> action !== NULL) {
			$fn = $this -> action;
			if (method_exists($this, $fn)) {
				$this -> $fn($dard, $tag);
			} else {
				echo "Action does not yet implemented. = " . $fn;
			}
		}
	}

}
?>