<?php

/* 
 * 
 * 作者：
 * 官网：www.jiutianju.com
 * 邮件: 365912146@qq.com  QQ 800026911
 */

class TribecollectModel extends CommonModel{
    protected $pk   = 'tribe_id,user_id';
    protected $tableName =  'tribe_collect';
    
    
    public function check($tribe_id,$user_id){
        $data = $this->find(array('where'=>array('tribe_id'=>(int)$tribe_id,'user_id'=>(int)$user_id)));
        return $this->_format($data);
    }
    
}