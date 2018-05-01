<?php
class RenqiAction extends CommonAction {
	 
  
 
    public function index() {
        $Tomrenqi = D('Tom_renqi');
        import('ORG.Util.Page'); 
        $map = array('shop_id'=>$this->shop_id);
        $keyword = $this->_param('keyword', 'htmlspecialchars');
        if ($keyword) {
            $map['title'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('title', $title);
        }
       
        $count = $Tomrenqi->where($map)->count(); 
        $Page = new Page($count, 15);
        $show = $Page->show(); 
        $list = $Tomrenqi->where($map)->order(array('id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        
        
        $this->assign('list', $list); 
        $this->assign('page', $show); 
        $this->display(); 
    }
	
  

    public function create() {
		$shop=D('Shop')->where(array('shop_id'=>$this->shop_id))->find();
		$shopyx=$shop['yx'];
		if ($shopyx < 1){
			$this->error('您的3次营销额度已用完，您可以联系管理员或者通过续费增加3次额度！',U('renqi/index'));
		}
		$renqinum = D('Tom_renqi')->where(array('shop_id'=>$this->shop_id))->count();
		if ($renqinum >0){
		$this->error('您当前已存在正在运营的攒人气活动，不能再添加了。');
		}
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Tom_renqi');
            if ($obj->add($data)) {
				$shopyx=$shopyx-1;
                D('Shop')->save(array('shop_id'=>$this->shop_id,'yx'=>$shopyx));
                $this->baoSuccess('添加成功', U('renqi/index'));
            }
            $this->baoError('操作失败！');
        } else {
			
			$shop_id = $this->shop_id;
			$this->assign('shop_id', $shop_id);
			
            $this->display();
        }
    }

    private function createCheck() {
        $data = $this->checkFields($this->_post('data', false), array('id', 'shop_id','title','start_time', 'end_time','pic_url', 'everyone_prize', 'add_msg', 'content', 'share_logo', 'share_title', 'share_desc', 'must_gz', 'muse_gz_help', 'guanzu_desc', 'guanzu_url', 'add_time', 'paixu', 'status', 'audit'));
        
        $data['shop_id'] = $this->shop_id;
        
        $data['title'] = htmlspecialchars($data['title']);
        if (empty($data['title'])) {
            $this->baoError('活动标题不能为空');
        } $data['start_time'] = htmlspecialchars(strtotime($data['start_time']));
        if (empty($data['start_time'])) {
            $this->baoError('活动开始时间不能为空');
        }
		$data['end_time'] = htmlspecialchars(strtotime($data['end_time']));
        if (empty($data['end_time'])) {
            $this->baoError('活动结束时间不能为空');
        }$data['everyone_prize'] = htmlspecialchars($data['everyone_prize']);
        if (empty($data['everyone_prize'])) {
            $this->baoError('用户领取的奖品数不能为空');
        }
        $data['pic_url'] = htmlspecialchars($data['pic_url']);
        if (!isImage($data['pic_url'])) {
            $this->baoError('请上传正确1的图片');
        }
        $thumb = $this->_param('thumb', false);
        foreach ($thumb as $k => $val) {
            if (empty($val)) {
                unset($thumb[$k]);
            }
            if (!isImage($val)) {
                unset($thumb[$k]);
            }
        }
        $data['thumb'] = serialize($thumb);
		$data['add_msg'] = htmlspecialchars($data['add_msg']);
        if (empty($data['add_msg'])) {
            $this->baoError('提示信息不能为空');
        }
        $data['content'] = SecurityEditorHtml($data['content']);
        if (empty($data['content'])) {
            $this->baoError('活动规则不能为空');
        } 
		$data['share_title'] = htmlspecialchars($data['share_title']);
        if (empty($data['share_title'])) {
            $this->baoError('分享标题不能为空');
        }$data['share_desc'] = htmlspecialchars($data['share_desc']);
        if (empty($data['share_desc'])) {
            $this->baoError('分享内容不能为空');
        }$data['guanzu_desc'] = htmlspecialchars($data['guanzu_desc']);
        $data['guanzu_url'] = htmlspecialchars($data['guanzu_url']);
        $au=$this->_CONFIG['yx']['yx_audit'];
        $data['audit'] = $au;
        $data['paixu'] = htmlspecialchars($data['paixu']);
        
        
        $data['create_ip'] = get_client_ip();
        return $data;
    }



    public function edit($id = 0) {
        if ($id = (int) $id) {
            $obj = D('Tom_renqi');
            if (!$detail = $obj->find($id)) {
                $this->baoError('请选择要编辑的活动');
            }
            if ($this->isPost()) {
                $data = $this->editCheck();
                $data['id'] = $id;
                if (false !== $obj->save($data)) {
                    $this->baoSuccess('操作成功', U('renqi/index'));
                }
                $this->baoError('操作失败');
            } else {
                $thumb = unserialize($detail['thumb']);
                
                $this->assign('detail', $detail);
                $this->display();
            }
        } else {
            $this->baoError('请选择要编辑的活动');
        }
    }

    private function editCheck() {
        $data = $this->checkFields($this->_post('data', false), array('id', 'shop_id','title','start_time', 'end_time','pic_url', 'everyone_prize', 'add_msg', 'content', 'share_logo', 'share_title', 'share_desc', 'must_gz', 'muse_gz_help', 'guanzu_desc', 'guanzu_url', 'add_time', 'paixu', 'status', 'audit'));
       
        
        $data['shop_id'] = $this->shop_id;
        
        $data['title'] = htmlspecialchars($data['title']);
        if (empty($data['title'])) {
            $this->baoError('活动标题不能为空');
        }$data['everyone_prize'] = htmlspecialchars($data['everyone_prize']);
        if (empty($data['everyone_prize'])) {
            $this->baoError('用户领取的奖品数不能为空');
        }
        $data['pic_url'] = htmlspecialchars($data['pic_url']);
        if (!isImage($data['pic_url'])) {
            $this->baoError('请上传正确1的图片');
        }
        $thumb = $this->_param('thumb', false);
        foreach ($thumb as $k => $val) {
            if (empty($val)) {
                unset($thumb[$k]);
            }
            if (!isImage($val)) {
                unset($thumb[$k]);
            }
        }
        $data['thumb'] = serialize($thumb);
		$data['add_msg'] = htmlspecialchars($data['add_msg']);
        if (empty($data['add_msg'])) {
            $this->baoError('提示信息不能为空');
        }
        $data['content'] = SecurityEditorHtml($data['content']);
        if (empty($data['content'])) {
            $this->baoError('活动规则不能为空');
        } 
		$data['share_title'] = htmlspecialchars($data['share_title']);
        if (empty($data['share_title'])) {
            $this->baoError('分享标题不能为空');
        }$data['share_desc'] = htmlspecialchars($data['share_desc']);
        if (empty($data['share_desc'])) {
            $this->baoError('分享内容不能为空');
        }$data['guanzu_desc'] = htmlspecialchars($data['guanzu_desc']);
        $data['guanzu_url'] = htmlspecialchars($data['guanzu_url']);
       
        return $data;
    }

    public function delete($id = 0) {
		$activity_id = (int) $activity_id;
        if (!empty($activity_id)) {
            $obj = D('Activity');
			if (!$detail = $obj->find($activity_id)) {
                $this->baoError('删除的活动不存在');
            }
			if ($detail['shop_id'] != $this->shop_id) {
                $this->baoError('请不要非法操作');
            }
            $obj->delete($activity_id);
            $this->baoSuccess('删除成功！', U('activity/index'));
        } else {
            $this->baoError('请选择要删除的活动');
        }
    }
    public function creategife($id=0){
		
	  if ($this->isPost()) {
			
            $data = $this->creategifeCheck();
            $obj = D('Tom_renqi_prize');
            if ($rq_id = $obj->add($data)) {
                $this->baoSuccess('添加成功', U('renqi/index'));
            }
            $this->baoError('操作失败！');
        } else {
            $this->display();
        }
        
    }
    public function creategifeCheck($id=0){
		
        $data = $this->checkFields($this->_post('data', false), array('rq_id','prize_name','prize_pic_url','prize_desc','prize_pwd','prize_rq_num','prize_num','add_time'));
		$renqi = D('Tom_renqi')->where(array('shop_id'=>$this->shop_id))->find();
        $data['rq_id'] =$renqi['id'];
        $data['prize_name'] = htmlspecialchars($data['prize_name']);
        if (empty($data['prize_name'])) {
            $this->baoError('奖品名不能为空');
        }
        $data['prize_pic_url'] = htmlspecialchars($data['prize_pic_url']);
        if (empty($data['prize_pic_url'])) {
            $this->baoError('奖品图片不能为空');
        }
       
        $data['prize_desc'] = htmlspecialchars($data['prize_desc']);
        if (empty($data['prize_desc'])) {
            $this->baoError('奖品描述内容不能为空');
        }
       $data['prize_pwd'] = htmlspecialchars($data['prize_pwd']);
        if (empty($data['prize_pwd'])) {
            $this->baoError('兑奖密码不能为空');
        }
       $data['prize_rq_num'] = htmlspecialchars($data['prize_rq_num']);
        if (empty($data['prize_rq_num'])) {
            $this->baoError('奖品人气数不能为空');
        }
		$data['prize_num'] = htmlspecialchars($data['prize_num']);
        if (empty($data['prize_num'])) {
            $this->baoError('奖品数不能为空');
        }
       
        $data['add_time'] = NOW_TIME;
        
        return $data;
    }
	 public function editgife($id = 0){
        
        if ($id = (int) $id) {
            $obj = D('Tom_renqi_prize');
            if (!$detail = $obj->find($id)) {
                $this->baoError('请选择要编辑的奖品');
            }
            if ($detail['id'] != $id) {
                $this->baoError('非法操作');
            }
            if ($this->isPost()) {
                $data = $this->editgifeCheck();
                $data['id'] = $id;
                if (false !== $obj->save($data)) {
                    $this->baoSuccess('保存成功', U('renqi/index'));
                }
                $this->baoError('操作失败');
            } else {
                $this->assign('detail',$detail);
                $this->display();
            }
        } else {
            $this->baoError('请选择要编辑的奖品');
        }
    }
	
    public function editgifeCheck(){
		
        $data = $this->checkFields($this->_post('data', false), array('rq_id','prize_name','prize_pic_url','prize_desc','prize_pwd','prize_rq_num','prize_num','add_time'));
		
        $data['prize_name'] = htmlspecialchars($data['prize_name']);
        if (empty($data['prize_name'])) {
            $this->baoError('奖品名不能为空');
        }
        $data['prize_pic_url'] = htmlspecialchars($data['prize_pic_url']);
        if (empty($data['prize_pic_url'])) {
            $this->baoError('奖品图片不能为空');
        }
       
        $data['prize_desc'] = htmlspecialchars($data['prize_desc']);
        if (empty($data['prize_desc'])) {
            $this->baoError('奖品描述内容不能为空');
        }
       $data['prize_pwd'] = htmlspecialchars($data['prize_pwd']);
        if (empty($data['prize_pwd'])) {
            $this->baoError('兑奖密码不能为空');
        }
       $data['prize_rq_num'] = htmlspecialchars($data['prize_rq_num']);
        if (empty($data['prize_rq_num'])) {
            $this->baoError('奖品人气数不能为空');
        }
		$data['prize_num'] = htmlspecialchars($data['prize_num']);
        if (empty($data['prize_num'])) {
            $this->baoError('奖品数不能为空');
        }
       
       
        
        return $data;
    }
	 public function gifeindex($id=0) {
		 
        $Tomrenqiprize = D('Tom_renqi_prize');
        import('ORG.Util.Page'); 
        $map = array('rq_id'=>$id);
        $keyword = $this->_param('keyword', 'htmlspecialchars');
        if ($keyword) {
            $map['title'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('title', $title);
        }
       
        $count = $Tomrenqiprize->where($map)->count(); 
        $Page = new Page($count, 15);
        $show = $Page->show(); 
        $list = $Tomrenqiprize->where($map)->order(array('rq_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        
        
        $this->assign('list', $list); 
        $this->assign('page', $show); 
        $this->display(); 
    }
	 public function userindex($id=0) {
		 
        $Tomrenqiuser = D('Tom_renqi_user');
        import('ORG.Util.Page'); 
        $map = array('rq_id'=>$id);
        $keyword = $this->_param('keyword', 'htmlspecialchars');
        if ($keyword) {
            $map['title'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('title', $title);
        }
       
        $count = $Tomrenqiuser->where($map)->count(); 
        $Page = new Page($count, 15);
        $show = $Page->show(); 
        $list = $Tomrenqiuser->where($map)->order(array('id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        
        
        $this->assign('list', $list); 
        $this->assign('page', $show); 
        $this->display(); 
    }
	 public function huojiang($id=0) {
		 
        $Tomrenqiuser = D('Tom_renqi_huojiang');
		$user=
        import('ORG.Util.Page'); 
        $map = array('rq_id'=>$id);
        $keyword = $this->_param('keyword', 'htmlspecialchars');
        if ($keyword) {
            $map['title'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('title', $title);
        }
       
        $count = $Tomrenqiuser->where($map)->count(); 
        $Page = new Page($count, 15);
        $show = $Page->show(); 
        $list = $Tomrenqiuser->where($map)->order(array('id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        
        
        $this->assign('list', $list); 
        $this->assign('page', $show); 
        $this->display(); 
    }
}
 