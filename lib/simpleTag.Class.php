<?php

/**
 *===============================================================================
 *
 * MIT License
 *
 * Copyright (c) 2020 Lorand Veres (lorand.mast@gmail.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * ===============================================================================
 *
 *
 *
 * ABOUT simpleTag
 * ---------------
 * Easy to use and create simple HTML blocks, what are more often in use to format
 * data from a given array using PHP.
 *
 * The intended purpose it is just to print the data in a very handy fashion without
 * having to mess up the php code. simpleTag is not intended to manipulate any XML
 * documents or fragments.
 *
 * The following Objects
 *
 * simpleTag -> inline_tag
 * simpleTag -> single_tag
 *
 * Will be printed one per row without increasing the indenting deepness after
 * subsequent inline tags. The tags listed in the arrays are not the ones specified
 * until the HTML4 standard. These arrays were created to print out the tags in the
 * desired way. At this time are still missing elements yet.
 *
 *
 */

class simpleTag {

	private $Doc = '';
	private $inline_tag = array('a', 'abbr', 'acronym', 'b', 'bdo', 'big', 'br',  'button', 'i', 'cite', 'code', 'dfn', 'dd', 'dt', 'em', 'hr', 'img', 'input', 'kbd', 'li', 'link', 'map', 'meta', 'object', 'option', 'q', 'script', 'samp',  'small', 'span', 'strong', 'sub', 'sup', 'textarea', 'td', 'th', 'time', 'title', 'tt', 'var');
	private $block_tag = array('address', 'article', 'aside', 'blockquote', 'canvas', 'div', 'dl', 'fieldset', 'figcaption', 'figure', 'footer', 'form', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'header', 'label', 'legend', 'main', 'nav', 'noscript', 'ol', 'p', 'pre', 'section', 'select', 'table', 'tr', 'tfoot', 'ul', 'video');
	private $single_tag = array('!DOCTYPE html', 'meta', 'link', 'br', 'img', 'hr', 'input', 'embed', 'bgsound', 'base', 'col', 'source');
	private $indentNum = 0;
	private $set_count = TRUE;

	function __construct() {

	}

	/*
	 * Append one tag into another
	 * Use:
	 *
	 * simpleTag -> append_tag($into, $tag);
	 *
	 * @return void
	 * @author  Lorand Veres
	 *
	 */
	public function append_tag(&$into, $tag) {
		if (!array_key_exists(1, $into)) {
			if (!array_push($into, $tag))
				;
		} else {
			if (!array_push($into[1], $tag))
				;
		}
	}

	/*
	 * Create a tag. When single tags are printed like <input> the $txt variable
	 * value provided should be an empty string "". No need to add each attribute
	 * at once, it can take a string with multiple attributes concatenated like in
	 * the example.
	 * Use:
	 * $tag = simpleTag -> tag("input", 'type="text" name="fname" value="first name" style="width:100px"', '');
	 *
	 * @return string
	 * @author  Lorand Veres
	 */
	public function tag($tagname, $attr, $txt) {
		$o = "<";
		$c = ">";
		//  output   /
		$e = chr(47);
		//  output   space
		$s = chr(32);
		//  output   "
		$q = chr(34);
		//   output   tab
		$t = chr(9);
		// output   =
		$eq = chr(61);
		//  output   new line
		$n = chr(10);

		$single = $this -> check_single_tag($tagname);
		if (!$single && !empty($tagname)) {
			$tag = array();
			$tag[] = $o . $tagname . $this -> setAttr($attr) . $c;
			$tag[] = !empty($txt) || $txt === '0' ? array($txt) : array();
			$tag[] = $o . $e . $tagname . $c;

		} elseif ($single) {
			$tag = ($o . $tagname . $this -> setAttr($attr) . $c . $n);
		} elseif(empty($tagname)) {
			$tag = $txt;
		}
		return $tag;
	}

	/*
	 *  @parameter passed is a string. Returning TRUE if in a single_tag array
	 * Helper function inside docOutput(...)
	 *
	 * @return bool
	 * @author  Lorand Veres
	 */
	private function check_single_tag($tag) {
		in_array($tag, $this -> single_tag) ? $b = true : $b = false;
		return $b;
	}

