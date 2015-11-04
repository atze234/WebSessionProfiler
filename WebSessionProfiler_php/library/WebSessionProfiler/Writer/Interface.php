<?php
interface WebSessionProfiler_Writer_Interface {
	public static function getInstance();
	public function write($uuid, $appName, $timestamp, $arguments);
}
