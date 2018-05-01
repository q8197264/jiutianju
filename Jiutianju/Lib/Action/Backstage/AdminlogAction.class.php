<?php
class AdminlogAction extends CommonAction{

    public function index(){
        $Admin = D('Admin_log');
        import('ORG.Util.Page');
        
        $map = array();
        
        $count = $Admin->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = $Admin->where($map)->select();
       foreach ($list as $k => $val) {
			
           
            $val['last_ip_area'] = $this->ipToArea($val['last_ip']);
            
			$list[$k] = $val;
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
  

}

