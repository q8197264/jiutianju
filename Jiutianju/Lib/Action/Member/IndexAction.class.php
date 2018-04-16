<?php
class IndexAction extends CommonAction
{
    public function index()
    {
        $Tuancode = D('Tuancode');
        import('ORG.Util.Page');
        // 导入分页类
        $map = array('user_id' => $this->uid, 'closed' => 0);
        //这里只显示 实物
        $status = (int) $this->_param('status');
        switch ($status) {
            case 1:
                break;
            case 2:
                $map['is_used'] = 0;
                break;
            case 3:
                $map['is_used'] = 1;
                break;
        }
        $this->assign('status', $status);
        $count = $Tuancode->where($map)->count();
        // 查询满足要求的总记录数
        $Page = new Page($count, 10);
        // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();
        // 分页显示输出
        $list = $Tuancode->where($map)->order(array('code_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $val) {
            $tuan_ids[$val['tuan_id']] = $val['tuan_id'];
        }

        //检查是否是配送员
//        $users = M("users");
//        $users_info = $users->field("account,mobile")->where("user_id='".$this->uid."'")->find();
        $delivery = M("delivery");
        $delivery_info = $delivery->field("id,d_status")->where("user_id='".$this->uid."'")->find();
        
        $this->assign('delivery_info', $delivery_info);

        $this->assign('tuans', D('Tuan')->itemsByIds($tuan_ids));
        $this->assign('list', $list);
        // 赋值数据集www.hatudou.com  二开开发qq  120585022
        $this->assign('page', $show);
        // 赋值分页输出
        $this->display();
    }
    public function coderefund($code_id)
    {
        $code_id = (int) $code_id;
        if ($detail = D('Tuancode')->find($code_id)) {
            if ($detail['user_id'] != $this->uid) {
                $this->baoError('非法操作');
            }
            if ($detail['status'] != 0 || $detail['is_used'] != 0) {
                $this->baoError('该抢购券不能申请退款');
            }
            if (D('Tuancode')->save(array('code_id' => $code_id, 'status' => 1))) {
                $this->baoSuccess('申请成功！等待网站客服处理！', U('index/index'));
            }
        }
        $this->baoError('操作失败');
    }
    public function delete($code_id = 0)
    {
        //根据抢购券id删除
        if (is_numeric($code_id) && ($code_id = (int) $code_id)) {
            $obj = D('Tuancode');
            if (!($detial = $obj->find($code_id))) {
                $this->baoError('该抢购券不存在');
            }
            if ($detial['user_id'] != $this->uid) {
                $this->baoError('请不要操作他人的订单');
            }
            if ($detial['status'] == 1) {
                $this->baoError('该抢购券暂时不能删除');
            }
            if ($detial['status'] == 0) {
                if ($detial['is_used'] == 0) {
                    $this->baoError('该抢购券暂时不能删除');
                }
            }
            $obj->save(array('code_id' => $code_id, 'closed' => 1));
            $this->baoSuccess('删除成功！', U('index/index'));
        } else {
            $this->baoError('请选择要删除的抢购券');
        }
    }


    public function sqdelivery()
    {        
        //检查是否是配送员
        $delivery = M("delivery");
        $delivery_info = $delivery->field("id,d_status")->where("user_id='".$this->uid."'")->find();
        if(!empty($delivery_info['id'])){
            if($delivery_info['d_status']==1){
                $this->baoError('您的账户已经绑定配送员了！');
            }
            else{
                $this->baoError('您的账户正式审核中，请耐心等待！');
            }
            exit;
        }
        $users = M("users");
        $users_info = $users->field("account,mobile")->where("user_id='".$this->uid."'")->find();
        $this->assign('users_info', $users_info);
        // 赋值分页输出
        $this->display();
    }

    public function dosq(){
        $username = I('username','','trim,htmlspecialchars');
        $password = I('password');
        $rpw = I('rpw');
        $name = I('name','','trim,htmlspecialchars');
        $mobile = I('mobile','','trim');
        if(!$username){
            $this->baoError('帐号没有填写！');
        }
        if(!$password || strlen($password)<6){
            $this->baoError('密码错误或小于6位！');
        }
        if(!$rpw || strlen($rpw)<6){
            $this->baoError('确认密码错误或小于6位！');
        }
        
        if($password != $rpw){
            $this->baoError('两次密码不一致！');
        }
        
        if(!$name){
            $this->baoError('姓名没有填写！');
        }
        
        if(!$mobile || strlen($mobile) != 11){
            $this->baoError('手机号填写错误！');
        }
        
        $dv = D('Delivery');
        
        $fu = $dv -> where('username ="'.$username.'"') -> find();
        if($fu){
            $this->baoError('重复的帐号！');
        }
        
        $fm = $dv -> where('mobile ='.$mobile) -> find();
        if($fm){
            $this->baoError('重复的手机号！');
        }
        
        $result = array(
            'username'=>$username,
            'password'=>md5($password),
            'name'=>$name,
            'mobile'=>$mobile,
            'user_id'=>$this->uid,
            'd_status'=>0,
            'add_time'=>time()
        );
        
        
        $r = $dv -> add($result);
        if($r){
            $this->baoSuccess('提交申请成功，等待审核', U('index/index'));
        }else{
            $this->baoError('添加失败！');
        }
    }
}