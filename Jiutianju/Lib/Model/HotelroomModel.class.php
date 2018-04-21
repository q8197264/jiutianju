<?php

/* 
 * 
 * 作者：
 * 官网：www.jiutianju.com
 * 邮件: 365912146@qq.com  QQ 800026911
 */

class HotelroomModel extends CommonModel{
    protected $pk   = 'room_id';
    protected $tableName =  'hotel_room';
    
    
    public function getRoomType(){
        return array(
            1 => '双床房',
            2 => '单人房',
            3 => '大床房',
            4 => '无烟房',
        );
    }
     
}