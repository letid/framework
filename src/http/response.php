<?php
namespace letId\http
{
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
			if (is_array($Id)) {
				foreach ($Id as $h) header($h);
			} elseif (is_scalar($Id) && isset($this->header[$Id])) {
				foreach ($this->header[$Id] as $h) header($h);
			}
	  }
		public function resource($file)
		{
			if (avail::$config['resourceCache'])$this->resourceCache($file);
			if (file_exists($file)) {
				readfile($file);
				// return file_get_contents($file);
			} else {
				header('HTTP/1.1 404 Not Found');
			}
		}
		public function resourceCache($file)
		{
			ob_start('ob_gzhandler');
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
			header("Cache-Control: max-age=$STC");
			// Check if page has changed. If not, send 304 and exit
			if(@strtotime($ifModifiedSince) == $lastModified || $etagHeader == $etagFile){
				header("HTTP/1.1 304 Not Modified");
				exit;
			}
		}
		public function responseTemplate($Id)
		{
			header("letId: 1.0.6");
			return avail::template(avail::$responseContext);
		}
		public function audio()
		{
			return avail::$responseContext;
		}
		public function none()
		{
			return avail::$responseContext;
		}
		public function json()
		{
			return json_encode(avail::$responseContext);
		}
		public function text($Id)
		{
			return is_array($Id)?print_r($Id, true):$Id;
		}
	}
}