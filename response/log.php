<?php
namespace letId\response;
/**
* log::request($Id)->response();
* NOTE: TO reset -> ?visitsReset
*/
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
class log
{
	public $table,$column=array();
	public function __construct($table=null)
	{
		if ($table) {
			$this->table = $table;
		}
	}
	static function requestTable($table=null)
	{
		return new self($table);
	}
	public function requestVisits()
	{
		if ($this->table) {
			$this->requestVisitsUpdate(array('ip'=>$_SERVER['REMOTE_ADDR']),array('hit'=>array('(hit+1)')));
			$select = avail::$database->select("SUM(hit) AS hits, COUNT(hit) AS total")->from($this->table)->execute()->toObject();
			avail::content('visitor.hits')->set(number_format($select->rows->hits+avail::configuration('visitsPrevious')->get(0)));
			if (isset($_GET['visitsReset']) || $select->rows->total >= avail::configuration('visitsReset')->get(999) ) {
				avail::$database->truncate('TABLE')->from($this->table)->execute();
				$this->requestVisitsUpdate(array('ip'=>$_SERVER['REMOTE_ADDR'],'hit'=>$select->rows->hits),array('hit'=>$select->rows->hits));
				avail::content('visitor.total')->set($update->result);
			} else {
				avail::content('visitor.total')->set($select->rows->total);
			}
		}
	}
	private function requestVisitsUpdate($insert,$update)
	{
		$column = array_filter(array(
			'locale'=>avail::session('sil')->has(), 'lang'=>avail::session('sol')->has()
		));
		// if ($this->column) {
		// 	$column = array_merge($this->column,$column);
		// }
		return avail::$database->insert(
			array_merge($column,$insert)
		)->to(
			$this->table
		)->duplicateUpdate(
			array_merge($column,$update)
		)->execute();
	}
}