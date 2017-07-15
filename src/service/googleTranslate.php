<?php
/*
$o= new googleTranslate(api_key);
$o->request(word,target,source);
*/
namespace letId\service
{
	class googleTranslate
	{
		/*
		https://www.googleapis.com/language/translate/v2?
		https://translate.google.com/translate_a/single?
		*/
		private $apiUrl = 'https://translation.googleapis.com/language/translate/v2?';
		private $apiKey;
		public function __construct($key)
		{
			$this->apiKey = $key;
		}
		public function request($q,$target,$source=NULL)
		{
			$apiParam = http_build_query(
				array_filter(
					array(
						'key'    => $this->apiKey,
						'source' => $source,
						'target' => $target,
						'q'      => rawurlencode($q)
					)
				)
			);
			/*
			$apiParam = http_build_query(
				array_filter(
					array(
						'client' => 't',
						'sl' => 'en',
						'tl' => 'no',
						'hl' => 'en',
						'dt' => 'at',
						'dt' => 'bd',
						'dt' => 'ex',
						'dt' => 'ld',
						'dt' => 'md',
						'dt' => 'qca',
						'dt' => 'rw',
						'dt' => 'rm',
						'dt' => 'ss',
						'dt' => 't',
						'ie' => 'UTF-8',
						'oe' => 'UTF-8',
						'swap' => '0',
						'source' => 'btn',
						'ssel' => '5',
						'tsel' => '5',
						'kc' => '0',
						'tk' => '955101.576168',
						'q' => 'hate'
					)
				)
			);
			*/
			return json_decode(file_get_contents($this->apiUrl.$apiParam),true);
		}
	}
}
?>