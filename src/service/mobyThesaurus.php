<?php
/**
 * @author Brent Rossen
 * Class for accessing the moby thesaurus and parts of speech.
 * This class is capable of retrieving a list of synonyms for a word, or parts of speech for a word.
 */
/*
require_once 'MobyThesaurus.php';
echo "<h1>Demonstrating MobyThesaurus Class</h1>";

echo "<h3>Getting Synonyms for 'medications' (MobyThesaurus::GetSynonyms)</h3>";
$start_time = microtime(true);
$synonyms = MobyThesaurus::GetSynonyms("medications");
var_export($synonyms);
echo "<br><br>Processing Time: " . (microtime(true) - $start_time) . "<br>";

echo "<br><h3>Getting Part of Speech for 'feel' (MobyThesaurus::GetPartsOfSpeech)</h3>";
$start_time = microtime(true);
$pos = MobyThesaurus::GetPartsOfSpeech("feel");
var_export($pos);
echo "<br><br>Processing Time: " . (microtime(true) - $start_time) . "<br>";
*/
namespace letId\service
{
	class mobyThesaurus
	{
		// private $mobyThesaurusFile = '/moby.thesaurus/partofspeech.txt';
		// private $mobyThesaurusFile = '/moby.thesaurus/thesaurus.txt'
		private $FilePath;
		public function __construct($Path)
		{
			//$thesaurus_array = file ( dirname ( __FILE__ ) . "/moby.thesaurus/thesaurus.txt");
			// $this->FilePath = file($Path);
			if (is_array($Path)){
				$this->filePath = implode('/',$Path);
			} else {
				$this->filePath = $Path;
			}
		}
		/**
		 * Gets the word in the thesaurus that is most similar to the passed word. Uses extension php_stem for stemming if it is available (highly recommended).
		 *
		 * @param string $word
		 * @return array The array of synonyms, array position 0 is the matched word 
		 */
		public function synonyms($word) {
			//get the thesaurus
			$thesaurus_array = file($this->filePath);
			//get the stemmed word, requires the PECL extension php_stem
			if (function_exists ("stem")) {
				$stemmed_word = stem ($word);
			} else {
				//can't get the stemmed word
				$stemmed_word = $word;
			}
			//the array of potential entries
			$potential_entries = array ();
			
			//loop through the thesaurus entries
			foreach ($thesaurus_array as $entry) {
				if (self::StartsWith ($stemmed_word, $entry)) {
					$entry_arr = explode(",",$entry);
					if ($entry_arr [0] == $word) {
						return $entry_arr;
					} else {
						array_push ($potential_entries, $entry_arr);
					}
				}
			}
			
			//anything above 10 is way too far away
			$lowest_distance = 10;
			foreach ( $potential_entries as $entry ) {
				$distance = levenshtein ($entry[0], $word);
				//keep only the word that is closest to the original word
				if ($distance < $lowest_distance) {
					$lowest_distance = $distance;
					$best_entry = $entry;
				}
			}
			
			if (isset ($best_entry)) {
				return $best_entry;
			} else {
				return array ();
			}
			
		}
		
		/**
		 * Gets the PartsOfSpeech for the entries that start with the given word.
		 *
		 * @param string $word
		 * @return array of parts of speech
		 */
		public function partsOfSpeech($word) {
			//get the thesaurus
			// $pos_array = file ( dirname ( __FILE__ ) . "/moby.thesaurus/partofspeech.txt" );
			$pos_array = file($this->filePath);
			
			$poss = array ();
			foreach ( $pos_array as $entry ) {
				if (self::StartsWith( $word, $entry )) {
					//split the word from it's parts of speech
					// $line_arr = explode( "[\\]", $entry );
					$line_arr = explode( "\\", $entry );
					$poss[$line_arr[0]] = array();
					$line_arr [1] = trim ( $line_arr [1] );
					//go through each part of speech item
					for($i = 0; $i < strlen ( $line_arr [1] ); $i ++) {
						$symbol = trim ( $line_arr [1] [$i] );
						array_push ( $poss [$line_arr[0]], $symbol );
					}
				}
			}
			
			return $poss;
		}
		
		/**
		 * Discovers if haystack starts with needle
		 *
		 * @param string $needle
		 * @param string $haystack
		 * @return boolean
		 */
		private function StartsWith($needle, $haystack) {
			return (substr($haystack,0,strlen($needle)) == $needle);
		}
	}
}
?>