	/**
	 * @param passed is a string. Returning TRUE if it is an opening tag
	 * Helper function inside print_block_tag(...).
	 *
	 * @return bool
	 * @author  Lorand Veres
	 */
	private function check_start_tag($value) {
		substr($value, 0, 2) !== "</" ? $start_tag = true : $start_tag = false;
		return $start_tag;
	}

	/**
	 * @param passed is a string. Returning TRUE if it is an empty end tag
	 * Helper function inside print_block_tag(...).
	 *
	 * @return bool
	 * @author  Lorand Veres
	 */
	private function check_empty_end_tag($value){
		substr($value, 0, 3) === "</>" ? $bool = true : $bool = false;
		return $bool;
	}

	/*
	 * @parameter passed is a string. Returning TRUE if if is a plain text, it may contain
	 * inline elements embeded in to the text.
	 * Helper function inside docOutput(...).
	 *
	 * @return bool
	 * @author  Lorand Veres
	 */
	private function check_plain_txt($value) {
		$start_tag = false;
		if(is_array($value))
			$value = $value[0];
		if(is_string($value))
			substr($value, 0, 1) !== "<" & substr($value, -1, 1) !== ">" ? $start_tag = true : $start_tag = false;
		return $start_tag;
	}

	/*
	 * Cheking the type of the tag.
	 * Helper function inside docOutput.
	 *
	 * @return bool
	 * @author  Lorand Veres
	 */
	private function check_tag_type($tag, $array) {
		$n = false;
		$txt_tag;
		if (is_array($tag)) {
			if (count($tag) >= 1){
				is_string($tag[0]) ? $txt_tag = $tag[0] : null;
			}
		} elseif (is_string($tag)) {
			$txt_tag = $tag;
		}
		$tag_array = explode(' ', $txt_tag);
		$tag_array[0] = str_replace('<', '', $tag_array[0]);
		$tag_array[0] = str_replace('/', '', $tag_array[0]);
		$tag_array[0] = str_replace('>', '', $tag_array[0]);
		in_array($tag_array[0], $array) ? $n = true : $n = false;
		return $n;
	}

