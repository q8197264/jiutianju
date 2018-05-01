<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_tom_common_district extends discuz_table
{
	public function __construct() {

		$this->_table = 'common_district';
		$this->_pk    = 'id';

		parent::__construct();
	}
    
    public function fetch_by_id($id,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE id=%d ", array($this->_table, $id));
	}
    
    public function fetch_by_name($name,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE name LIKE %s ", array($this->_table,'%'.$name.'%'));
	}
    
    public function fetch_by_level_name($name,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE name LIKE %s AND (level=1 OR level=2 OR level=3) ", array($this->_table,'%'.$name.'%'));
	}
    
    public function fetch_all_by_level($level,$field='*') {
		return DB::fetch_all("SELECT $field FROM %t WHERE level=%d ", array($this->_table, $level));
	}
    
	public function fetch_all_by_upid($upid, $order = null, $sort = 'DESC') {
		$upid = is_array($upid) ? array_map('intval', (array)$upid) : dintval($upid);
		if($upid !== null) {
			$ordersql = $order !== null && !empty($order) ? ' ORDER BY '.DB::order($order, $sort) : '';
			return DB::fetch_all('SELECT * FROM %t WHERE '.DB::field('upid', $upid)." $ordersql", array($this->_table), $this->_pk);
		}
		return array();
	}

	public function fetch_all_by_name($name) {
		if(!empty($name)) {
			return DB::fetch_all('SELECT * FROM %t WHERE '.DB::field('name', $name), array($this->_table));
		}
		return array();
	}

}

