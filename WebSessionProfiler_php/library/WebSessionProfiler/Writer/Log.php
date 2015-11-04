<?php
class WebSessionProfiler_Writer_Log implements WebSessionProfiler_Writer_Interface {
   
   protected static $_instance = null;
   private $_facility = LOG_LOCAL0;
   
   public static function getInstance()
   {
       if (null === self::$_instance)
       {
           self::$_instance = new self();
       }
       return self::$_instance;
   }
 
   /**
    * clone
    *
    * Kopieren der Instanz von aussen verbieten
    */
   protected function __clone() {}
   
   /**
    * constructor
    *
    * externe Instanzierung verbieten
    */
   protected function __construct() {
   }
   
   public function write($uuid, $appName, $timestamp, $arguments) {
		openlog(strtolower($appName), LOG_PID, $this->_facility);
		if (isset($arguments[1])) {
			$prio = (int)$arguments[1];
			unset($arguments[1]);
		} else {
			$prio = LOG_DEBUG;
		}
		$msg = array(
			'wspid' => $uuid,
			'timestamp' => $timestamp,
			'message' => json_encode($arguments[0])
		);
		$msg = json_encode($msg);
        syslog($prio, $msg);
   }
}