	/**
	 *
	 *
	 * Helper function inside docOutput.
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	private function print_inline_tag($value) {
		$tf = str_pad("", $this -> indentNum + 1, "\t");
		$loop_inline_tags = function($var, $tf){
			$inline = '';
			if(is_array($var)){
				foreach ($var as $key => $val) {
					if(is_string($val)){
						$inline .= $val;
					} elseif (is_array($val)){
						if(count($val) === 3 ){
							if($this -> check_tag_type(preg_replace('/[<\/>]+/i', "" , $val[0] ), $this -> inline_tag)) {
								$temp = $val[0] . $val[1][0] . $val[2];
								$inline .= str_replace("\n", "", $temp);
							}
						}
					}
				}
			}elseif(is_string($var)){
				$inline .= $var;
			}
			return $inline;
		};
		if(is_array($value[1])){
			if(!isset($value[1][0]) && empty($value[1]))
			 	$value[1][0] = '';
			printf("%s", $tf . $value[0] . $loop_inline_tags($value[1], $tf) . $value[2] . "\n");
		}else if(is_string($value[1])){
			printf("%s", $tf . $value[0] . $loop_inline_tags($value[1], $tf) . $value[2] . "\n");
		}
	}

	/**
	 * Helper function inside docOutput.
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	private function print_text($value) {
		$tf = str_pad("", $this -> indentNum + 1, "\t");
		if ($this -> check_plain_txt($value))
			is_array($value) ? printf("%s", $tf . $value[0] . "\n") : ( is_string($value) ? printf("%s", $tf . $value . "\n") : '' );
	}

	/**
	 * Helper function inside docOutput.
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	private function print_single_tag($value) {
		$tf = str_pad("", $this -> indentNum + 1, "\t");
		if(is_string($value))
			printf("%s", $tf . $value . "");
		if(is_array($value)) {
			if(count($value) === 1 && isset($value[0]))
				printf("%s", $tf . $value[0] . "");
		}
		
	}

	/**
	 * Helper function inside docOutput.
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	private function print_block_tag($value) {
		$tf = str_pad("", $this -> indentNum, "\t");
		// checking for very first block tag if start indenting > 0
		if ($this -> set_count & $this -> indentNum > 0) {
			$value = $tf . $value . "\n";
			$this -> set_count = FALSE;
			printf("%s", $value);
		}
		// printing the block tags
		$this -> check_tag_type($value, $this -> block_tag) ? printf("%s", $tf . $value . "\n") : '';
		!$this -> check_start_tag($value) || $this -> check_tag_type($value, $this -> inline_tag) ? ( !$this -> check_empty_end_tag($value) ? $this -> indentNum-- : null ): null;
	}

	/*
	 *  Helper function, appending the attributes, used inside simpleTag ->tag(..)
	 *  Attributes can be passed to the tag function as a string or as an associative array
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	private function setAttr($attr) {
		$attributes = '';
		if (is_array($attr) && !empty($attr)) {
			foreach ($attr as $key => $value) {
				is_numeric($key) && is_string($value)? ($attributes .= $value) : ($attributes .= ' ' . $key . '="' . $value . '"');
			}
		} elseif (is_string($attr) && !empty($attr)) {
			$attributes .= ' ' . $attr;
		}
		return $attributes;
	}

	/*
	 * A recursive function doing the heavy printing.
	 * @argument array(). It is a document fragment created under the form of
	 * a multidimensional Array. See example .
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	private function docOutput($argument) {
		foreach ($argument as $key => $value) {
			if (is_array($value) && !empty($value)) {
				if (isset($value[0]) && is_string($value[0])) {
					if ($this -> check_tag_type($value, $this -> block_tag)) {
						// increment the indenting if block tag
						$this -> check_start_tag($value[0]) ? $this -> indentNum++ : '';
						$this -> docOutput($value);
					} elseif ($this -> check_tag_type($value, $this -> inline_tag) && count($value) === 3) {
						$this -> print_inline_tag($value);
					} elseif (count($value) >= 1) {
						if(count($value) === 1 && $this -> check_tag_type($value, $this -> single_tag)) {
							$this -> print_single_tag($value);
						} elseif(count($value) === 1 && $this -> check_plain_txt($value)){
							$this -> print_text($value);
						} elseif(count($value) >= 1){
							foreach ($value as $val){
								if(is_array($val)){
									if($this -> check_tag_type($val, $this -> inline_tag)){
										$this -> print_inline_tag($val);
									}elseif($this -> check_tag_type($val, $this -> single_tag)){
										$this -> print_single_tag($val);
									}else{
										$this -> docOutput($val);
									}
								}elseif(is_string($val) && $this -> check_tag_type($value, $this -> single_tag)){
									$this -> print_single_tag($val);
								}elseif(is_string($val) && $this -> check_plain_txt($val)){
									$this -> print_text($val);
								}elseif($this -> check_tag_type($value, $this -> block_tag)){
									$this -> print_block_tag($val);
								}
							}
						}
					}
				} else {
					$this -> docOutput($value);
				}
			} elseif (is_string($value)) {
				if ($this -> check_tag_type($value, $this -> single_tag)) {
					$this -> print_single_tag($value);
				} else {
					$this -> print_block_tag($value);
				}
			}
		}
	}

	/**
	 * Print the document and reset the internal objects to default values
	 *
	 * @param indentNum = int. Is optional, helpful if you would like to
	 * align the indentation in your HTML output to the existing position
	 * for improved human visibility.
	 * Use:
	 * simpleTag -> print_doc($doc_array, [$indentNum]);
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	public function print_doc($arguments) {
		$arg = func_get_args();
		if (isset($arg[1]))
			$this -> indentNum = $arg[1];
		$this -> docOutput($arg[0]);
		$this -> indentNum = 0;
		$this -> set_count = TRUE;
	}

	/**
	 * Setting up the attributes array, if not provided one array with
	 * empty elements initialised
	 * Helper function in print_table
	 *
	 * @return $in_attr (array)
	 * @author  Lorand Veres
	 */
	private function table_attr($my_data) {
		$in_attr =array();
		if (isset($my_data['attr']) && !empty($my_data['attr'])) {
			$in_attr = $my_data['attr'];
		} else {
			if(!empty($my_data['data']) || $my_data['data'] !== NULL){
				for ($i = 0; $i < count($my_data['data'][0]); $i++) {
					$in_attr[$i] = '';
				}
			}
		}
		return $in_attr;
	}

