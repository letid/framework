<?php
namespace letId\http;
abstract class response
{
	protected $header = array(
		'css'=> array(
			'Content-Type:text/css'
		),
		'js'=> array(
			'Content-Type:application/javascript'
		),
		'png'=> array(
			'Content-Type:image/png'
		),
		'ico'=> array(
			'Content-Type:image/ico'
		),
		'text'=> array(
			'Content-Type:text/plain'
		),
		'json'=> array(
			'Content-Type:application/json'
		),
		'xml'=> array(
			'Content-Type:application/xml'
		),
		'audio'=> array(
			'Content-Type:audio/mpeg'
		)
	);

	public function __construct($Id)
    {
		if (isset($this->header[$Id])) {
			foreach ($this->header[$Id] as $h) header($h);
		}
    }
	public function resource($file)
	{
		$this->resourceCache($file);
		if (file_exists($file)) {
			readfile($file);
			// return file_get_contents($file);
		} else {
			header('HTTP/1.1 404 Not Found');
		}
	}
	public function resourceCache($file)
	{
		// Second to cache
		$STC = 86400;
		// Greenwich Mean Time
		$GMT = gmdate("D, d M Y H:i:s", time() + $STC) . " GMT";
		// Last Modified
		$lastModified = filemtime($file);
		// Get a unique hash of this file (etag)
		$etagFile = md5_file($file);
		// Get the HTTP_IF_MODIFIED_SINCE header if set
		$ifModifiedSince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
		// Get the HTTP_IF_NONE_MATCH header if set (etag: unique file hash)
		$etagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);
		// Set etag-header
		header("Etag: $etagFile");
		// Set last-modified header
		header("Last-Modified: ".gmdate("D, d M Y H:i:s", $lastModified)." GMT");
		header("Expires: $GMT");
		header("Pragma: cache");
		// Make sure caching is turned on
		header("Cache-Control: public, max-age=$STC");
		// Check if page has changed. If not, send 304 and exit
		if(@strtotime($ifModifiedSince) == $lastModified || $etagHeader == $etagFile){
		   header("HTTP/1.1 304 Not Modified");
		   exit;
		}
	}
	public function responseTemplate($Id)
	{
		return avail::template(avail::$contextId);
	}
	public function audio()
	{
		return avail::$contextId;
	}
	public function json()
	{
		return json_encode(avail::$contextId);
	}
	public function text($Id)
	{
		if (is_array($Id)) {
            return print_r($Id, true);
		} else {
			return $Id;
		}
	}
}
