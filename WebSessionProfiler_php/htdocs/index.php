<?php
/*
** This is just a quick try Script for the Unified Logging of WebSessionProfiler for seeing the Concept working
*/

require_once '../library/WebSessionProfiler/Writer/Interface.php';
require_once '../library/WebSessionProfiler/Profiler.php';
require_once '../library/WebSessionProfiler/Writer/SQL.php';
require_once '../library/WebSessionProfiler/Writer/Log.php';
require_once '../library/WebSessionProfiler/Writer/OtherInterface.php';
require_once '../library/WebSessionProfiler/Writer/KV.php';


//@todo better in prepend

//Enable Websession Profiler in htaccess
defined('_WEBSESSION_PROFILER_ENABLE') || define('_WEBSESSION_PROFILER_ENABLE',
        (getenv('_WEBSESSION_PROFILER_ENABLE') !== false ? getenv('_WEBSESSION_PROFILER_ENABLE') : false));

if (_WEBSESSION_PROFILER_ENABLE) {
	//better set application Name via SetEnv in htaccess
	$_please_overwrite_applicationname = 'websession_profiler';
	defined('_WEBSESSION_PROFILER_ID') || define('_WEBSESSION_PROFILER_ID',
		(getenv('_WEBSESSION_PROFILER_ID') !== false ? getenv('_WEBSESSION_PROFILER_ID') : uniqid('',true)));
	defined('_WEBSESSION_PROFILER_NAME') || define('_WEBSESSION_PROFILER_NAME',
		(getenv('_WEBSESSION_PROFILER_NAME') !== false ? getenv('_WEBSESSION_PROFILER_NAME') : $_please_overwrite_applicationname));
	
	//Session handling should be in a shutdown function, headers maybe too
	session_start();
	if (isset( $_SESSION['WebSessionProfilerInc'])) {
		$_SESSION['WebSessionProfilerInc']++;
	} else {
		$_SESSION['WebSessionProfilerInc']=0;
	}
	define(_WEBSESSION_PROFILER_SESSIONID, session_id());

	header('X-WebSessionProfilerId: '. _WEBSESSION_PROFILER_ID);
	header('X-WebSessionProfilerName: '. _WEBSESSION_PROFILER_NAME);
	header('X-WebSessionProfilerSessionId: '. _WEBSESSION_PROFILER_SESSIONID);
	header('X-WebSessionProfilerSessionInc: '.$_SESSION['WebSessionProfilerInc']);

}

//Session maybe Config Constant based


//Creates a Profiling/Logging Instance

$profiling = WebSessionProfiler_Profiler::getInstance($appname = _WEBSESSION_PROFILER_NAME, $uuid = _WEBSESSION_PROFILER_ID);


//do some messages
//logmethods via __call, not the best way, right?
$profiling->log($message = 'Hello Error Logging World', $priority = LOG_CRIT);

//This should be done at a central DB Class
$myuuid = $profiling->getWebSessionProfilerId();
$profiling->sQL($query = 'SELECT * FROM Examples /*' . $myuuid . '*/)', $rows_affected = 12, $user = 'test', $host = 'mydb');

//do some Interface Logging
$profiling->otherInterface($query = 'Memcache Key: abc /*' . $myuuid . '*/', $rows_affected = 13, $interfaceHost = 'mymemcache', $interfaceReturn = 'OK');

//do some Performance Tracking
$profiling->kV($key = 'memusage', $value = memory_get_usage(false));


echo "Hello WebSessionProfiler World!";



 