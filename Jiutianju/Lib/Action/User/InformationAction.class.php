<?php
class InformationAction extends CommonAction{
    public function index(){
        $u = D('Users');
        $ud = D('UserAddr');
        $bc = D('Connect');
        $map = array('user_id' => $this->uid);
        $res = $u->where($map)->find();
        $addr_count = $ud->where($map)->count();
        $rbc = $bc->where('uid =' . $this->uid)->select();
        $bind = array();
        foreach ($rbc as $val) {
            $bind[$val['type']] = $val;
        }
        $this->assign('res', $res);
        $this->assign('addr_count', $addr_count);
        $this->assign('bind', $bind);
        $this->display();
        // 输出模板
    }
	
    public function upload_face(){
        if (!$this->uid) {
            $this->ajaxReturn(array('status' => 'error', 'message' => '您没有登录或登录超时！'));
        } else {
            $avatar = I('avatar', '', 'trim,htmlspecialchars');
            if (!$avatar) {
                $this->ajaxReturn(array('status' => 'error', 'message' => '没有上传头像！'));
            } else {
                $u = D('Users');
                $up = $u->where('user_id =' . $this->uid)->setField('face', $avatar);
                if ($up) {
                    $this->ajaxReturn(array('status' => 'success', 'message' => '修改成功！'));
                } else {
                    $this->ajaxReturn(array('status' => 'error', 'message' => '修改失败！'));
                }
            }
        }
    }
    public function worker($worker_id = 0){
        if (empty($worker_id)) {
            $this->error('访问错误！');
        }
        $worker = D('Shopworker')->find($worker_id);
        if (empty($worker)) {
            $this->error('访问错误！');
        }
        if ($worker['user_id'] != $this->uid) {
            $this->error('没有权限访问错误！');
        }
        if ($worker['status'] == 1) {
            $this->error('您已经同意过这条请求！');
        }
        $shop = D('Shop')->find($worker['shop_id']);
        $this->assign('worker', $worker);
        $this->assign('shop', $shop);
        $this->display();
        // 输出模板
    }
    public function worker_agree($worker_id = 0) {
        if (empty($worker_id)) {
            $this->error('访问错误！');
        }
        $worker = D('Shopworker')->find($worker_id);
        if (empty($worker)) {
            $this->error('访问错误！');
        }
		if ($worker['status'] == 1) {
            $this->error('您已经确认过了');
        }
        if ($worker['user_id'] != $this->uid) {
            $this->error('没有权限访问错误！');
        }
        D('Shopworker')->save(array('status' => 1, 'worker_id' => $worker['worker_id']));
        $this->success('恭喜您成为了该商家的员工！', U('worker/index/index'));
    }
    public function worker_refuse($worker_id = 0){
        if (empty($worker_id)) {
            $this->error('访问错误！');
        }
        $worker = D('Shopworker')->find($worker_id);
        if (empty($worker)) {
            $this->error('访问错误！');
        }
		if ($worker['status'] == 1) {
            $this->error('您不能执行此操作');
        }
        if ($worker['user_id'] != $this->uid) {
            $this->error('没有权限访问错误！');
        }
        D('Shopworker')->where(array('worker_id' => $worker['worker_id']))->delete();
        $this->success('您残忍地拒绝了该商家的请求！', U('user/index/index'));
    }
	public function buy(){
        $Usergrade = D('User_rank');
        import('ORG.Util.Page');
        $map = array('closed'=>0);
        $count = $Usergrade->where($map)->count();
        $Page = new Page($count, 15);
        $show = $Page->show();
        $list = $Usergrade->where($map)->order(array('rank_id' => 'asc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	public function pay($rank_id){
		 $obj = D('User_rank');
		$user_rank = $obj->find($rank_id);//准备购买的商家等级
		
		$money=$user_rank['money']*100;
        $u = D('Users');
        $map = array('user_id' => $this->uid);
	   $users = $u->where($map)->find();
	   $old_shop_grade = $obj->find($users['rank_id']);//当前商家的等级
       $kaiguan= $this->_CONFIG['rank']['rank_kaiguan'] ;
	   if($kaiguan==0){
		   $this->error ('当前系统已禁止购买会员等级，请联系管理员', U('user/index/index'));
	   }
       if($users['rank_id'] == $rank_id){
			$this->error ('购买的等级跟您的会员等级一致，无法购买', U('user/index/index'));
	   }
	   if($old_shop_grade['orderby'] >= $user_rank['orderby']){
			$this->error('您不能降级，只能购买高权限的等级', U('user/index/index'));
	   }
	   if ($this->member['money'] < $money){
			$this->error('您的会员余额不足，无法购买，请先到会员中心充值后购买', U('user/money/index'));
		}
	  
		
		if (D('Users')->addMoney($users['user_id'], -$money, '提升用户等级【' . $user_rank['rank_name'] . '】扣费成功')) {
			D('Users')->save(array('user_id' => $users['user_id'], 'rank_id' => $rank_id));
			$this->success('购买成功！', U('user/index/index'));
			} 
				$this->error ('扣费失败请重试', U('user/index/index'));
			
    }
}