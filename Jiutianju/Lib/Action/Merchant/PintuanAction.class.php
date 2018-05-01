<?php
class PintuanAction extends CommonAction {
    
   

    
    private function check_pintuan(){
        
        $pintuan = D('Pshop');
        $res =  $pintuan->where(array('user_id'=>$this->uid))->find();
        if(!$res){
            $this->error('请先完善拼团资料！',U('pintuan/set_pintuan'));
        }elseif ($res['closed'] == 1) {
            $this->error('您的拼团已被删除请重新提交资料！', U('pintuan/set_pintuan'));
        }elseif($res['audit'] == 0){
            $this->error('您的拼团申请正在审核中，请耐心等待！',U('pintuan/set_pintuan'));
        }elseif($res['audit'] == 2){
            $this->error('您的拼团申请未通过审核！',U('pintuan/set_pintuan'));
        }else{
            return $res['id'];
        }
        
    }
    
    public function index(){
        $id = $this->check_pintuan();
        $pintuanorder = D('pintuanorder');
        $pintuanorder->plqx($pintuan_id);
        import('ORG.Util.Page'); 
        $map = array('pintuan_id' => $pintuan_id);
        $map['closed'] = 0;
        if (($bg_date = $this->_param('bg_date', 'htmlspecialchars') ) && ($end_date = $this->_param('end_date', 'htmlspecialchars'))) {
            $bg_time = strtotime($bg_date);
            $end_time = strtotime($end_date);
            $map['create_time'] = array(array('ELT', $end_time), array('EGT', $bg_time));
            $this->assign('bg_date', $bg_date);
            $this->assign('end_date', $end_date);
        } else {
            if ($bg_date = $this->_param('bg_date', 'htmlspecialchars')) {
                $bg_time = strtotime($bg_date);
                $this->assign('bg_date', $bg_date);
                $map['create_time'] = array('EGT', $bg_time);
            }
            if ($end_date = $this->_param('end_date', 'htmlspecialchars')) {
                $end_time = strtotime($end_date);
                $this->assign('end_date', $end_date);
                $map['create_time'] = array('ELT', $end_time);
            }
        }

        if ($keyword = $this->_param('keyword', 'htmlspecialchars')) {
            $map['order_id'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
        if (isset($_GET['st']) || isset($_POST['st'])) {
            $st = (int) $this->_param('st');
            if ($st != 999) {
                $map['order_status'] = $st;
            }
            $this->assign('st', $st);
        } else {
            $this->assign('st', 999);
        }
        $count = $pintuanorder->where($map)->count(); 
        $Page = new Page($count, 15); 
        $show = $Page->show(); 
        $list = $pintuanorder->where($map)->order(array('order_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $room_ids = array();
        foreach($list as $k=>$val){
            $room_ids[$val['room_id']] = $val['room_id'];
        }
        $this->assign('rooms',D('pintuanroom')->itemsByIds($room_ids));
        $this->assign('list', $list); 
        $this->assign('page', $show);
        $this->display(); 
    }
    
    
    public function set_pintuan(){
        $obj = D('pshop');
        $pintuan = $obj->where(array('user_id'=>$this->user_id))->find();
        if ($this->isPost()) { 
           $data = $this->createCheck();
          
            if (empty($pintuan)) {
               
                if($id = $obj->add($data)){
                    
                     $this->baoSuccess('设置成功', U('pintuan/index'));
                }else{
                    $this->baoError('设置失败');
                }
				 $this->baoError('设置失败');
            }
			 
		}
		$this->display();
    }
    
    private function createCheck() {
        $data = $this->checkFields($this->_post('data', false), array('name', 'tel', 'logo', 'address','user_id','add_time', 'mianyunfei','tongchen','closed', 'audit'));
        $data['pintuan_name'] = htmlspecialchars($data['pintuan_name']);
        if (empty($data['name'])) {
            $this->baoError('商家名称不能为空');
        }
		$data['tel'] = htmlspecialchars($data['tel']);
       if (empty($data['tel'])) {
            $this->baoError('电话不能为空');
        }
		$data['logo'] = htmlspecialchars($data['logo']);
       if (empty($data['logo'])) {
            $this->baoError('LOGO不能为空');
        }
		$data['address'] = htmlspecialchars($data['address']);
       if (empty($data['address'])) {
            $this->baoError('地址不能为空');
        }
		$data['mianyunfei'] = htmlspecialchars($data['mainyunfie']);
       $data['tongchen'] = htmlspecialchars($data['tongchen']);
       if (empty($data['tongchen'])) {
            $this->baoError('同城ID不能为空');
        }
       $data['user_id'] = $this->uid;
	   $data['add_time'] = NOW_TIME;
	   $data['close'] = 0;
       $data['audit'] = 0;
        return $data;
    }
    
    
    public function room(){ 
        $pintuan_id = $this->check_pintuan();
        $room = D('pintuanroom');
        import('ORG.Util.Page'); 
        $map = array('pintuan_id' => $pintuan_id);
        if ($keyword = $this->_param('keyword', 'htmlspecialchars')) {
            $map['title'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
        $count = $room->where($map)->count(); 
        $Page = new Page($count, 25); 
        $show = $Page->show(); 
        $list = $room->where($map)->order(array('room_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); 
        $this->assign('page', $show); 
        $this->display(); 
    }

    

    public function setroom(){ //添加房间
        $this->check_pintuan();
        if ($this->isPost()) {
            $data = $this->roomCreateCheck();
            $obj = D('pintuanroom');
            if ($room_id = $obj->add($data)) {
                $this->baoSuccess('添加成功', U('pintuan/room'));
            }
            $this->baoError('操作失败！');
        } else {
            $this->display();
        }
    }
    
    
    private function roomCreateCheck() {
        $data = $this->checkFields($this->_post('data', false), array('title', 'price','settlement_price', 'type', 'photo','pintuan_id','is_zc', 'is_kd','is_cancel','sku'));
        $data['title'] = htmlspecialchars($data['title']);
        if (empty($data['title'])) {
            $this->baoError('房间名称不能为空');
        }$data['price'] = (int)$data['price'];
        if (empty($data['price'])) {
            $this->baoError('房间价格不能为空');
        }$data['settlement_price'] = (int)$data['settlement_price'];
        if (empty($data['settlement_price'])) {
            $this->baoError('房间结算价格不能为空');
        }if ($data['settlement_price'] >=$data['price']) {
            $this->baoError('结算价格不能大于房间价格');
        }$data['type'] = (int)$data['type'];
        if (empty($data['type'])) {
            $this->baoError('房间类型不能为空');
        }
        $data['type'] = (int)$data['type'];
        $pintuan = D('pintuan')->where(array('shop_id'=>$this->shop_id))->find();
        $data['pintuan_id'] = $pintuan['pintuan_id'];
        $data['photo'] = htmlspecialchars($data['photo']);
        if (empty($data['photo'])) {
            $this->baoError('请上传房间图片');
        }
        if (!isImage($data['photo'])) {
            $this->baoError('房间图片格式不正确');
        } 
        $data['sku'] = (int) $data['sku'];
        $data['is_zc'] = (int)$data['is_zc'];
        $data['is_kd'] = (int)$data['is_kd'];
        $data['is_cancel'] = (int)$data['is_cancel'];
        $data['create_time'] = NOW_TIME;
        $data['create_ip'] = get_client_ip();
        return $data;
    }
    
    public function editroom($room_id=null){
        $pintuan_id = $this->check_pintuan();
        if ($room_id = (int) $room_id) {
            $obj = D('pintuanroom');
            if (!$detail = $obj->find($room_id)) {
                $this->baoError('请选择要编辑的房间');
            }
            if ($detail['pintuan_id'] != $pintuan_id) {
                $this->baoError('请不要操作别人的房间');
            }
            if ($this->isPost()) {
                $data = $this->roomEditCheck();
                $data['room_id'] = $room_id;
                if (false !== $obj->save($data)) {
                    $this->baoSuccess('操作成功', U('pintuan/room'));
                }
                $this->baoError('操作失败');
            } else {
                $this->assign('detail',$detail);
                $this->display();
            }
        } else {
            $this->baoError('请选择要编辑的房间');
        }
    }

    

    private function roomEditCheck() {
        $data = $this->checkFields($this->_post('data', false), array('title', 'price','settlement_price', 'type', 'photo','is_zc', 'is_kd','is_cancel','sku'));
        $data['title'] = htmlspecialchars($data['title']);
        if (empty($data['title'])) {
            $this->baoError('房间名称不能为空');
        }$data['price'] = (int)$data['price'];
        if (empty($data['price'])) {
            $this->baoError('房间价格不能为空');
        }$data['settlement_price'] = (int)$data['settlement_price'];
        if (empty($data['settlement_price'])) {
            $this->baoError('房间结算价格不能为空');
        }if ($data['settlement_price'] >=$data['price']) {
            $this->baoError('结算价格不能大于房间价格');
        }$data['type'] = (int)$data['type'];
        if (empty($data['type'])) {
            $this->baoError('房间类型不能为空');
        }
        $data['type'] = (int)$data['type'];
        $pintuan = D('pintuan')->where(array('shop_id'=>$this->shop_id))->find();
        $data['pintuan_id'] = $pintuan['pintuan_id'];
        $data['photo'] = htmlspecialchars($data['photo']);
        if (empty($data['photo'])) {
            $this->baoError('请上传房间图片');
        }
        if (!isImage($data['photo'])) {
            $this->baoError('房间图片格式不正确');
        } 
        $data['sku'] = (int) $data['sku'];
        $data['is_zc'] = (int)$data['is_zc'];
        $data['is_kd'] = (int)$data['is_kd'];
        $data['is_cancel'] = (int)$data['is_cancel'];
        return $data;
    }
   
    
    public function cancel($order_id){
        $pintuan_id = $this->check_pintuan();
        if($order_id = (int) $order_id){
            if(!$order = D('pintuanorder')->find($order_id)){
                $this->baoError('订单不存在');
            }elseif($order['pintuan_id'] != $pintuan_id){
                $this->baoError('非法操作订单');
            }elseif($order['order_status'] == -1){
                $this->baoError('该订单已取消');
            }else{
                if(false !== D('pintuanorder')->cancel($order_id)){
                    $this->baoSuccess('订单取消成功',U('pintuan/index'));
                }else{
                    $this->baoError('订单取消失败');
                }
            }
        }else{
            $this->baoError('请选择要取消的订单');
        }
    }
    
    
    public function complete($order_id){
        $pintuan_id = $this->check_pintuan();
        if($order_id = (int) $order_id){
            if(!$order = D('pintuanorder')->find($order_id)){
                $this->baoError('订单不存在');
            }elseif($order['pintuan_id'] != $pintuan_id){
                $this->baoError('非法操作订单');
            }elseif(($order['online_pay'] == 1&&$order['order_status'] != 1)||($order['online_pay'] == 0&&$order['order_status'] != 0)){
                $this->baoError('该订单无法完成');
            }else{

                if(false !== D('pintuanorder')->complete($order_id)){
                    $this->baoSuccess('订单操作成功',U('pintuan/index'));
                }else{
                    $this->baoError('订单操作失败');
                }
            }
        }else{
            $this->baoError('请选择要完成的订单');
        }
    }
    
    
    public function delete($order_id){
        $pintuan_id = $this->check_pintuan();
        if($order_id = (int) $order_id){
            if(!$order = D('pintuanorder')->find($order_id)){
                $this->baoError('订单不存在');
            }elseif($order['pintuan_id'] != $pintuan_id){
                $this->baoError('非法操作订单');
            }elseif($order['order_status'] != -1){
                $this->baoError('订单状态不正确');
            }else{
                if(false !== D('pintuanorder')->save(array('order_id'=>$order_id,'closed'=>1))){
					D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 1,$type = 6,$status = 11);
					D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 2,$type = 6,$status = 11);
                    $this->baoSuccess('订单删除成功',U('pintuan/index'));
                }else{
                    $this->baoError('订单删除失败');
                }
            }
        }else{
            $this->baoError('请选择要删除的订单');
        }
    }
    
    public function detail($order_id=null){
        $pintuan_id = $this->check_pintuan();
        if(!$order_id = (int)$order_id){
            $this->error('订单不存在');
        }elseif(!$detail = D('pintuanorder')->find($order_id)){
             $this->error('订单不存在');
        }elseif($detail['closed'] == 1){
             $this->error('订单已删除');
        }elseif($detail['pintuan_id'] != $pintuan_id){
             $this->error('非法的订单操作');
        }else{
            $detail['night_num'] = $this->diffBetweenTwoDays($detail['stime'],$detail['ltime']); 
            $detail['room'] = D('pintuanroom')->find($detail['room_id']); 
            $detail['pintuan'] = D('pintuan')->find($detail['pintuan_id']);
            $this->assign('detail',$detail);
            $this->display();
        }
    }
	
	//同意退款操作
    public function agree_refund(){
        $order_id = I('order_id', 0, 'trim,intval');
        $pintuanorder = D('pintuanorder');
        $pintuan_order = $pintuanorder->where('order_id =' . $order_id)->find();
		if (!($detial = $pintuanorder->find($order_id))) {
                $this->baoError('该订单不存在');
        }elseif($pintuan_order['order_status'] != 3){
				$this->baoError('订单状态不正确，无法退款');
		}elseif($detial['shop_id'] != $this->shop_id){
				$this->baoError('请不要操作其他商铺的订单');
		}else{
			if (false == $pintuanorder->pintuan_refund_user($order_id)) {//退款操作
				$this->baoError('非法操作');
			}else{
				$this->baoSuccess('已成功退款',U('pintuan/order'));	
			}
		}
    }
    
    function diffBetweenTwoDays ($day1, $day2){
          $second1 = strtotime($day1);
          $second2 = strtotime($day2);

          if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
          }
          return ($second1 - $second2) / 86400;
    }

  
}
