<?php
if (!defined('IN_DISCUZ')) {
    exit('Aecsse Denied');
}
class table_hjggk_ggks extends discuz_table{
    public function __construct() {
        $this->_table = 'hjggk_ggks';
        $this->_pk = 'id';
        parent::__construct();
    }

    public function fetch_all(){
         return DB::fetch_all('select * from %t order by id desc',array($this->_table));
    }
    public function fetch_limit($startnum,$count){
         return DB::fetch_all('select * from %t order by id desc limit %d,%d',array($this->_table,$startnum,$count));
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
    public function insert($data){
         return DB::insert($this->_table,$data);
    }
}
?>