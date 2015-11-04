<?php
class WebSessionProfiler_Writer_SQL implements WebSessionProfiler_Writer_Interface {
   
   protected static $_instance = null;
   private $_facility = LOG_LOCAL2;
   private $_priority = LOG_DEBUG;
   
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
		openlog('WebSessionProfiler', LOG_PID, $this->_facility);
		$msg = array(
			'wspid' => $uuid,
			'appName' => $appName,
			'timestamp' => $timestamp,
			'message' => json_encode($arguments)
		);
		$msg = json_encode($msg);
        syslog($this->_priority, $msg);
   }
}

