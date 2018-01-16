<?php

class GetMyPage {

    protected $links;
    protected $top;
    protected $pageUri;
    protected $relativePath;
    protected $subPage;
    protected $class;
    protected $Meta;
    protected $linkTag;
    protected $URI;
    protected $isPage = 1;
    protected $allPage;
    protected $userPriv;
    public $pageArguments;
    public $headers = array();

    function __construct($config, $DBconect, $_DARDSESSI, $tag) {
        $_DARDSESSI -> init_user_session($config, $DBconect);
        $this -> getAllPage($config, $DBconect);
        $this -> generate_relative_path();
        $this -> sendDoc($config, $DBconect, $tag, $_DARDSESSI);
    }

    public function fullURL() {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on")
            $pageURL .= "s";
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80" || $_SERVER["SERVER_PORT"] != "443") {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;

    }

    private function genURI() {
        $_SERVER["REQUEST_URI"] === "/" ? $this -> URI = NULL : $this -> URI = explode("/", trim($_SERVER["REQUEST_URI"], '/'));
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

    private function setIfTopPage($config, $DBconect) {
        if (preg_match("/^[\w-]*$/", $this -> top)) {
            $this -> sqlTopPage($config, $DBconect);
        } else {
            $this -> isPage = 1;
            $this -> top = error;
        }
    }

    private function sqlTopPage($config, $DBconect) {
        $arg = $this -> top;
        isset($_SESSION['user_priv']) ? $user = $_SESSION['user_priv'] : $user = FALSE;
        $query = "SELECT `id`, `type`, `user_priv`, `parentpage` FROM `page` WHERE `pagename` = '$arg';";
        $result = $DBconect -> selectDB($arg, $config, $query, TRUE, 'array');
        if (!$result['id']) {
            $this -> isPage = 1;
            $this -> top = 'error';
        } elseif ($result['id']) {
            if ($result['parentpage'] && ($result['type'] !== 'top' || $result['type'] !== 'main')) {
                $this -> isPage = 1;
            } else {
                $this -> isPage = $result['id'];
                $this -> userPriv = $result['user_priv'];
            }
        }
    }

    private function setIfSubPage($config, $DBconect) {
        $pos = strpos($this -> URI[1], '?');
        if ($pos !== false) {
            $sub_page = substr($this -> URI[1], 0, $pos);
            $this -> setLinkArguments(substr($this -> URI[1], $pos));
            preg_match("/^[\w-]*$/", $sub_page) ? $this -> sqlSubPage($config, $DBconect, $sub_page) : $this -> subPage = 1;
        } else {
            preg_match("/^[\w-]*$/", $this -> URI[1]) ? $this -> sqlSubPage($config, $DBconect, $this -> URI[1]) : $this -> subPage = 1;
        }
    }

    private function sqlSubPage($config, $DBconect, $sub_page) {
        $arg = $sub_page;
        $parent_id = "
                    SELECT
                        `parentpage`
                    FROM
                        `page`
                    WHERE
                        `pagename` = '$arg'
                    ";
        $query = "SELECT
                    S.`id`,
                    S.`pagename`,
                    S.`user_priv`,
                    P.`pagename` AS top_page
                FROM
                    `page` AS S,
                    `page` AS P
                WHERE
                    P.`id` = ($parent_id) AND S.`pagename` = '$arg';";
        $result = $DBconect -> selectDB($arg, $config, $query, TRUE, 'array');
        if (!$result || $result['top_page'] !== $this -> top) {
            $this -> subPage = 1;
            $this -> top = 'error';
        } elseif ($result) {
            if ($result['top_page'] !== $this -> top) {
                $this -> subPage = 1;
                $this -> top = 'error';
            } else {
                $this -> subPage = $result['id'];
                $this -> userPriv = $result['user_priv'];
            }
        }
    }

    private function setIsPage($config, $DBconect) {
        $this -> genURI();
        $this -> getTopPageName();
        if (count($this -> URI) <= 1 || $this -> URI === null) {
            $this -> setIfTopPage($config, $DBconect);
        } elseif (count($this -> URI) == 2) {
            $this -> setIfSubPage($config, $DBconect);
        } else {
            $this -> isPage = 1;
        }
    }

    private function prepAllPage($config, $DBconect) {
        $this -> setIsPage($config, $DBconect);
        if (count($this -> URI) > 1 && $this -> subPage > 1) {
            $this -> isPage = $this -> subPage;
        } elseif (count($this -> URI) <= 1 && $this -> isPage > 1) {
            $this -> isPage;
        } else {
            $this -> isPage = 1;
            $this -> top = 'error';
        }
        if($this->isPage === 1)
            array_push($this -> headers, header('HTTP/1.0 404 Not Found'));
    }

    private function getAllPage($config, $DBconect) {
        $this -> prepAllPage($config, $DBconect);
        $this -> checkUserPriv();
        $arg = $this -> isPage;
        $query = " 
            SELECT
                `pagename`,
                `title`,
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
                `css`
            WHERE
                `id` =(
                SELECT
                    `css`
                FROM
                    `page`
                WHERE
                    `id` = '$arg'
                ) OR `general` = 1;";
        $query .= "
            SELECT
                `name`, 
                `content`
            FROM 
                `pagemeta`
            WHERE 
                `active` = 1 AND `general` = 1 OR `pageid` = '$this->isPage'
            ;";
        $result = $DBconect -> selectDB($arg, $config, $query, TRUE, 'default');
        $this -> allPage = $result[0];
        $this -> Meta = $result[2];
        $this -> linkTag = $result[1];
        $this -> setPageUri();
        $result = array();
        unset($result);
    }

    private function setPageUri() {
        $this -> pageUri = $this -> allPage['pageURI'];
    }

    private function setLinkArguments($arg) {
        $arg = explode("&", ltrim($arg, '?'));
        $i = 0;
        foreach ($arg as $value) {
            $this -> pageArguments[$i]['param'] = substr($value, 0, strpos($value, '='));
            $this -> pageArguments[$i]['value'] = substr($value, (strpos($value, '=') + 1));
            $i++;
        }
    }

    private function createDocOut($config) {
        $doc = "<!DOCTYPE html>\n";
        $doc .= "<html lang=" . $config -> language . ">\n";
        $doc .= $this -> createHtmlHead();
        $doc .= "\t<body>\n";
        return $doc;
    }

    private function createHtmlHead() {
        $head = "\t<head>\n";
        $head .= $this -> createHtmlMetaTags();
        $head .= $this -> createHtmlLinkTags();
        $head .= "\t</head>\n";
        return $head;
    }

    private function createHtmlMetaTags() {
        $meta = "\t\t<title>" . $this -> allPage['title'] . "</title>\n";
        foreach ($this->Meta as $key => $value) {
            $meta .= "\t\t<meta name=\"" . $this -> Meta[$key]['name'] . "\" content=\"" . $this -> Meta[$key]['content'] . "\">\n";
        }
        return $meta;
    }

    private function createHtmlLinkTags() {
        $link = "";
        foreach ($this->linkTag as $key => $value) {
            $rel = $this -> linkTag[$key]['rel'];
            $type = $this -> linkTag[$key]['type'];
            $href = $this -> relativePath . $this -> linkTag[$key]['href'];
            $link .= "\t\t<link rel=\"" . $rel . "\" type=\"" . $type . "\" href=\"" . $href . "\" />\n";
        }
        return $link;
    }

    private function sendDoc($config, $DBconect, $tag, $_DARDSESSI) {
        $this -> sendHeaders();
        if (printf("%s", $this -> createDocOut($config))) {
            if ($this -> pageUri && file_exists($this -> pageUri)) {
                $myPage = $this;
                
                include_once $this -> pageUri;
            } elseif (!$this -> pageUri) {
                array_push($this -> headers, header('HTTP/1.0 404 Not Found'));
                if (file_exists($this -> pageUri)) {
                    include_once $this -> pageUri;var_dump('$expression');
                } else {
                    //unknow internal error redirection should be here
                }

            }
        }
        $doc = "\t</body>\n";
        $doc .= "</html>\n";
        printf("%s", $doc);
    }

    private function sendHeaders() {
        foreach ($this->headers as $value) {
            $value;
        }
    }

    private function checkUserPriv() {
        if ($this -> userPriv) {
            $priv = gmp_and(gmp_import($_SESSION['user_priv']), gmp_import($this -> userPriv));
            if (!gmp_strval($priv)) {
                array_push($this -> headers, header('HTTP/1.0 403 Forbidden'));
                $this -> isPage = 6;
                $this -> top = 'forbidden';
            }
        }
    }

}// end of class
?>