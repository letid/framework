<?php
namespace Letid\Response;
/*
CREATE TABLE `visitor` (
	`ip` VARCHAR(50) NULL DEFAULT '1',
	`visit` BIGINT(20) NOT NULL DEFAULT '1',
	`locale` VARCHAR(5) NOT NULL DEFAULT 'en',
	`lang` VARCHAR(5) NOT NULL DEFAULT 'en',
	`modified` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`created` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	UNIQUE INDEX `unique` (`ip`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;
*/
/*
NOTE: TO reset -> ?visitorReset
*/
class Visitor extends \Letid\Id\Application
{
	public function __construct($vars)
	{
		$this->tableName = $vars;
		$this->previousHits = 0;
		// $this->previousHits = 27812301080497;
	}
	static function request()
	{
		return new self(func_get_args()[0]);
	}
	public function visit()
	{
		if ($this->tableName) {
			$this->insertOrUpdate(array('ip'=>$_SERVER['REMOTE_ADDR']),array('hit'=>array('(hit+1)')));
			$select = self::$database->select("SUM(hit) AS hits, COUNT(hit) AS total")->from($this->tableName)->execute()->toObject();
			self::content('visitor.hits')->set($select->rows->hits+$this->previousHits);
			if (isset($_GET['visitorReset']) || $select->rows->total > 999) {
				self::$database->truncate('TABLE')->from($this->tableName)->execute();
				$this->insertOrUpdate(array('ip'=>$_SERVER['REMOTE_ADDR'],'hit'=>$select->rows->hits),array('hit'=>$select->rows->hits));
				self::content('visitor.total')->set($update->result);
			} else {
				self::content('visitor.total')->set($select->rows->total);
			}
		}
	}
	private function insertOrUpdate($insert,$update)
	{
		$query = array_filter(array(
			'locale'=>@$_SESSION['sil'], 'lang'=>@$_SESSION['sol']
		));
		return self::$database->insert(
			array_merge($query,$insert)
		)->to(
			$this->tableName
		)->duplicateUpdate(
			array_merge($query,$update)
		)->execute();
	}
}