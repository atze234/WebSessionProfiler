<?php
class WebSessionProfiler_Profiler {
   protected static $_instance = null;
   protected $_uuid = null;
   protected $_appName = null;
   
   public static function getInstance($appName = false, $uuid = false)
   {
       if (null === self::$_instance)
       {
           self::$_instance = new self($appName, $uuid);
       }
       return self::$_instance;
   }
 
   /**
    * clone forbidden
    *
    *
    */
   protected function __clone() {}
   
   /**
    * constructor
    *
    *
    */
   protected function __construct($appName = false, $uuid = false) {
	   if ($appName === false) {
		   throw new Exception('no Appname specified for WebSessionProfiling');
	   } else {
		   $this->_appName = strtolower($appName);
	   }
	   //maybe add a pluggable uuid generator later
	   if ($uuid === false) {
		   $this->_uuid = $this->_createUUId();
	   } else {
           $this->_uuid = $uuid;
	   }
	   
   }
   //not sure thats a nice way...
   public function __call($name, $messages) 
   {
	   //@Todo: some non-blocking-whitepage Exception Handling ;)
		$class = 'WebSessionProfiler_Writer_'.ucfirst($name);

		$writer = call_user_func(array($class, 'getInstance'));
		$writer->write($this->_uuid, $this->_appName, date('Y-m-d H:i:s'), $messages);
   }
   public function getWebSessionProfilerId() {
	   return $this->_uuid;
   }
   protected function _createUUId() {
	   return uniqid('',true);
   }
   public function getWebSessionProfilerName() {
	   return $this->_appName;
   }
}

