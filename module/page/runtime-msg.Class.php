<?php
include_once '../lib/FormCleaner.Class.php';

/**
 * Atention! 
 * Url parameters are unfiltered yet. Are used crude for aplication logic build
 * 
 * 
 */
class runMessages extends FormCleaner {

	public $param_pageid = '';
	public $param_moduleid = '';
	public $param_pagename = '';
	public $param_modulename = '';
	public $param_id = '';
	public $param_a = '';

	function __construct($dard, $tag) {
		parent::__construct($dard, $tag);
		$this -> params($dard);
	}

	private function params($dard) {
		if ($dard -> arg) {
			isset($dard -> arg['pageid']) ? $this -> param_pageid = $dard -> arg['pageid'] : '';
			if (isset($_POST['page']) && empty($this -> param_pageid))
				$this -> param_pageid = $_POST['page'];
			isset($dard -> arg['moduleid']) ? $this -> param_moduleid = $dard -> arg['moduleid'] : '';
			if (isset($_POST['module']) && empty($this -> param_moduleid))
				$this -> param_moduleid = $_POST['module'];
			isset($dard -> arg['id']) ? $this -> param_id = $dard -> arg['id'] : '';
			isset($dard -> arg['a']) ? $this -> param_a = $dard -> arg['a'] : '';
			$query = "SELECT `pagename` FROM `page` WHERE id = '$this->param_pageid';";
			if (!empty($this -> param_pageid)) {
				$query = "SELECT `pagename` FROM `page` WHERE id = '$this->param_pageid';";
				$page = $dard -> selectDB($this -> param_pageid, $query, TRUE, 'string');
				$this -> param_pagename = $page;
			}
			if (!empty($this -> param_moduleid)) {
				$query = "SELECT `name` FROM `module`  WHERE id = '$this->param_moduleid';";
				$mod = $dard -> selectDB($this -> param_moduleid, $query, TRUE, 'string');
				$this -> param_modulename = $mod;
			}
		}
	}

	private function getedit($dard) {
		$msg = $this -> geteditsql($dard);
		$page = $this -> param_pageid;
		$module = $this -> param_moduleid;
		$id = $this -> param_id;
		$conf = array();
		if (!empty($this -> param_a)) {
			if ($msg['type'] == '1') {
				$conf[0] = 'Confirm';
				$conf[1] = 'Error';
				$conf[2] = 1;
				$conf[3] = 0;
			} else {
				$conf[0] = 'Error';
				$conf[1] = 'Confirm';
				$conf[2] = 0;
				$conf[3] = 1;
			}
			include_once '../www/template/module/page/forms/form-edit-run-msg.php';
		} else {
			echo '<p>Not enough parameters!</p>';
		}
	}

	private function edit($dard) {
		$this -> match("/^[\S\p{L}\p{M}*0-9\s]{1,255}$/u", $_POST['message'], 21) ? $message = $_POST['message'] : '';
		$this -> match("/^[0-9]+$/", $_POST['number'], 22) ? $number = $_POST['number'] : '';
		$this -> match("/^[0-9]+$/", $_POST['module'], 23) ? $module = $_POST['module'] : '';
		$this -> match("/^[0-9]+$/", $_POST['page'], 24) ? $page = $_POST['page'] : '';
		$this -> match("/^[0-1]+$/", $_POST['type'], 25) ? $type = $_POST['type'] : '';
		$this -> match("/^[0-9]+$/", $_POST['id'], 26) ? $id = $_POST['id'] : '';
		if (count($this -> errors) > 0) {
			$this -> retrive_error_msg('error-messages');
		} else {
			$query = "
					UPDATE `error_message` 
					SET `message` = '$message',
						`number` = '$number',
						`module` = '$module',
						`page` = '$page',
						`type` = '$type'
					WHERE `error_message`.`id` = '$id';";
			$result = $dard -> insertDB($_POST, $query);
			echo '<p>' . $result['info'] . '</p>';
			$this -> view($dard);
		}
	}

