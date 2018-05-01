<?php
class ZuliAction extends CommonAction {
	 
  
 
    public function index() {
        $Tomweixinzl = D('Tom_weixin_zl');
        import('ORG.Util.Page'); 
        $map = array('shop_id'=>$this->shop_id);
        $keyword = $this->_param('keyword', 'htmlspecialchars');
        if ($keyword) {
            $map['title'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('title', $title);
        }
       
        $count = $Tomweixinzl->where($map)->count(); 
        $Page = new Page($count, 15);
        $show = $Page->show(); 
        $list = $Tomweixinzl->where($map)->order(array('id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        
        
        $this->assign('list', $list); 
        $this->assign('page', $show); 
        $this->display(); 
    }
	
  

    public function create() {
		$shop=D('Shop')->where(array('shop_id'=>$this->shop_id))->find();
		$shopyx=$shop['yx'];
		if ($shopyx < 1){
			$this->error('您的营销额度已用完，您可以联系管理员或者通过购买和通过续费增加3次额度！',U('zuli/index'));
		}
		$renqinum = D('Tom_weixin_zl')->where(array('shop_id'=>$this->shop_id))->count();
		if ($renqinum >0){
		$this->error('您当前已存在正在运营的助力活动，不能再添加了。');
		}
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Tom_weixin_zl');
            if ($obj->add($data)) {
				$shopyx=$shopyx-1;
                D('Shop')->save(array('shop_id'=>$this->shop_id,'yx'=>$shopyx));
                $this->baoSuccess('添加成功', U('zuli/index'));
            }
            $this->baoError('操作失败！');
        } else {
			
			$shop_id = $this->shop_id;
			$this->assign('shop_id', $shop_id);
			
            $this->display();
        }
    }

    private function createCheck() {
        $data = $this->checkFields($this->_post('data', false), array('id', 'shop_id','title','start_time', 'end_time','pic_url', 'guizhe','order_type','content', 'share_logo', 'share_title', 'share_desc', 'must_gz',  'guanzu_desc', 'guanzu_url', 'add_time', 'paixu',  'audit'));
        
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
		$data['order_type']=htmlspecialchars($data['order_type']);
		$data['guizhe'] = htmlspecialchars($data['guizhe']);
       if (empty($data['guizhe'])) {
            $this->baoError('活动规则不能为空');
        } 
        $data['content'] = SecurityEditorHtml($data['content']);
        if (empty($data['content'])) {
            $this->baoError('活动内容不能为空');
        } 
		$data['share_title'] = htmlspecialchars($data['share_title']);
        if (empty($data['share_title'])) {
            $this->baoError('分享标题不能为空');
        }$data['share_desc'] = htmlspecialchars($data['share_desc']);
        if (empty($data['share_desc'])) {
            $this->baoError('分享内容不能为空');
        }$data['guanzu_desc'] = htmlspecialchars($data['guanzu_desc']);
        $data['guanzu_url'] = htmlspecialchars($data['guanzu_url']);
        $data['share_logo'] = htmlspecialchars($data['pic_url']);
		
        $data['must_gz']=0;
		$au=$this->_CONFIG['yx']['yx1_audit'];
        $data['audit'] = $au;
        $data['paixu'] = htmlspecialchars($data['paixu']);
        $data['add_time'] = NOW_TIME;
        
        
        return $data;
    }



    public function edit($id = 0) {
        if ($id = (int) $id) {
            $obj = D('Tom_weixin_zl');
            if (!$detail = $obj->find($id)) {
                $this->baoError('请选择要编辑的活动');
            }
            if ($this->isPost()) {
                $data = $this->editCheck();
                $data['id'] = $id;
                if (false !== $obj->save($data)) {
                    $this->baoSuccess('操作成功', U('zuli/index'));
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
         $data = $this->checkFields($this->_post('data', false), array('id', 'shop_id','title','start_time', 'end_time','pic_url', 'guizhe','order_type','content', 'share_logo', 'share_title', 'share_desc', 'must_gz',  'guanzu_desc', 'guanzu_url', 'add_time', 'paixu',  'audit'));
       
        
        $data['shop_id'] = $this->shop_id;
        
        $data['title'] = htmlspecialchars($data['title']);
        if (empty($data['title'])) {
            $this->baoError('活动标题不能为空');
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
		$data['order_type']=htmlspecialchars($data['order_type']);
		$data['guizhe'] = htmlspecialchars($data['guizhe']);
       if (empty($data['guizhe'])) {
            $this->baoError('活动规则不能为空');
        } 
        $data['content'] = SecurityEditorHtml($data['content']);
        if (empty($data['content'])) {
            $this->baoError('活动内容不能为空');
        } 
		$data['share_title'] = htmlspecialchars($data['share_title']);
        if (empty($data['share_title'])) {
            $this->baoError('分享标题不能为空');
        }$data['share_desc'] = htmlspecialchars($data['share_desc']);
        if (empty($data['share_desc'])) {
            $this->baoError('分享内容不能为空');
        }
        $data['paixu'] = htmlspecialchars($data['paixu']);
        
       
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
            $obj = D('Tom_weixin_zl_prize');
            if ($activity_id = $obj->add($data)) {
                $this->baoSuccess('添加成功', U('zuli/index',array('id'=>$id)));
            }
            $this->baoError('操作失败！');
        } else {
            $this->display();
        }
        
    }
    public function creategifeCheck($id=0){
		
        $data = $this->checkFields($this->_post('data', false), array('activity_id','prize_title','prize_desc','prize_pic','prize_desc','prize_start_time','prize_end_time','prize_num','paixu'));
		$zuli = D('Tom_weixin_zl')->where(array('shop_id'=>$this->shop_id))->find();
        $data['activity_id'] =$zuli['id'];
        $data['prize_title'] = htmlspecialchars($data['prize_title']);
        if (empty($data['prize_title'])) {
            $this->baoError('奖项名不能为空');
        }
		$data['prize_desc'] = htmlspecialchars($data['prize_desc']);
        if (empty($data['prize_desc'])) {
            $this->baoError('奖品名不能为空');
        }
		$data['prize_num'] = htmlspecialchars($data['prize_num']);
        if (empty($data['prize_num'])) {
            $this->baoError('奖品数不能为空');
        }
       $data['prize_start_time'] = htmlspecialchars(strtotime($data['prize_start_time']));
        if (empty($data['prize_start_time'])) {
            $this->baoError('活动开始时间不能为空');
        }
		$data['prize_end_time'] = htmlspecialchars(strtotime($data['prize_end_time']));
        if (empty($data['prize_end_time'])) {
            $this->baoError('活动结束时间不能为空');
        }
       
		 $data['paixu'] = htmlspecialchars($data['paixu']);
        
        
        return $data;
    }
	public function editgife($id=0){
	 if ($id = (int) $id) {
            $obj = D('Tom_weixin_zl_prize');
            if (!$detail = $obj->find($id)) {
                $this->baoError('请选择要编辑的奖品0');
            }
            if ($detail['id'] != $id) {
                $this->baoError('非法操作');
            }
            if ($this->isPost()) {
                $data = $this->editgifeCheck();
                $data['id'] = $id;
                if (false !== $obj->save($data)) {
                    $this->baoSuccess('保存成功', U('zuli/index'));
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
		
        $data = $this->checkFields($this->_post('data', false), array('activity_id','prize_title','prize_desc','prize_pic','prize_desc','prize_start_time','prize_end_time','prize_num','paixu'));
		
        $data['prize_title'] = htmlspecialchars($data['prize_title']);
        if (empty($data['prize_title'])) {
            $this->baoError('奖项名不能为空');
        }
		$data['prize_desc'] = htmlspecialchars($data['prize_desc']);
        if (empty($data['prize_desc'])) {
            $this->baoError('奖品名不能为空');
        }
		$data['prize_num'] = htmlspecialchars($data['prize_num']);
        if (empty($data['prize_num'])) {
            $this->baoError('奖品数不能为空');
        }
       $data['prize_start_time'] = htmlspecialchars(strtotime($data['prize_start_time']));
        if (empty($data['prize_start_time'])) {
            $this->baoError('活动开始时间不能为空');
        }
		$data['prize_end_time'] = htmlspecialchars(strtotime($data['prize_end_time']));
        if (empty($data['prize_end_time'])) {
            $this->baoError('活动结束时间不能为空');
        }
       
        return $data;
    }
	 public function gifeindex($id=0) {
		 
        $Tomrenzlprize = D('Tom_weixin_zl_prize');
        import('ORG.Util.Page'); 
        $map = array('activity_id'=>$id);
        $keyword = $this->_param('keyword', 'htmlspecialchars');
        if ($keyword) {
            $map['title'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('title', $title);
        }
       
        $count = $Tomrenzlprize->where($map)->count(); 
        $Page = new Page($count, 15);
        $show = $Page->show(); 
        $list = $Tomrenzlprize->where($map)->order(array('activity_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        
        
        $this->assign('list', $list); 
        $this->assign('page', $show); 
        $this->display(); 
    }
	 public function userindex($id=0) {
		 
        $Tomrenqiuser = D('Tom_weixin_zl_user');
        import('ORG.Util.Page'); 
        $map = array('activity_id'=>$id);
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
 