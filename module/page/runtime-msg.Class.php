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

	function __construct($config, $DBconect, $myPage, $tag) {
		$config -> debugMYSQL = TRUE;
		parent::__construct($config, $DBconect, $myPage, $tag);
		$this -> params($config, $DBconect, $myPage);
	}

	private function params($config, $DBconect, $myPage) {
		if ($myPage -> arg) {
			isset($myPage -> arg['pageid']) ? $this -> param_pageid = $myPage -> arg['pageid'] : '';
			if (isset($_POST['page']) && empty($this -> param_pageid))
				$this -> param_pageid = $_POST['page'];
			isset($myPage -> arg['moduleid']) ? $this -> param_moduleid = $myPage -> arg['moduleid'] : '';
			if (isset($_POST['module']) && empty($this -> param_moduleid))
				$this -> param_moduleid = $_POST['module'];
			isset($myPage -> arg['id']) ? $this -> param_id = $myPage -> arg['id'] : '';
			isset($myPage -> arg['a']) ? $this -> param_a = $myPage -> arg['a'] : '';
			$query = "SELECT `pagename` FROM `page` WHERE id = '$this->param_pageid';";
			if (!empty($this -> param_pageid)) {
				$query = "SELECT `pagename` FROM `page` WHERE id = '$this->param_pageid';";
				$page = $DBconect -> selectDB($this -> param_pageid, $config, $query, TRUE, 'string');
				$this -> param_pagename = $page;
			}
			if (!empty($this -> param_moduleid)) {
				$query = "SELECT `name` FROM `module`  WHERE id = '$this->param_moduleid';";
				$mod = $DBconect -> selectDB($this -> param_moduleid, $config, $query, TRUE, 'string');
				$this -> param_modulename = $mod;
			}
		}
	}

	private function getedit($config, $DBconect, $myPage) {
		$msg = $this -> geteditsql($config, $DBconect, $myPage);
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

	private function edit($config, $DBconect) {
		$this -> match("/^[\S\p{L}\p{M}*0-9\s]{1,255}$/u", $_POST['message'], 21) ? $message = $_POST['message'] : '';
		$this -> match("/^[0-9]+$/", $_POST['number'], 22) ? $number = $_POST['number'] : '';
		$this -> match("/^[0-9]+$/", $_POST['module'], 23) ? $module = $_POST['module'] : '';
		$this -> match("/^[0-9]+$/", $_POST['page'], 24) ? $page = $_POST['page'] : '';
		$this -> match("/^[0-1]+$/", $_POST['type'], 25) ? $type = $_POST['type'] : '';
		$this -> match("/^[0-9]+$/", $_POST['id'], 26) ? $id = $_POST['id'] : '';
		if (count($this -> errors) > 0) {
			var_dump($this -> errors);
			$this -> retrive_error_msg($config, $DBconect, 'error-messages');
		} else {
			$query = "
					UPDATE `error_message` 
					SET `message` = '$message',
						`number` = '$number',
						`module` = '$module',
						`page` = '$page',
						`type` = '$type'
					WHERE `error_message`.`id` = '$id';";
			$result = $DBconect -> insertDB($_POST, $config, $query);
			echo '<p>' . $result['info'] . '</p>';
			$this -> view($config, $DBconect);
		}
	}

	private function add($config, $DBconect) {
		$this -> match("/^[\S\p{L}\p{M}*0-9\s]{1,255}$/u", $_POST['message'], 21) ? $message = $_POST['message'] : '';
		$this -> match("/^[0-9]+$/", $_POST['module'], 23) ? $module = $_POST['module'] : '';
		$this -> match("/^[0-9]+$/", $_POST['page'], 24) ? $page = $_POST['page'] : '';
		$this -> match("/^[0-1]+$/", $_POST['type'], 25) ? $type = $_POST['type'] : '';
		if (count($this -> errors) > 0) {
			var_dump($this -> errors);
			$this -> retrive_error_msg($config, $DBconect, 'error-messages');
		} else {
			$query = "SET @a = (SELECT MAX(`number`)+1 AS num FROM `error_message` WHERE `module` = '$this->param_moduleid');
					INSERT INTO `error_message` 
					(`id`, `message`, `number`, `module`, `page`, `type`)
					VALUES ( NULL, '$message', @a, '$module', '$page', '$type');";
			$result = $DBconect -> insertDB($_POST, $config, $query);
			if (isset($result['id'])) {
				$this -> param_id = $result['id'];
				echo '<p>One row inserted with id: ' . $result['id'] . '</p>';
				$this -> view($config, $DBconect);
			}
		}
	}

	private function geteditsql($config, $DBconect, $myPage) {
		$arg = '';
		if (isset($myPage -> arg['id']) && is_numeric($myPage -> arg['id'])) {
			$arg = $myPage -> arg['id'];
			$query = "SELECT * FROM `error_message` WHERE `id` = '$arg';";
			return $DBconect -> selectDB($arg, $config, $query, TRUE, 'array');
		}
	}
	/*
	 * 
	 *  
	 */
	private function view_Sql($config, $DBconect) {
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
		return $DBconect -> selectDB('', $config, $query, TRUE, 'array');
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
	private function show_module_mesages($config, $DBconect, $tag){
		$th = array('Id', 'Message', 'Msg no', 'Module id', 'Page name', 'Type', 'Edit');
		$data = $this -> view_Sql($config, $DBconect);
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

	public function job_control($config, $DBconect, $myPage, $tag) {
		if ($myPage -> ajax) {
			// do ajax stuff
		} else {
			if ($this -> post) {
				if (empty($this -> param_a))
					return;
				switch ($this->param_a) {
					case 'add' :
						$this -> add($config, $DBconect);
						break;
					case 'delete' :
						break;

					case 'edit' :
						$this -> edit($config, $DBconect);
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
						$this ->show_module_mesages($config, $DBconect, $tag);
						break;

					case 'edit' :
						$this -> getedit($config, $DBconect, $myPage);
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