	private function add($dard) {
		$this -> match("/^[\S\p{L}\p{M}*0-9\s]{1,255}$/u", $_POST['message'], 21) ? $message = $_POST['message'] : '';
		$this -> match("/^[0-9]+$/", $_POST['module'], 23) ? $module = $_POST['module'] : '';
		$this -> match("/^[0-9]+$/", $_POST['page'], 24) ? $page = $_POST['page'] : '';
		$this -> match("/^[0-1]+$/", $_POST['type'], 25) ? $type = $_POST['type'] : '';
		if (count($this -> errors) > 0) {
			$this -> retrive_error_msg('error-messages');
		} else {
			$query = "SET @a = (SELECT MAX(`number`)+1 AS num FROM `error_message` WHERE `module` = '$this->param_moduleid');
					INSERT INTO `error_message` 
					(`id`, `message`, `number`, `module`, `page`, `type`)
					VALUES ( NULL, '$message', @a, '$module', '$page', '$type');";
			$result = $dard -> insertDB($_POST, $query);
			if (isset($result['id'])) {
				$this -> param_id = $result['id'];
				echo '<p>One row inserted with id: ' . $result['id'] . '</p>';
				$this -> view($dard);
			}
		}
	}

	private function geteditsql($dard) {
		$arg = '';
		if (isset($dard -> arg['id']) && is_numeric($dard -> arg['id'])) {
			$arg = $dard -> arg['id'];
			$query = "SELECT * FROM `error_message` WHERE `id` = '$arg';";
			return $dard -> selectDB($arg, $query, TRUE, 'array');
		}
	}
	/*
	 * 
	 *  
	 */
	private function view_Sql($dard) {
		$mod = $this -> param_moduleid;
		$page = $this -> param_pageid;
		if (!empty($this -> param_id) || isset($_POST['id'])) {
			!empty($this -> param_id) ? $id = $this -> param_id : $id = $_POST['id'];
			$where = "`id` = '$id'";
		} else if (!empty($this -> param_moduleid) && !empty($this -> param_pageid)) {
			$where = "module = $mod AND page = $page ";
		} else if (!empty($this -> param_moduleid) && $this -> param_a === 'view' && empty($this -> param_pageid)) {
			$where = "module = $mod";
		}
		$query = "SELECT `id`, `message`, `number`, `module`, (SELECT `pagename` FROM `page` WHERE `id`= `page`) AS page, `type_hint` FROM `error_message` WHERE $where";
		return $dard -> selectDB('', $query, TRUE, 'array');
	}
	/*
	 * 
	 * 
	 */
	private function build_view_edit_link($id){
		!empty($this -> param_pageid) ? $page = '&pageid=' . $this -> param_pageid : $page = '';
		!empty($this -> param_moduleid) ? $mod = '&moduleid=' . $this -> param_moduleid : $mod = '';
		$link = $page . $mod . '&id=' . $id;
		return '<a href="error-messages?a=edit' . $link . '">edit</a>';
		
	}
	/*
	 * 
	 * 
	 */
	private function show_module_mesages($dard, $tag){
		$th = array('Id', 'Message', 'Msg no', 'Module id', 'Page name', 'Type', 'Edit');
		$data = $this -> view_Sql($dard);
		if(is_array($data) && !empty($data)){
			for($i = 0; $i < count($data); $i++) {
				$link = $this -> build_view_edit_link($data[$i]['id']);
				$new = array_values($data[$i]);
				$data[$i] = $new;
				array_push($data[$i], $link);
			}
		}
		$tag -> print_table(array( 'th' => $th, 'data'=>$data));
	}

	public function job_control($dard, $tag) {
		if ($dard -> ajax) {
			// do ajax stuff
		} else {
			if ($this -> post) {
				if (empty($this -> param_a))
					return;
				switch ($this->param_a) {
					case 'add' :
						$this -> add($dard);
						break;
					case 'delete' :
						break;

					case 'edit' :
						$this -> edit($dard);
						break;
					default :
						'' ;
						break;
				}

			} else {
				switch ($this->param_a) {
					case 'add' :
						include_once '../www/template/module/page/forms/form-add-run-msg.php';
						break;
					case 'view' :
						$this ->show_module_mesages($dard, $tag);
						break;

					case 'edit' :
						$this -> getedit($dard);
						break;

					default :
						'';
						break;
				}
			}
		}
	}

}
?>