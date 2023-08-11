<?php

/**
 * 
 * 
 * 
 * 
 */
class DardConfig {
    
	// data base config
	protected $cf_db_name = "dard";
	protected $cf_db_password = "nosecrets";
	protected $cf_db_host = "localhost";
	protected $cf_db_user ="darduser";
	// end data base config
	
	// general core config
	protected $cf_session_lifetime = 1440;
	protected $cf_session_path = "/";
	protected $cf_session_utd_life = 31536000;
	public $cf_language = "en";
	public $cf_password_cost = 12;
	public $cf_login_redirect;
	// end general core config
	
	// debug and stats config
	protected $cf_debug_MYSQL = TRUE;
	protected $cf_dard_statisctics = FALSE;
	// end debug and stats config
}
?>