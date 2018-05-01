<?php
class KtvAction extends CommonAction {
	private $edit_fields = array('shop_id', 'ktv_name','intro', 'tel', 'photo', 'addr', 'city_id', 'area_id', 'business_id','lat', 'lng', 'date_id','details','audit');
    public function _initialize() {
        parent::_initialize();
		
		$this->assign('group', $this->group);
		$this->getKtvDate = D('Ktv')->getKtvDate();
        $this->assign('dates',  $this->getKtvDate);
        $this->assign('getTypes', D('KtvOrder')->getType());//订单状态
		
    }

    
    
    //订单首页
    public function index(){
		 $ktv = D('Ktv');
        import('ORG.Util.Page'); 
         $map = array('closed' => 0);
        //$map['ktv_id'] = $detail['ktv_id'];
        if ($keyword = $this->_param('keyword', 'htmlspecialchars')) {
            $map['ktv_name'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
        if ($city_id = (int) $this->_param('city_id')) {
            $map['city_id'] = $city_id;
            $this->assign('city_id', $city_id);
        }
        if ($area_id = (int) $this->_param('area_id')) {
            $map['area_id'] = $area_id;
            $this->assign('area_id', $area_id);
        }
        if ($cate_id = (int) $this->_param('cate_id')) {
            $map['cate_id'] = $cate_id;
            $this->assign('cate_id', $cate_id);
        }
        
       $count = $ktv->where($map)->count(); 
        $Page = new Page($count, 25); 
        $show = $Page->show(); 
        $list = $ktv->where($map)->order(array('ktv_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); 
        $this->assign('page', $show); 
        $this->display(); 
    }

	 public function audit($ktv_id=0 ) {
        $obj = D('Ktv');
        if (is_numeric($ktv_id) && ($ktv_id = (int) $ktv_id)) {
            if($obj->where(array('ktv_id' => $ktv_id))->save(array('audit' => 1))){
				$this->baoSuccess('审核成功！', U('ktv/index'));
			}else{
				$this->baoError('审核失败');
			}
        } else {
            $ktv_id = $this->_post('ktv_id', false);
            if (is_array($ktv_id)) {
                foreach ($ktv_id as $id) {
                    $obj->save(array('ktv_id' => $id, 'audit' => 1));
                }
                $this->baoSuccess('审核成功！', U('ktv/index'));
            }
            $this->baoError('请选择要审核的KTV');
        }
    }

    
	    public function delete($ktv_id = 0) {
         $obj = D('Ktv');
        if (is_numeric($ktv_id) && ($ktv_id = (int) $ktv_id)) {
            if($obj->where(array('ktv_id' => $ktv_id))->save(array('closed' => 1))){
				$this->baoSuccess('删除成功！', U('ktv/index'));
			}else{
				$this->baoError('删除失败');
			}
        } else {
            $ktv_id = $this->_post('ktv_id', false);
            if (is_array($ktv_id)) {
                foreach ($ktv_id as $id) {
                    $obj->save(array('ktv_id' => $id, 'closed' => 1));
                }
                $this->baoSuccess('删除成功！', U('ktv/index'));
            }
            $this->baoError('请选择要删除的KTV');
        }
    }
   
    public function edit($ktv_id = 0) {
 if ($ktv_id = (int) $ktv_id) {
            $obj = D('Ktv');
            if (!$detail = $obj->find($ktv_id)) {
                $this->baoError('请选择要编辑的KTV');
            }
            if ($this->isPost()) {
				
				$data = $this->editCheck();
                $data['ktv_id'] = $ktv_id;

				
                $date_id = $this->_post('date_id', false);
				$date_ids = implode(',', $date_id);
				$data['date_id'] = $date_ids;
                
                
                if (false !== $obj->save($data)){
                   
                    $this->baoSuccess('操作0成功', U('ktv/index'));
                }
                $this->baoError('操作失败');
				 } else {
                
               $this->assign('detail',$detail);
            $this->assign('shop',D('Shop')->find($detail['shop_id']));
			$this->assign('date_ids', $date_ids = explode(',', $detail['date_id']));
            $this->assign('detail', $detail);
            $this->display();
            }
        } else {
            $this->baoError('请选择要编辑的酒店');
        }
    }

    private function editCheck() {
        
		 $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
		$data['shop_id'] = $this->shop_id;
        $data['ktv_name'] = htmlspecialchars($data['ktv_name']);
        if (empty($data['ktv_name'])) {
            $this->baoError('名称不能为空');
        }
		$data['intro'] = htmlspecialchars($data['intro']);
        if (empty($data['intro'])) {
            $this->baoError('简介不能为空');
        }
		$data['addr'] = htmlspecialchars($data['addr']);
        if (empty($data['addr'])) {
            $this->baoError('地址不能为空');
        }
		$data['tel'] = htmlspecialchars($data['tel']);
        if (empty($data['tel'])) {
            $this->baoError('联系电话不能为空');
        }
		$data['city_id'] = $this->shop['city_id'];
        $data['area_id'] = $this->shop['area_id'];
        $data['business_id'] = $this->shop['business_id'];
		
        $data['lng'] = htmlspecialchars($data['lng']);
        $data['lat'] = htmlspecialchars($data['lat']);
        if (empty($data['lng']) || empty($data['lat'])) {
            $this->baoError('坐标没有选择');
        }
        $data['photo'] = htmlspecialchars($data['photo']);
        if (empty($data['photo'])) {
            $this->baoError('请上传缩略图');
        }
        if (!isImage($data['photo'])) {
            $this->baoError('缩略图格式不正确');
        } 
        
        $data['details'] = SecurityEditorHtml($data['details']);
        if (empty($data['details'])) {
            $this->baoError('商家简介不能为空');
        }
        if ($words = D('Sensitive')->checkWords($data['details'])) {
            $this->baoError('详情含有敏感词：' . $words);
        }
        
        return $data;
    }
    public function order(){
        $ktvorder = D('Ktvorder');
        import('ORG.Util.Page'); 
        $map = array();
        $map['closed'] = 0;
        
		if ($order_id = (int) $this->_param('order_id')) {
            $map['order_id'] = $order_id;
            $this->assign('order_id', $order_id);
        }
        if ($shop_id = (int) $this->_param('shop_id')) {
            $map['shop_id'] = $shop_id;
            $shop = D('Shop')->find($shop_id);
            $this->assign('shop_name', $shop['shop_name']);
            $this->assign('shop_id', $shop_id);
        }
        if ($user_id = (int) $this->_param('user_id')) {
            $map['user_id'] = $user_id;
            $users = D('Users')->find($user_id);
            $this->assign('nickname', $users['nickname']);
            $this->assign('user_id', $user_id);
        }
        $count = $ktvorder->where($map)->count(); 
        $Page = new Page($count, 15); 
        $show = $Page->show(); 
        $list = $ktvorder->where($map)->order(array('order_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $ktv_ids = array();
		foreach($list as $k => $v){
			$ktv_ids[$v['ktv_id']] = $v['ktv_id'];
            $p = D('Ktv_room') -> where(array('room_id'=>$v['room_id'])) -> find();
            $list[$k]['room'] = $p;
        }
        $this->assign('ktv', D('Ktv')->itemsByIds($ktv_ids));
        $this->assign('list', $list); 
        $this->assign('page', $show);
        $this->display(); 
    }
    //Ktv房间列表
    public function room($ktv_id = 0){ 
        $ktv_id = (int)$ktv_id;
		$Ktv = D('Ktv');
        if (!$detail = $Ktv->find($ktv_id)) {
          $this->baoError('请选择要编辑的KTV房间');
        }
        $obj = D('KtvRoom');
        import('ORG.Util.Page'); 
		$map = array('ktv_id' => $ktv_id);
        if ($keyword = $this->_param('keyword', 'htmlspecialchars')) {
            $map['title|intro'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
        $count = $obj->where($map)->count();
        $Page = new Page($count, 25); 
        $show = $Page->show(); 
        $list = $obj->where($map)->order(array('room_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('detail',$detail);
        $this->assign('list', $list); 
        $this->assign('page', $show); 
        $this->display();
    }

    
   //添加套餐
    public function setroom(){ 
        $ktv_id = (int)$ktv_id;
        if ($this->isPost()) {
            $data = $this->roomCreateCheck();
            $obj = D('KtvRoom');
            if ($room_id = $obj->add($data)) {
                $this->baoSuccess('添加成功', U('ktv/room'));
            }
            $this->baoError('操作失败！');
        } else {
            $this->display();
        }
    }
    
    
    private function roomCreateCheck() {
        $data = $this->checkFields($this->_post('data', false), array('title','photo','intro','num','price','daofu_price','small_price','accommodate_number','jiesuan_price'));
        $data['title'] = htmlspecialchars($data['title']);
        if(empty($data['title'])) {
            $this->baoError('房间名称不能为空');
        }
		$data['photo'] = htmlspecialchars($data['photo']);
        if (empty($data['photo'])) {
            $this->baoError('请上传缩略图');
        }
        if (!isImage($data['photo'])) {
            $this->baoError('缩略图格式不正确');
        } 
		$data['num'] = htmlspecialchars($data['num']);
        if(empty($data['num'])) {
            $this->baoError('每天可预约多人人次必填');
        }
		$data['intro'] = htmlspecialchars($data['intro']);
        if(empty($data['intro'])) {
            $this->baoError('房间简介不能为空');
        }
		$data['price'] = (int)($data['price']*100);
        if (empty($data['price'])) {
            $this->baoError('套餐价格不能为空');
        }
		$data['daofu_price'] = (int)($data['daofu_price']*100);
		$data['small_price'] = (int)($data['small_price']*100);
		$data['accommodate_number'] = (int)$data['accommodate_number'];
        if (empty($data['accommodate_number'])) {
            $this->baoError('容纳人数不能为空');
        }
		$data['jiesuan_price'] = (int)($data['jiesuan_price']*100);
        $data['jiesuan_price'] = (int)($data['jiesuan_price']*100);
        if (!empty($data['jiesuan_price'])) {
           if($data['jiesuan_price'] > $data['price']) {
				$this->baoError('结算价格不能大于套餐价格,但是可等于结算价格,但是可以填写位0');
			} 
        }
        $detail = D('Ktv')->where(array('shop_id'=>$this->shop_id))->find();
        $data['ktv_id'] = $detail['ktv_id'];
		$au=$this->_CONFIG['ktv']['ktv_audit'];
        $data['audit'] = $au;
        return $data;
    }
    
     
    
    public function editroom($room_id = 0){
        $ktv_id = (int)$ktv_id;
        if ($room_id = (int) $room_id) {
            $obj = D('KtvRoom');
            if (!$detail = $obj->find($room_id)) {
                $this->baoError('请选择要编辑的套餐');
            }
            if ($detail['ktv_id'] != $ktv_id) {
                $this->baoError('非法操作');
            }
            if ($this->isPost()) {
                $data = $this->roomEditCheck();
                $data['room_id'] = $room_id;
                if (false !== $obj->save($data)) {
                    $this->baoSuccess('保存成功', U('ktv/room'));
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
        $data = $this->checkFields($this->_post('data', false), array('title','photo','intro','num','price','daofu_price','small_price','accommodate_number','jiesuan_price'));
         $data['title'] = htmlspecialchars($data['title']);
        if(empty($data['title'])) {
            $this->baoError('房间名称不能为空');
        }
		$data['photo'] = htmlspecialchars($data['photo']);
        if (empty($data['photo'])) {
            $this->baoError('请上传缩略图');
        }
        if (!isImage($data['photo'])) {
            $this->baoError('缩略图格式不正确');
        } 
		$data['num'] = htmlspecialchars($data['num']);
        if(empty($data['num'])) {
            $this->baoError('每天可预约多人人次必填');
        }
		$data['intro'] = htmlspecialchars($data['intro']);
        if(empty($data['intro'])) {
            $this->baoError('房间简介不能为空');
        }
		$data['price'] = (int)($data['price']*100);
        if (empty($data['price'])) {
            $this->baoError('套餐价格不能为空');
        }
		$data['small_price'] = (int)($data['small_price']*100);
		$data['daofu_price'] = (int)($data['daofu_price']*100);
		$data['accommodate_number'] = (int)$data['accommodate_number'];
        if (empty($data['accommodate_number'])) {
            $this->baoError('容纳人数不能为空');
        }
		$data['jiesuan_price'] = (int)($data['jiesuan_price']*100);
        $data['jiesuan_price'] = (int)($data['jiesuan_price']*100);
        if (!empty($data['jiesuan_price'])) {
           if($data['jiesuan_price'] > $data['price']) {
				$this->baoError('结算价格不能大于套餐价格,但是可等于结算价格,但是可以填写位0');
			} 
        }
        $detail = D('Ktv')->where(array('shop_id'=>$this->shop_id))->find();
        $data['ktv_id'] = $detail['ktv_id'];
        return $data;
    }
	
    public function deleteroom($room_id = 0){
        $ktv_id = (int)$ktv_id;
        if ($room_id = (int) $room_id) {
            $obj = D('KtvRoom');
            if (!$detail = $obj->find($pid)) {
                $this->baoError('请选择要删除的房间');
            }
            if ($detail['ktv_id'] != $ktv_id) {
                $this->baoError('非法操作');
            }
            if (false !== $obj->save(array('room_id'=>$room_id,'closed'=>1))){
                $this->baoSuccess('删除成功', U('ktv/room'));
            }else {
                $this->baoError('删除失败');
            }
        } else {
            $this->baoError('请选择要删除的套餐');
        }
    }    
    
   
}