	/**
	 * Self deducting that the table head is generated
	 * Helper function in print_table
	 *
	 * @return $table (array)
	 * @author  Lorand Veres
	 */
	private function table_head($my_data, $in_attr, $table) {
		if (!empty($my_data['th']) || $my_data['th'] !==NULL) {
			$tr = $this -> tag('tr', '', '');
			for ($i = 0; $i < count($my_data['th']); $i++) {
				!empty($in_attr) ? $attr = $in_attr[$i] : $attr ='';
				$this -> append_tag($tr, $this -> tag('th', $attr, $my_data['th'][$i]));
			}
			$this -> append_tag($table, $tr);
		}
		return $table;
	}

	/**
	 * Numeric data will have cells style text-align:right
	 *
	 * @return string
	 * @author  Lorand Veres
	 */
	private function cell_numeric_right($attr, $data){
		if(is_numeric($data)){
			if(strpos($attr, 'text-align:left') || strpos($attr, 'text-align: left')){
				str_replace('text-align:left', 'text-align: right ', $attr) || str_replace('text-align: left', 'text-align: right ', $attr);
			}else{
				$attr .= 'style="text-align: right; "';
			}
		}
		return $attr;
	}

	/**
	 * Self deducting that the table body is generated.
	 * Helper function in print_table
	 *
	 * @return $table (array)
	 * @author  Lorand Veres
	 */
	private function table_body($my_data, $in_attr, $table) {
		if (isset($my_data['data'])) {
			$attr = $in_attr;
			for ($i = 0; $i < count($my_data['data']); $i++) {
				$tr[$i] = $this -> tag('tr', '', '');
				for ($j = 0; $j < count($my_data['data'][$i]); $j++) {
					$my_data['data'][$i][$j] === NULL ? $my_data['data'][$i][$j] = '' : $my_data['data'][$i][$j] ;
					$in_attr = $attr;
					$in_attr[$j] = $this -> cell_numeric_right($in_attr[$j], $my_data['data'][$i][$j]);
					$this -> append_tag($tr[$i], $this -> tag('td', $in_attr[$j], $my_data['data'][$i][$j]));
				}
				$this -> append_tag($table, $tr[$i]);
			}
		}
		return $table;
	}

	/**
	 * Print the table. All array elemets are optinal but 'data' .
	 *
	 * @param  array('tb' => string, 'th' => array(), 'attr' => array(), 'data' => array(array(), array()....))
	 * 'tb' = string like, table attributes
	 * 'th' = th elements text values
	 * 'attr' = optional attributes like style. it has no individual id posibility yet
	 * 'data' = the text data value what will be inserted in each cell
	 *
	 * @param  int, the indentation deepness optional
	 *
	 * @return void
	 * @author Lorand Veres
	 */
	public function print_table() {
		$my_data = func_get_arg(0);
		isset($my_data['tb']) ? $tb_attr = $my_data['tb'] : $tb_attr = '';
		$table = $this -> tag('table', $tb_attr, '');
		$in_attr = $this -> table_attr($my_data);
		if (func_num_args() > 1)
			$this -> indentNum = func_get_arg(1);
		$table = $this -> table_head($my_data, $in_attr, $table);
		$table = $this -> table_body($my_data, $in_attr, $table);
		$this -> print_doc($table);
	}

