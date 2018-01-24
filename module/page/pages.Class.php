<?php

/**
 * 
 */
 
include_once '../lib/FormCleaner.Class.php';
class pages extends FormCleaner {
	
	function __construct($config, $DBconect, $myPage, $tag) {
		parent::__construct($config);
        $this->modules = $this->getModules($config, $DBconect);
        $this->setModuleIdName();
        $this->getMainPages($config, $DBconect, $this->module_id);
        $this->css = $this->getCssId($config, $DBconect);
    }
    
    public $modules ;
    public $module_id;
    public $module_name;
    public $mainPages;
    public $css;
    public $page;
    private $state = 0;
    private $confirm_message;
    
    
    
    
    private function getAll($query, $config, $DBconect){
        return $DBconect->selectDB('', $config, $query, TRUE, 'array');
    }
    
    private function failedTransaction($config, $DBconect){
        $this -> is_error = TRUE;
        $this -> generate_query('page');
        $this -> retrive_error_msg($config, $DBconect, 'page');
    }
    
    private function getModules($config, $DBconect){
        $query = "SELECT * FROM `module` ;";
        $modules = $this->getAll($query, $config, $DBconect);
        $this->modules = $modules;
        return $modules;
    }
    
    private function setModuleIdName(){
        if($this->post){
            if(isset($_POST['select_module']) && $_POST['select_module'] === 'select_module'){
                if(isset($_POST['module']) && $this->match("/^[0-9]+$/", $_POST['module'], 9)){
                    $this->module_id = $_POST['module'];
                    //$this->module_name =
                    foreach ($this->modules as $value) {
                        if($value['id'] === $this->module_id)
                            $this->module_name = $value['name'];
                    } 
                }
            }
        }
    }
    
    private function getMainPages($config, $DBconect, $module_id){
        if($module_id === null ) $module_id = 1;
        $query = "SELECT `id`, `pagename` FROM `page` WHERE `module_id` = $module_id AND `type` <> 'sub';";
        $pages = $DBconect->selectDB($module_id, $config, $query, TRUE, 'array');
        if(array_key_exists('id', $pages)) $pages = array( $pages);
        $this->mainPages = $pages;
    }
    
    private function getCssId($config, $DBconect){
        $query = "SELECT `id`, `href` FROM `css` WHERE `active` = 1 AND `general` = '0';";
        $result = $DBconect->selectDB('', $config, $query, TRUE, 'array');
        return $result;
    }
    
    private function addModule($config, $DBconect, $module){
        $query = "INSERT INTO `module`(`id`, `name`) VALUES (null, $module);";
        $DBconect->insertDB($module, $config, $query);
    }
    
    private function getPage($config, $DBconect, $page){
        $query = "SELECT * FROM `page` WHERE `pagename` = $page;";
        $this->page = $DBconect->selectDB($page, $config, $query, TRUE, 'array');
    }
    
    private function addPageValues() {
        $values = "";
        if(isset($_POST['pagename']) && $this->match("/^([a-zA-Z0-9\-]){1,50}$/", $_POST['pagename'], 1))
            $values = "(null,\n '".$_POST['pagename']."',\n '";
        if(isset($_POST['type']) && $this->match("/^((main)?(sub)?(top)?){1}$/", $_POST['type'], 2))
            $values .= $_POST['type']."',\n ";
        if(isset($_POST['parentpage']) && $this->match("/^([NULL]|[0-9])+$/", $_POST['parentpage'], 3))
            $values .= $_POST['parentpage'].",\n '";
        if(isset($_POST['title']) && $this->match("/^([\w\-\s\|]){1,70}$/", $_POST['title'], 4))
            $values .= $_POST['title']."',\n '";
        if(isset($_POST['pageURI']) && $this->match("/^([\w\-\.\/]){5,50}$/", $_POST['pageURI'], 5))
            $values .= 'template/module/' . $this->module_name . '/' . $_POST['pageURI']."',\n ";
        if(isset($_POST['module_id']) && $this->match("/^([0-9])+$/", $_POST['module_id'], 6))
            $values .= $_POST['module_id'].",\n ";
        if(isset($_POST['arg']) && $this->cleanNumber($_POST['arg'], 0, 1, 7))
            $values .= $_POST['arg'].",\n ";
        if(isset($_POST['css']) && $this->match("/^([NULL]|[0-9])+$/", $_POST['css'], 8))
            $values .= $_POST['css'].");";
        return $values;
    }

    private function addPage($config, $DBconect){
            
        $query = "INSERT INTO `page`
            (`id`, 
            `pagename`, 
            `type`, 
            `parentpage`, 
            `title`, 
            `pageURI`, 
            `module_id`, 
            `arg`, 
            `css`)
        VALUES ".$this->addPageValues();
        if ($this->post) {
            if(!$DBconect->insertDB($_POST, $config, $query)){
                $this->failedTransaction($config, $DBconect);
                return FALSE;
            }else{
                $this->confirm = "You succsefully set up a new page with the below stated details:<br>  ";
                $this->confirm .= "Page name = ". $_POST['pagename']. "<br>";
                $this->confirm .= "Type = ". $_POST['type']. "<br>";
                $this->confirm .= "Parent page = ". $_POST['parentpage']. "<br>";
                $this->confirm .= "Title = ". $_POST['title']. "<br>";
                $this->confirm .= "File name = ". $_POST['pageURI']. "<br>";
                $this->confirm .= "In module = ". $this->module_name . "<br>";
                $this->confirm .= "have arguments = ". $_POST['arg']. "<br>";
                $this->confirm .= "Css associated = ". $_POST['css']. "<br>";
                return TRUE;
            }
        }
    }
    
    private function ifAddPage($config, $DBconect){
        if(isset($_POST['add_page']) && $_POST['add_page'] === 'add'){
            $this->addPage($config, $DBconect);
        }
    }

    public function includeAddForm($config, $DBconect, $tag){
        switch ($this->state) {
            case '1':
                if(isset($_POST['add_page']) && $_POST['add_page'] === 'add'){
                    if($this->addPage($config, $DBconect)){
                        $this->state = 2;
                        $this->includeAddForm($config, $DBconect, $tag);
                        }
                }else{
                    include_once 'template/module/page/forms/form-addpage.php';
                }
                break;
            
            case '2':
                include_once 'template/module/page/confirm-message.php';
                
                break;
            
            default :
                if(isset($_POST['select_module']) && $_POST['select_module'] === 'select_module'){
                    $this->setModuleIdName();
                    $this->state = 1;
                    $this->includeAddForm($config, $DBconect, $tag);
                }else{
                    include_once 'template/module/page/forms/form-selet-module.php';
                }
                break;
        }
    }
    
    public function slect_box($arg, $attr, $tag, $default, $param){
        $wrap = array();
        $wrap = $tag->tag('select', $attr, '');
        if(is_array($default)){
            $tag->append_tag($wrap, $tag->tag('option', 'value="'.$default['id'].'"', $default[$param]));
        }else{
            $tag->append_tag($wrap, $tag->tag('option', 'value="0"', ''));
        }
        if(is_array($arg)){
            foreach ($arg as $value) {
                $tag->append_tag($wrap, $tag->tag('option', 'value="'.$value['id'].'"', $value[$param]));
            }
            $tag -> docOutput($wrap);
        }
    }


}




?>