<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_tom_common_member_profile extends discuz_table
{
	public function __construct() {

		$this->_table = 'common_member_profile';
		$this->_pk    = 'uid';

		parent::__construct();
	}
    
    public function fetch_by_uid($uid,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE uid=%d ", array($this->_table, $uid));
	}

}
