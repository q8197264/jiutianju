<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
} 

class table_tom_love_rec extends discuz_table{
	public function __construct() {
        parent::__construct();
		$this->_table = 'tom_love_rec';
		$this->_pk    = 'id';
	}

    public function fetch_by_id($id,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE id=%d ", array($this->_table, $id));
	}
    
    public function fetch_by_uid($uid,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE user_id=%d ORDER BY id DESC ", array($this->_table, $uid));
	}
    
    public function fetch_all_list($condition,$orders = '',$start = 0,$limit = 10) {
		$data = DB::fetch_all("SELECT * FROM %t WHERE 1 %i $orders LIMIT $start,$limit",array($this->_table,$condition));
		return $data;
	}
    
    public function fetch_all_count($condition) {
        $return = DB::fetch_first("SELECT count(*) AS num FROM ".DB::table($this->_table)." WHERE 1 $condition ");
		return $return['num'];
	}
    
    public function update_rec_status($status,$uid) {
		return DB::query("UPDATE %t SET rec_status=%d WHERE user_id=%d", array($this->_table, $status,$uid));
	}
    
    public function delete_by_uid($uid) {
		return DB::query("DELETE FROM %t WHERE user_id=%d", array($this->_table, $uid));
	}

}

