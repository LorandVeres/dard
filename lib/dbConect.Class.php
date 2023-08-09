<?php 
//if(!defined('DARDSTATUS') ) exit();

/**
 * 
 */
class dbConect extends DardConfig{
	
    const S = 'string';
    const A = 'array';
    const D = 'default';
        
	
	public function insertDB($arg, $query)
	{
		$link = mysqli_connect($this->cf_host, $this->cf_user, $this->cf_password, $this->cf_db);
		
		$query = $this->prepareQuery($link, $query, $arg);
		
		//$result = array();
		if(is_string($query)){
			$j=true;
			if (mysqli_multi_query($link, $query)) {
				do{
					if($result = mysqli_store_result($link)){
					    //just do nothing'
					    mysqli_free_result($result);
					}
						//mysqli_free_result($result);
					if ( mysqli_more_results($link)) {
						$j =true;
					}else{
						$j = false;
					}
				} while($j && mysqli_next_result($link));
			}if(mysqli_error($link) && $this->cf_debug_MYSQL === TRUE){
                $num = mysqli_errno($link);
                $error = mysqli_error($link);
                $this->debug($num, $error, $query);
            }
		}
		$result = array();
		$result['id'] = mysqli_insert_id($link);
		$result['info'] = mysqli_info($link);
		mysqli_close($link);
		return $result;
		
	}
	
	
	public function selectDB($arg, $query, $assoc, $return_format){
			
		$link = mysqli_connect($this->cf_host, $this->cf_user, $this->cf_password, $this->cf_db);
		
		$query = $this->prepareQuery($link, $query, $arg);
            $i=0;
			$j=true;
			$response = array();
			if (mysqli_multi_query($link, $query)) {
				do{
					if ($result = mysqli_store_result($link)) {
					    $k = 0;
                        $inner = array();
						if(!mysqli_num_rows($result)) {
                                $response[$i] = NULL;
                        }else{
                            if(!$assoc){
                                if(mysqli_num_rows($result) === 0) $response[$i] = NULL ;
                                while ($row = mysqli_fetch_row($result)) {
                                    $inner[$k] = $row ;
                                    $k++;
                                }
                            }elseif($assoc){
                                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                    $inner[$k] = $row;
                                    $k++;
                                }
                            }
                            if(count($inner) > 1 ) $response[$i] = $inner;
                            if(count($inner) === 1 )$response[$i] = $inner[0];
                        }
					}
                    $result ? mysqli_free_result($result) : '';
				    if ( mysqli_more_results($link)) {
						$i++;
						$j =true;
					}else{
						$j = false;
					}
					
					
				} while($j && mysqli_next_result($link));
			}elseif(mysqli_error($link)){
			    $num = mysqli_errno($link);
                $error = mysqli_error($link);
			    if($this->cf_debug_MYSQL === TRUE){
			        if( strpos( $error, "Duplicate entry", 0) !== false ) {
                        $errarray = explode(" ", $error);
			            $error = $errarray[0] . " " . $errarray[1] . " for : " . str_replace("'", "", $errarray[2]) ." ." ;
			            mysqli_close($link);
			            return $error;
                    }else{
                        $this->debug($num, $error, $query);
                    }
			    }elseif( stripos( mysqli_error($link), "Duplicate entry") ) {
			        $return_format= 'string';
			        $response = $error;
			    }
            }
		
		
		mysqli_close($link);
		return $this->packRows($response, $return_format);
		
	}
	
	public function stmt($table, $arg, $stmt, $data ) {
		$bool = true; 
		$argtext = '';
		$query = '';
		!empty($table) ? $argtext = "'".$table . "'," : $argtext ='';
		foreach ( $arg as $key => $val) {
			!$bool ? $arg[$key] = ", '". $val ."'" : $arg[$key] = "'". $val ."'";
			$bool = false;
			$argtext .= $arg[$key];
		}
		if(count($arg) === 0 ) 
			$argtext = str_replace(",", "", $argtext );
		$query = "CALL " . $stmt . "(" . $argtext . ");";
		return $this -> selectDB($data, $query, TRUE, 'array');
	}
	
	// stmt small, no params just the function name. Good to select all fields
	// or statements with no parameters
	public function stmts ($arg){
		$query = "CALL " . $arg . "();";
		return $this -> selectDB('', $query, TRUE, 'array');
	}
	
	
	private function prepareQuery($link, $query, $arg){
			
		if(isset($arg) && !empty($arg) && isset($link)){
			if(is_string($arg)){
				//$escaped = addcslashes($arg, '%_');
				$escaped = mysqli_real_escape_string($link, $arg);
                $query = str_replace($arg, $escaped, $query);
            }
			elseif(is_array($arg)){
				foreach ($arg as $key => $value) {
				    //$escaped = addcslashes($arg, '%_');
				    if(is_string($value)){
						$escaped = mysqli_real_escape_string($link, $arg[$key]);
						$query = str_replace($arg[$key], $escaped, $query);
					}elseif(is_array($value)){
						$this -> prepareQuery($link, $query, $value);
					}
				}
			}
		}
		return $query;
	}
	
	
	
	
	
	private function selectStringExpected($response){
	    
        $string = '';
		if(count($response) == 1){
			if(is_string($response[0]))$string = $response[0];
			if(is_array($response[0])){
			    foreach ($response[0] as $key => $value) {
					$string .= $value;
				}
			}
		}
        return $string;
	}
	
	
	private function selectOneRowExpected($response){
		if(count($response) == 1){
			$key = array_keys($response);
			$row = $response[$key[0]];
			return $row;
		}
	}
	
	private function packRows($response, $selector){
		
        $result = '';
		switch ($selector) {
			case 'string':
				$result = $this->selectStringExpected($response);
				break;
			
            case 'array':
                $result = $this->selectOneRowExpected($response);
                break;
			
            case 'default':
				$result = $response;
				break;
		}
        return $result;
	}
   
   private function debug($num, $error, $sql){
       $debug = "\n".'<div class="debug">';
       $debug .= '<p> Mysql Error Number : '. $num .'</p>';
       $debug .= '<p> Mysql Error : '. $error .'</p>';
	   $debug .= '<p> Mysql Error : '. $sql .'</p>';
       $debug .= '</div>';
       $debug .= "\n";
       printf("%s", $debug);
   }
}//end of class


?>