	/**
	 * Print lists.
	 * @param First param can be string or aray to define the list type and
	 * maybe suply attributes
	 * @param Second param is an array of list elemts, or 2 dimensional array for definition lists
	 * @param Third param optional to increase deepness
	 *
	 * @param example array('item 1', 'ietm 2', 'item 3', 'item 4');
	 * Use:
	 * print_list('ul', array('item 1', 'ietm 2', 'item 3', 'item 4'), 3)
	 * print_list(array('ol', 'start="8"'), array('item 1', 'ietm 2', 'item 3', 'item 4'), 3)
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	public function print_list() {
		$my_list_type = func_get_arg(0);
		$my_items = func_get_arg(1);
		if (func_num_args() > 2)
			$this -> indentNum = func_get_arg(2);
		is_array($my_list_type) & isset($my_list_type[1]) ? $attr = $my_list_type[1][0] : $attr = '';
		!is_string($my_list_type) ? $list_type = $my_list_type[0] : $list_type = $my_list_type;
		$list = $this -> tag($list_type, $attr, '');
		if ($list_type === 'ol' || $list_type === 'ul') {
			foreach ($my_items as $key => $value) {
				$this -> append_tag($list, $this -> tag('li', '', $value));
			}
		} elseif ($list_type === 'dl') {
			foreach ($my_items as $value) {
				$this -> append_tag($list, $this -> tag('dt', '', $value[0]));
				$this -> append_tag($list, $this -> tag('dd', '', $value[1]));
			}
		}
		$this -> print_doc($list);
	}

	/**
	 * Returns a select option group
	 * @param (string) $attr , representing select element attributes
	 * @param (array) $values = array( array('value="peach"' , 'Peach'), array('value="apple"' , 'Apple'))
	 * @param (int) the indent deepnes, optional
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	private function build_select_option($attr, $values){
		if(func_num_args() > 2)
			$this -> indentNum = func_get_arg(2);
		$select_el = $this -> tag('select', $attr, '');
		for($i = 0; $i < count($values); $i++) {
			if(is_array($values[$i])) {
				$clean = array_values($values[$i]);
				substr($clean[0], 0, 6) === 'value=' ? $clean[0] : $clean[0] = 'value="'.$clean[0].'"';
				$this -> append_tag($select_el, $this -> tag('option', $clean[0], $clean[1]));
			}
		}
		return $select_el;
	}

	// Print out a select option group
	public function print_select_option($attr, $values){
		$this -> print_doc($this -> build_select_option($attr, $values));
	}

	/**
	 * Build an input tag
	 * @param (array) array(label =>array('attr'=>for="first-name"', text=>'First name') , input=>array('attr'=>'type="text" name="sure-name"', 'text'=>'')))
	 *
	 * @return void
	 * @author  Lorand Veres
	 */
	private function build_input_tag($cur){
		$input_tag = array();//echo "<pre>";var_dump($cur);echo"</pre>";
		switch ($cur[0]) {
			case 'select':
				$input_tag[] = $this -> print_select_option($new['select']['attr'], $new['select']['val']);
				break;
			default :
				$input_tag[] = $this -> tag($cur[0], $this ->setAttr($cur[1]), $cur[2]);
				break;
		}
		return $input_tag;
	}

	// Print input tag
	public function print_input_tag($cur){
		$this -> print_doc($this -> build_input_tag($cur));
	}

	// build a bloc of input tags
	private function build_input_bloc($bloc, $cur_array){
		foreach ($cur_array as $value) {
			$this -> append_tag($bloc, $this -> build_input_tag($value));
		}
		return $bloc;
	}

	// Print a bloc of input tags
	public function print_input_bloc_tags($bloc, $cur_array){
		$this -> print_doc($this -> build_input_bloc($bloc, $cur_array));
	}

	/**
	 * build a simple form
	 * @param $form a multidimensional array
	 *  $form['attr'] (string) representing form element attributes
	 *  $form['fields'] (array) (array(label =>array('attr'=>for="first-name"', text=>'First name') , input=>array('attr'=>'type="text" name="sure-name"', 'text'=>''))))
	 *  $form['header'] (array) (header_type, header_text)
	 * @param $b (int) the indent deepnes, optional
	 *
	 * @return ( array ) the new form
	 * @author  Lorand Veres
	 */
	private function build_simple_form($form){
		$submit = '';
		//$args_num = func_num_args();
		//if($args_num > 1)  $this -> indentNum = func_get_arg(1);
		$new_form = $this -> tag('form', $form['attr'], '');
		if(isset($form['header']) && is_array($form['header']))
			$this -> append_tag($new_form, $this -> tag($form['header'][0], '', $form['header'][1]));
		for($i = 0; $i <= count($form['fields'])-1; $i++) {
			if(is_array($form['fields'][$i])) {
				$form_body = $this -> build_input_tag($form['fields'][$i]);
				foreach ($form_body as $val) {
					$this -> append_tag($new_form, $val);
				}
			}
		}
		return $new_form;
	}

	// Print out a form
	public function print_simple_form($form){
		$args_num = func_num_args();
		if($args_num >= 2)  $this -> indentNum = func_get_arg(1);
		$this -> print_doc($this -> build_simple_form($form));
	}

}// end of class
?>
