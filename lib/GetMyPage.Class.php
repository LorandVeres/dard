<?php

class GetMyPage {

    const S = 'string';
    const A = 'array';
    const D = 'default';

    protected $main;
    protected $mainLinks;
    protected $top;
    public $pageUri;
    public $relativePath;
    protected $topLinks;
    protected $subPage;
    protected $class;
    protected $Meta;
    protected $nextSubPage;
    protected $arg;
    protected $URI;
    protected $isPage = 1;
    protected $haveArgument = FALSE;
    protected $pageArguments;
    protected $allPage;

    function __construct($config, $DBconect) {
        $this -> genURI();
        $this -> getTopPageName();
        $this -> setIsPage($config, $DBconect);
        $this -> getAllPage($config, $DBconect);
        $this -> getMeta($config, $DBconect);
        $this -> setPageUri();
        $this -> generate_relative_path();
        //$this->GetMainPages($config, $DBconect);
    }

    protected function fullURL() {
        $pageURL = 'http';
        //if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}   can be puted on if the https is on
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;

    }

    private function genURI() {
        $_SERVER["REQUEST_URI"] === "/" ? $this -> URI = NULL : $this -> URI = explode("/", trim($_SERVER["REQUEST_URI"], "/"));
    }

    private function generate_relative_path() {
        $relative = '';
        for ($i = 0; $i < count($this -> URI); $i++) {
            $relative .= '../';
        }
        $this -> relativePath = $relative;
    }

    private function getTopPageName() {
        !$this -> URI ? $this -> top = 'home' : $this -> top = $this -> URI[0];
    }

    private function setIsPage($config, $DBconect) {
        if ($this -> URI !== NULL) {
            $arg = $this -> URI[0];
            $where = "`pagename` = '$arg'";
        } elseif ($this -> URI === NULL) {
            $where = "`pagename` = 'home'";
            $arg = 'home';
        }
        $query = "SELECT `id`, `arg` FROM `page` WHERE " . $where . ";";
        $result = $DBconect -> selectDB($arg, $config, $query, TRUE, 'array');
        if ($result === null || (count($this -> URI) > 1 && $result['arg'] == 0))
            $result['id'] = 1;
        $this -> isPage = $result['id'];
        if (isset($result['arg'])) {
            if ($result['arg'] > 0)
                $this -> haveArgument = TRUE;
        }
    }

    private function getAllPage($config, $DBconect) {
        if (!$this -> isPage) {
            $this -> top = 'error';
            return;
        } else {
            $arg = $this -> isPage;
            $query = " 
            SELECT
                `pagename`,
                `type`,
                `parentpage`,
                `title`,
                `user_priv`,
                `pageURI`
            FROM
                `page`
            WHERE
                `id` = '$arg'
            ;";
            $query .= "
            SELECT
                `rel`,
                `type`,
                `href`
            FROM
                `linktag`
            WHERE
                `pageid` = '$arg' OR
                `general` = 1 
            ;";
            $this -> allPage = $DBconect -> selectDB($arg, $config, $query, TRUE, self::D);
        }

    }

    private function getMeta($config, $DBconect) {
        $query = "
            SELECT
                `name`, 
                `content`
            FROM 
                `pagemeta`
            WHERE 
                `active` = 1 AND `general` = 1 OR `pageid` = '$this->isPage'
        ;";
        $this -> Meta = $DBconect -> selectDB($this -> isPage, $config, $query, TRUE, self::A);
    }

    private function setPageUri() {
        $this -> pageUri = $this -> allPage[0]['pageURI'];
        return;
    }

    private function GetLinkArguments() {
        $arg = explode("/", $this -> CurentPageURL());
        $arg = array_slice($arg, 5);
        $this -> Arguments = $arg;

        unset($arg);
    }

}// end of class
?>