<?php 
if(!defined('DARDSTATUS') ) exit();
/**
 * 
 */
class dbConect {
	
    const S = 'string';
    const A = 'array';
    const D = 'default';
        
	
	public function insertDB($arg, $config, $query)
	{
		$link = mysqli_connect($config->host, $config->user, $config->password, $config->db);
		
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
			}if(mysqli_error($link) && $config->debugMYSQL === TRUE){
                $num = mysqli_errno($link);
                $error = mysqli_error($link);
                $this->debug($num, $error);
            }
		}
		$returnedid = mysqli_insert_id($link);
		mysqli_close($link);
		return $returnedid;
		
	}
	
	
	public function selectDB($arg, $config, $query, $assoc, $return_format){
			
		$link = mysqli_connect($config->host, $config->user, $config->password, $config->db);
		
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
                    mysqli_free_result($result);
				    if ( mysqli_more_results($link)) {
						$i++;
						$j =true;
					}else{
						$j = false;
					}
					
					
				} while($j && mysqli_next_result($link));
			}elseif(mysqli_error($link) && $config->debugMYSQL === TRUE){
			    $num = mysqli_errno($link);
                $error = mysqli_error($link);
			    $this->debug($num, $error);
            }
		
		
		mysqli_close($link);
		return $this->packRows($response, $return_format);
		
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
					$escaped = mysqli_real_escape_string($link, $arg[$key]);
					$query = str_replace($arg[$key], $escaped, $query);
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
			$row = $response[0];
			return $row;
		}
	}
	
	private function packRows($response, $selector){
		
        $result;
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
   
   private function debug($num, $error){
       $debug = "\n".'<div class="debug">';
       $debug .= '<div> Mysql Error Number : '. $num .'</div>';
       $debug .= '<div> Mysql Error : '. $error .'</div>';
       $debug .= '</div>';
       $debug .= "\n";
       printf("%s", $debug);
   }
}//end of class


?>