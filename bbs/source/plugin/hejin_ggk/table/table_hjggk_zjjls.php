<?php
if (!defined('IN_DISCUZ')) {
    exit('Aecsse Denied');
}
class table_hjggk_zjjls extends discuz_table{
    public function __construct() {
        $this->_table = 'hjggk_zjjls';
        $this->_pk = 'id';
        parent::__construct();
    }

    public function fetch_all_gid($gid){
         return DB::fetch_all('select * from %t where gid=%d order by add_time desc, id desc',array($this->_table,$gid));
    }
    public function fetch_by_ogid($gid,$openid){
         return DB::fetch_all('select * from %t where gid=%d and openid=%s order by add_time desc, id desc',array($this->_table,$gid,$openid));
    }
    public function fetch_by_gjuid($gid,$jid,$openid){
         return DB::fetch_first('select * from %t where gid=%d and ggkjid=%d and openid=%s',array($this->_table,$gid,$jid,$openid));
    }
    public function fetch_by_id($id){
         return DB::fetch_first('select * from %t where id=%d',array($this->_table,$id));
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