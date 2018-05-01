<?php
if (!defined('IN_DISCUZ')) {
    exit('Aecsse Denied');
}
class table_hjggk_jfjls extends discuz_table{
    public function __construct() {
        $this->_table = 'hjggk_jfjls';
        $this->_pk = 'id';
        parent::__construct();
    }

    public function fetch_all_gid($gid){
         return DB::fetch_all('select * from %t where gid=%d order by start_us asc, id asc',array($this->_table,$gid));
    }
    public function fetch_by_udid($uid,$did){
         return DB::fetch_first('select * from %t where uid=%d and daytime=%d',array($this->_table,$uid,$did));
    }
    public function update_by_id($id,$data){
         return DB::update($this->_table,$data,'id='.$id);	 
    }

    public function delete_by_id($id){
         return DB::delete($this->_table,'id='.$id);
    }
    public function delete_by_gid($gid){
         return DB::delete($this->_table,'gid='.$gid);
    }
    public function insert($data){
         return DB::insert($this->_table,$data);
    }
}
?>