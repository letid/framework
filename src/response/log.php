<?php
namespace letId\response
{
	/**
	* log::request('tableName')->response();
	* NOTE: TO reset -> ?visitsReset
	*/
	/*
	CREATE TABLE `visits` (
		`ip` VARCHAR(50) NULL DEFAULT '1',
		`view` BIGINT(20) NOT NULL DEFAULT '1',
		`locale` VARCHAR(5) NOT NULL DEFAULT 'en',
		`lang` VARCHAR(5) NOT NULL DEFAULT 'en',
		`modified` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		`created` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
		UNIQUE INDEX `unique` (`ip`)
	)
	COLLATE='utf8_general_ci'
	ENGINE=InnoDB
	ROW_FORMAT=COMPACT;
	*/
class log
	{
		protected $table;
		public function __construct($table=null)
		{
			if ($table) $this->table = $table;
			$this->rowSelector=array('ip'=>$_SERVER['REMOTE_ADDR']);
		}
		public function requestVisits()
		{
			if ($this->table) {
				if ($this->requestVisitsUpdate($this->rowSelector,array('view'=>array('(view+1)')))->result) {
					$select = avail::$database->select('created,SUM(view) AS viewSum, COUNT(view) AS viewTotal')->from($this->table)->execute()->fetchObject();
					// print_r($select);
					// $user = avail::$database->select('locale, lang, view, modified, created')->from($this->table)->where($this->rowSelector)->execute()->fetchObject();
					// avail::content('visitor.view')->set($user->rows->view);
					// avail::configuration('locale')->set($user->rows->locale);
					// avail::configuration('lang')->set($user->rows->lang);
					// avail::configuration('modified')->set($user->rows->modified);
					// avail::configuration('created')->set($user->rows->created);

					avail::content('visits.sum')->set(number_format($select->rows->viewSum+avail::configuration('visitsPrevious')->get(0)));
					avail::content('visits.created')->set($select->rows->created);
					// print_r($select->rows);

					$visitsReset = avail::configuration('visitsReset');

					if (($visitsReset->has() && isset($_GET[$visitsReset->get()])) || $select->rows->viewTotal > avail::configuration('visitsLimit')->get(999)) {
						// NOTE: visitsReset=1
						avail::$database->truncate('TABLE')->from($this->table)->execute();
						// $update = $this->requestVisitsUpdate($this->rowSelector,array('hit'=>$select->rows->hits));
						$update = $this->requestVisitsUpdate(array_merge($this->rowSelector,array('view'=>$select->rows->viewSum)));
						avail::content('visits.total')->set($update->result);
					} else {
						avail::content('visits.total')->set($select->rows->viewTotal);
					}
				}
			}
		}
		private function requestVisitsUpdate($insert,$update=array())
		{
			$column = array_filter(array(
				'locale'=>avail::session('locale')->version()->has(), 'lang'=>avail::session('lang')->version()->has()
			));
			return avail::$database->insert(
				array_merge($column,$insert)
			)->to(
				$this->table
			)->duplicateUpdate(
				array_merge($column,$update)
			)->execute();
		}
	}
}