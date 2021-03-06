<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
} 

class table_tom_kanjia_user extends discuz_table{
	public function __construct() {
        parent::__construct();
		$this->_table = 'tom_kanjia_user';
		$this->_pk    = 'id';
	}

    public function fetch_by_id($id,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE id=%d", array($this->_table, $id));
	}
    
    public function insert_id() {
		return DB::insert_id();
	}
    
    public function fetch_by_kid_tel($kid,$tel) {
		return DB::fetch_first("SELECT * FROM %t WHERE kj_id=%d AND tel=%s ", array($this->_table, $kid,$tel));
	}
    
    public function fetch_by_kid_openid($kid,$openid) {
		return DB::fetch_first("SELECT * FROM %t WHERE kj_id=%d AND openid=%s ", array($this->_table, $kid,$openid));
	}
    
    public function fetch_all_list($condition,$orders = '',$start = 0,$limit = 10) {
		$data = DB::fetch_all("SELECT * FROM %t WHERE 1 %i $orders LIMIT $start,$limit",array($this->_table,$condition));
		return $data;
	}
    
    public function fetch_all_count($condition) {
        $return = DB::fetch_first("SELECT count(*) AS num FROM ".DB::table($this->_table)." WHERE 1 $condition ");
		return $return['num'];
	}
	
	public function delete_by_id($id) {
		return DB::query("DELETE FROM %t WHERE id=%d", array($this->_table, $id));
	}
    
    public function delete_by_kj_id($kj_id) {
		return DB::query("DELETE FROM %t WHERE kj_id=%d", array($this->_table, $kj_id));
	}

}


?>
