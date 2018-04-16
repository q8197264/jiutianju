<?php
class PaoAction extends CommonAction {
    public function index() {
        $pao_records = D('PaoRecords');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('fabu_uid' => $this->uid,'is_close'=>0);
        $count = $pao_records->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 10); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $pao_records->where($map)->order(array('record_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $categorylist = array();
        if(!empty($list)){
            $pao_category = D("PaoCategory");
            $pcategorylist = $pao_category->field("category_id,category_name")->where("category_status=1")->select();
            if(!empty($pcategorylist)){
                foreach($pcategorylist as $k => $v){
                    $categorylist[$v['category_id']] = $v['category_name'];
                }
            }
        }
        //var_dump( D('Business')->itemsByIds($business_ids));die();
        $this->assign('list', $list); // 赋值数据集www.hatudou.com  二开开发qq  120585022
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('categorylist', $categorylist);
        $this->display(); // 输出模板
    }

    public function records() {
        $pao_records = D('PaoRecords');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('jie_uid' => $this->uid,'is_close'=>0);
        $count = $pao_records->where($map)->count(); // 查询满足要求的总记录数 
        $Page = new Page($count, 10); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $pao_records->where($map)->order(array('record_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $categorylist = array();
        if(!empty($list)){
            $pao_category = D("PaoCategory");
            $pcategorylist = $pao_category->field("category_id,category_name")->where("category_status=1")->select();
            if(!empty($pcategorylist)){
                foreach($pcategorylist as $k => $v){
                    $categorylist[$v['category_id']] = $v['category_name'];
                }
            }
        }
        //var_dump( D('Business')->itemsByIds($business_ids));die();
        $this->assign('list', $list); // 赋值数据集www.hatudou.com  二开开发qq  120585022
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('categorylist', $categorylist);
        $this->display(); // 输出模板
    }

    public function addpao() {
        if ($this->isPost()) {
            $data = I("post.");
            $pao_category = D("PaoCategory");
            $check = $pao_category->field("category_price")->where("category_id='".$data['category_id']."'")->find();
            if(empty($check) || $check['category_price']>$data['record_price']){
                $this->baoError('出价不可低于该分类最低'.$check['category_price'].'报价！');
            }
            $pao_setting = D("PaoSetting");
            $pao_setting_info = $pao_setting->field("setting_value")->where("setting_id=1")->find();
            $obj = D('PaoRecords');
            $time = time();
            $data['fabu_uid'] = $this->uid;
            $data['fenpei_bili'] = $pao_setting_info['setting_value'];
            $data['fenpei_add'] = $time;
            $data['fenpei_update'] = $time;
            $id = $obj->add($data);
            if($id>0){
                $backurl = U('pao/pay',array('rid'=>$id));
                $this->baoSuccess('数据提交成功，等待去付款', $backurl);
            }
            $this->baoError('操作失败！');
        } else {
            $pao_category = D("PaoCategory");
            $pcategorylist = $pao_category->field("category_id,category_name,category_price")->where("category_status=1")->select();
            $this->assign('detail', array());
            $this->assign('categorylist', $pcategorylist);
            $this->display();
        }
    }

    public function editpao() {
        $id = I("get.record_id");
        if ($id>0) {
            $obj = D('PaoRecords');
            $detail = $obj->where("record_id='".$id."' and fabu_uid='".$this->uid."' and is_close=0")->find();
            if(empty($detail)){
                $this->baoError('该记录不存在，或者无权查看');
            }
            if ($this->isPost()) {
                $data = I("post.");
                $pao_category = D("PaoCategory");
                $check = $pao_category->field("category_price")->where("category_id='".$data['category_id']."'")->find();
                if(empty($check) || $check['category_price']>$data['record_price']){
                    $this->baoError('出价不可低于该分类最低'.$check['category_price'].'元报价！');
                }
                $vo = $obj->where("record_id='".$id."' and fabu_uid='".$this->uid."'")->save($data);
                if (false !== $vo) {
                    $this->baoSuccess('操作成功', U('pao/index'));
                }
                $this->baoError('操作失败');
            } else {
                $pao_category = D("PaoCategory");
                $pcategorylist = $pao_category->field("category_id,category_name,category_price")->where("category_status=1")->select();
                $this->assign('categorylist', $pcategorylist);
                $this->assign('detail', $detail);
                $this->display();
            }
        } else {
            $this->baoError('请选择要编辑的信息');
        }
    }

    public function viewpao() {
        $id = I("get.record_id");
        if ($id>0) {
            $obj = D('PaoRecords');
            $detail = $obj->where("record_id='".$id."' and fabu_uid='".$this->uid."' and is_close=0")->find();
            if(empty($detail)){
                $this->baoError('该记录不存在，或者无权查看');
            }
            $pao_category = D("PaoCategory");
            $pcategorylist = $pao_category->field("category_id,category_name,category_price")->where("category_status=1")->select();
            $this->assign('categorylist', $pcategorylist);
            $this->assign('detail', $detail);
            $this->display();
        } else {
            $this->baoError('请选择要查看的信息');
        }
    }

    
    public function paoview() {
        $id = I("get.record_id");
        if ($id>0) {
            $obj = D('PaoRecords');
            $detail = $obj->where("record_id='".$id."' and jie_uid='".$this->uid."' and is_close=0")->find();
            if(empty($detail)){
                $this->baoError('该记录不存在，或者无权查看');
            }
            $pao_category = D("PaoCategory");
            $pcategorylist = $pao_category->field("category_id,category_name,category_price")->where("category_status=1")->select();
            $this->assign('categorylist', $pcategorylist);
            $this->assign('detail', $detail);
            $this->display();
        } else {
            $this->baoError('请选择要查看的信息');
        }
    }

    public function deletepao() {
        $id = I("get.record_id");
        if($id>0){
            $obj = D('PaoRecords');
            $detail = $obj->field("record_id")->where("record_id='".$id."' and fabu_uid='".$this->uid."'")->find();
            if(empty($detail)){
                $this->baoError('该记录不存在，或者无权查看');
            }
            $obj->where("record_id='".$id."' and fabu_uid='".$this->uid."'")->save(array('is_close' => 1,'fenpei_update'=>time()));
            $this->baoSuccess('删除成功！', U('pao/index'));
        }
        else{
            $this->baoError('请选择要删除的信息');
        }
    }
        
    public function confirmpao() {
        $id = I("get.record_id");
        if($id>0){
            $obj = D('PaoRecords');
            $detail = $obj->field("record_id,jie_uid,record_price")->where("record_id='".$id."' and fabu_uid='".$this->uid."' and is_close=0 and is_fenpei=0 and is_finished=0")->find();
            if(empty($detail)){
                $this->baoError('该记录不存在，或者无权查看');
            }
            $vo = $obj->where("record_id='".$id."' and fabu_uid='".$this->uid."'")->save(array('is_finished'=>1,'is_fenpei' => 1,'fenpei_update'=>time()));
            if(false===$vo){
                $this->baoError('数据更新失败');
            }
            else{
                $pao_setting = D("PaoSetting"); 
                $info = $pao_setting->field("setting_value")->where("setting_id=1")->find();
                $exp = explode(":",$info['setting_value']);
                $price = $detail['record_price']*$exp['0']/($exp['0']+$exp['1']);
                $users = D("Users");
                $userinfo = $users->field("money,pao_money")->where("user_id='".$detail['jie_uid']."'")->find();
                $money = $userinfo['money']+$price*100;
                $pao_money = $userinfo['pao_money']+$price;
                $users->where("user_id='".$detail['jie_uid']."'")->save(array('money'=>$money,'pao_money'=>$pao_money));
                $this->baoSuccess('确认成功！', U('pao/index'));
            }
        }
        else{
            $this->baoError('请选择要删除的信息');
        }
    }

    public function buy() {
        $id =$this->_get('rid');
        $pao_records = D("PaoRecords");
        $detail = $pao_records->field("record_id,record_title,record_price")->where("record_id='".$id."' and fabu_uid='".$this->uid."' and jie_uid<1 and record_status=1 and is_finished=0 and is_fenpei=0 and is_close=0")->find();
        if(empty($detail)){
            $this->error('该跑腿不存在');
            die;
        }
        $num = 1;
        $this->assign('num', $num);
        $this->assign('detail', $detail);
        $this->display();
    }

    public function order() {
        if (!$this->uid) {
          $this->ajaxLogin(); 
        }
        if (!$this->member['mobile']) {
            echo "<script>parent.check_user_mobile_for_pc();</script>";
            die();
        }
        $id = (int) $this->_get('rid');
        $pao_records = D("PaoRecords");
        $detail = $pao_records->field("record_id,record_title,record_price")->where("record_id='".$id."' and fabu_uid='".$this->uid."' and jie_uid<1 and record_status=1 and is_finished=0 and is_fenpei=0 and is_close=0")->find();
        if(empty($detail)){
            $this->error('该跑腿不存在');
            die;
        }
        $order = (int)$this->_param('order');
        if(!empty($order) && $detail['record_id']==$order){
            $this->baoSuccess('订单提交成功！', U('pao/pay', array('rid' =>$detail['record_id'])));
        }
        else{            
            $this->baoError('订单提交失败！');
        }
    }

    public function pay() {
        if (empty($this->uid)) {
            header("Location:" . U('passport/login'));
            die;
        }
        $id = (int)$this->_get('rid');
        $pao_records = D("PaoRecords");
        $detail = $pao_records->field("record_id,record_title,record_price")->where("record_id='".$id."' and fabu_uid='".$this->uid."' and jie_uid<1 and record_status=1 and is_finished=0 and is_fenpei=0 and is_close=0")->find();
        if(empty($detail)){
            $this->baoError('该订单不存在');
            die;
        }
        $paymentlist = D('Payment')->getPayments();
        $this->assign('use_integral', 0);
        $this->assign('payment', $paymentlist);
        $this->assign('detail', $detail);
        $this->display();
    }

    public function pay2() {
        if (empty($this->uid)) {
            $this->ajaxLogin();
        }
        $order_id = (int) $this->_get('order_id');
        $pao_records = D("PaoRecords");
        $detail = $pao_records->field("record_id,record_title,record_price")->where("record_id='".$order_id."' and fabu_uid='".$this->uid."' and jie_uid<1 and record_status=1 and is_finished=0 and is_fenpei=0 and is_close=0")->find();
        if(empty($detail)){
            $this->baoError('该订单不存在');
            die;
        }
        if (!$code = $this->_post('code')) {
            $this->baoError('请选择支付方式！');
        }
        $payment = D('Payment')->checkPayment($code);
        if (empty($payment)) {
            $this->baoError('该支付方式不存在');
        }
        $logs = D('Paymentlogs')->getLogsByOrderId('pao', $order_id);
        if (empty($logs)) {
            $logs = array(
                'type' => 'pao',
                'user_id' => $this->uid,
                'order_id' => $order_id,
                'code' => $code,
                'need_pay' => $detail['record_price']*100,
                'create_time' => NOW_TIME,
                'create_ip' => get_client_ip(),
                'is_paid' => 0
            );
            $logs['log_id'] = D('Paymentlogs')->add($logs);
        } else {
            $logs['need_pay'] = $detail['record_price']*100;
            $logs['code'] = $code;
            D('Paymentlogs')->save($logs);
        }
        D('Weixintmpl')->weixin_notice_pao_user($order_id,$this->uid,1);
        $this->baoSuccess('选择支付方式成功！下面请进行支付！', U('/payment/payment', array('log_id' => $logs['log_id'])));
    }
}