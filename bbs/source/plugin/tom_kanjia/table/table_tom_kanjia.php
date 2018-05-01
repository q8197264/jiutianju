<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
} 

class table_tom_kanjia extends discuz_table{
	public function __construct() {
        parent::__construct();
		$this->_table = 'tom_kanjia';
		$this->_pk    = 'id';
	}

    public function fetch_by_id($id,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE id=%d", array($this->_table, $id));
	}
	
    public function fetch_all_list($condition,$orders = '',$start = 0,$limit = 10) {
		$data = DB::fetch_all("SELECT * FROM %t WHERE 1 %i $orders LIMIT $start,$limit",array($this->_table,$condition));
		return $data;
	}
    
    public function fetch_all_sun_user_count($condition) {
        $return = DB::fetch_first("SELECT SUM(user_count) AS user_count_num FROM ".DB::table($this->_table)." WHERE 1 $condition ");
		return $return['user_count_num'];
	}
    
    public function fetch_all_sun_clicks($condition) {
        $return1 = DB::fetch_first("SELECT SUM(clicks) AS clicks_num FROM ".DB::table($this->_table)." WHERE 1 $condition ");
        $return2 = DB::fetch_first("SELECT SUM(virtual_clicks) AS virtual_clicks_num FROM ".DB::table($this->_table)." WHERE 1 $condition ");
		return $return1['clicks_num']+$return2['virtual_clicks_num'];
	}
    
    public function fetch_all_count($condition) {
        $return = DB::fetch_first("SELECT count(*) AS num FROM ".DB::table($this->_table)." WHERE 1 $condition ");
		return $return['num'];
	}
	
	public function delete_by_id($id) {
		return DB::query("DELETE FROM %t WHERE id=%d", array($this->_table, $id));
	}

}


?>
