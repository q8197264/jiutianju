<?php
class KanjiaAction extends CommonAction {
	 
  
 
    public function index() {
        $Tomweixinzl = D('Tom_kanjia');
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
			$this->error('您的营销额度已用完，您可以联系管理员或者通过购买和通过续费增加3次额度！',U('kanjia/index'));
		}
		$renqinum = D('Tom_kanjia')->where(array('shop_id'=>$this->shop_id))->count();
		if ($renqinum >0){
		$this->error('您当前已存在正在运营的砍价活动，不能再添加了。');
		}
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Tom_kanjia');
            if ($obj->add($data)) {
				$shopyx=$shopyx-1;
                D('Shop')->save(array('shop_id'=>$this->shop_id,'yx'=>$shopyx));
                $this->baoSuccess('添加成功', U('kanjia/index'));
            }
            $this->baoError('操作失败！');
        } else {
			
			$shop_id = $this->shop_id;
			$this->assign('shop_id', $shop_id);
			
            $this->display();
        }
    }

    private function createCheck() {
        $data = $this->checkFields($this->_post('data', false), array('id', 'shop_id','title','start_time', 'end_time','pic_url','base_price','goods_price', 'goods_num','dh_pwd','buy_url','add_msg','bk_info','info','goodinfo','content', 'share_logo', 'share_title', 'share_desc', 'must_gz',  'guanzu_desc', 'guanzu_url', 'add_time', 'paixu',  'audit','style_id','kj_type','template_id'));
        
        $data['shop_id'] = $this->shop_id;
         $data['template_id'] = 'default';
		  $data['kj_type'] = 1;
        $data['style_id'] = 1;
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
		$data['base_price'] = htmlspecialchars($data['base_price']);
        
		$data['thumb'] = serialize($thumb);
		$data['goods_price'] = htmlspecialchars($data['goods_price']);
        if (empty($data['base_price'])) {
            $this->baoError('原价不能为空');
        } 
		$data['goods_num']=htmlspecialchars($data['goods_num']);
		$data['dh_pwd']=htmlspecialchars($data['dh_pwd']);
		$data['buy_url']=htmlspecialchars($data['buy_url']);
		$data['add_msg'] = htmlspecialchars($data['add_msg']);
       if (empty($data['add_msg'])) {
            $this->baoError('报名参加信息不能为空');
        } 
		$data['bk_info'] = htmlspecialchars($data['bk_info']);
       if (empty($data['bk_info'])) {
            $this->baoError('砍价信息模板不能为空');
        } 
		$data['info'] = htmlspecialchars($data['info']);
       if (empty($data['info'])) {
            $this->baoError('帮砍价信息模板不能为空');
        } 

		$data['content'] = htmlspecialchars($data['content']);
       if (empty($data['content'])) {
            $this->baoError('活动规则不能为空');
        } 
        $data['goodinfo'] = SecurityEditorHtml($data['goodinfo']);
        if (empty($data['goodinfo'])) {
            $this->baoError('商品详细内容不能为空');
        } 
		$data['share_title'] = htmlspecialchars($data['share_title']);
        if (empty($data['share_title'])) {
            $this->baoError('分享标题不能为空');
        }$data['share_desc'] = htmlspecialchars($data['share_desc']);
        if (empty($data['share_desc'])) {
            $this->baoError('分享内容不能为空');
        }$data['guanzu_desc'] = htmlspecialchars($data['guanzu_desc']);
        
        $data['share_logo'] = htmlspecialchars($data['pic_url']);
		
        $data['must_gz']=0;
		$au=$this->_CONFIG['yx']['yx2_audit'];
        $data['audit'] = $au;
        $data['paixu'] = htmlspecialchars($data['paixu']);
        $data['add_time'] = NOW_TIME;
        
        
        return $data;
    }



    public function edit($id = 0) {
        if ($id = (int) $id) {
            $obj = D('Tom_kanjia');
            if (!$detail = $obj->find($id)) {
                $this->baoError('请选择要编辑的活动');
            }
            if ($this->isPost()) {
                $data = $this->editCheck();
                $data['id'] = $id;
                if (false !== $obj->save($data)) {
                    $this->baoSuccess('操作成功', U('kanjia/index'));
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
        $data = $this->checkFields($this->_post('data', false), array('id', 'shop_id','title','start_time', 'end_time','pic_url','base_price','goods_price', 'goods_num','dh_pwd','buy_url','add_msg','bk_info','info','goodinfo','content', 'share_logo', 'share_title', 'share_desc', 'must_gz',  'guanzu_desc', 'guanzu_url', 'add_time', 'paixu',  'audit','style_id','kj_type','template_id'));
        
         
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
		$data['base_price'] = htmlspecialchars($data['base_price']);
        
		$data['thumb'] = serialize($thumb);
		$data['goods_price'] = htmlspecialchars($data['goods_price']);
        if (empty($data['base_price'])) {
            $this->baoError('原价不能为空');
        } 
		$data['goods_num']=htmlspecialchars($data['goods_num']);
		$data['dh_pwd']=htmlspecialchars($data['dh_pwd']);
		$data['buy_url']=htmlspecialchars($data['buy_url']);
		$data['add_msg'] = htmlspecialchars($data['add_msg']);
       if (empty($data['add_msg'])) {
            $this->baoError('报名参加信息不能为空');
        } 
		$data['bk_info'] = htmlspecialchars($data['bk_info']);
       if (empty($data['bk_info'])) {
            $this->baoError('砍价信息模板不能为空');
        } 
		$data['info'] = htmlspecialchars($data['info']);
       if (empty($data['info'])) {
            $this->baoError('帮砍价信息模板不能为空');
        } 

		$data['content'] = htmlspecialchars($data['content']);
       if (empty($data['content'])) {
            $this->baoError('活动规则不能为空');
        } 
        $data['goodinfo'] = SecurityEditorHtml($data['goodinfo']);
        if (empty($data['goodinfo'])) {
            $this->baoError('商品详细内容不能为空');
        } 
		$data['share_title'] = htmlspecialchars($data['share_title']);
        if (empty($data['share_title'])) {
            $this->baoError('分享标题不能为空');
        }$data['share_desc'] = htmlspecialchars($data['share_desc']);
        if (empty($data['share_desc'])) {
            $this->baoError('分享内容不能为空');
        }$data['guanzu_desc'] = htmlspecialchars($data['guanzu_desc']);
        
        $data['share_logo'] = htmlspecialchars($data['pic_url']);
		
        
		
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
            $obj = D('Tom_kanjia_price');
            if ($kj_id = $obj->add($data)) {
                $this->baoSuccess('添加成功', U('kanjia/index'));
            }
            $this->baoError('操作失败！');
        } else {
            $this->display();
        }
        
    }
    public function creategifeCheck($id=0){
		
        $data = $this->checkFields($this->_post('data', false), array('kj_id','start_price','end_price','min_price','max_price','add_time'));
		$zuli = D('Tom_kanjia')->where(array('shop_id'=>$this->shop_id))->find();
        $data['kj_id'] =$zuli['id'];
        $data['start_price'] = htmlspecialchars($data['start_price']);
        if (empty($data['start_price'])) {
            $this->baoError('规则开始价格（小）不能为空');
        }
		$data['end_price'] = htmlspecialchars($data['end_price']);
        if (empty($data['end_price'])) {
            $this->baoError('规则开始价格（大）不能为空');
        }
		$data['min_price'] = htmlspecialchars($data['min_price']);
        if (empty($data['min_price'])) {
            $this->baoError('砍价随机最小价格不能为空');
        }
       $data['max_price'] = htmlspecialchars($data['max_price']);
        if (empty($data['max_price'])) {
            $this->baoError('砍价随机最大价格不能为空');
        }
		
       
		 $data['add_time'] = NOW_TIME;
        
        
        return $data;
    }
	public function editgife($id=0){
		
	  if ($id = (int) $id) {
            $obj = D('Tom_kanjia_price');
            if (!$detail = $obj->find($id)) {
                $this->baoError('请选择要编辑的规则');
            }
            if ($detail['id'] != $id) {
                $this->baoError('非法操作');
            }
            if ($this->isPost()) {
                $data = $this->editgifeCheck();
                $data['id'] = $id;
                if (false !== $obj->save($data)) {
                    $this->baoSuccess('保存规则成功', U('kanjia/index'));
                }
                $this->baoError('操作失败');
            } else {
                $this->assign('detail',$detail);
                $this->display();
            }
        } else {
            $this->baoError('请选择要编辑的规则');
        }
    }
	
    public function editgifeCheck(){
		
        $data = $this->checkFields($this->_post('data', false), array('kj_id','start_price','end_price','min_price','max_price','add_time'));
		
       $data['start_price'] = htmlspecialchars($data['start_price']);
        if (empty($data['start_price'])) {
            $this->baoError('规则开始价格（小）不能为空');
        }
		$data['end_price'] = htmlspecialchars($data['end_price']);
        if (empty($data['end_price'])) {
            $this->baoError('规则开始价格（大）不能为空');
        }
		$data['min_price'] = htmlspecialchars($data['min_price']);
        if (empty($data['min_price'])) {
            $this->baoError('砍价随机最小价格不能为空');
        }
       $data['max_price'] = htmlspecialchars($data['max_price']);
        if (empty($data['max_price'])) {
            $this->baoError('砍价随机最大价格不能为空');
        }
		
       
        
        return $data;
    }
	 public function gifeindex($id=0) {
		 
        $Tomrenzlprize = D('Tom_kanjia_price');
        import('ORG.Util.Page'); 
        $map = array('kj_id'=>$id);
        $keyword = $this->_param('keyword', 'htmlspecialchars');
        if ($keyword) {
            $map['title'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('title', $title);
        }
       
        $count = $Tomrenzlprize->where($map)->count(); 
        $Page = new Page($count, 15);
        $show = $Page->show(); 
        $list = $Tomrenzlprize->where($map)->order(array('kj_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        
        
        $this->assign('list', $list); 
        $this->assign('page', $show); 
        $this->display(); 
    }
	 public function userindex($id=0) {
		 
        $Tomrenqiuser = D('Tom_kanjia_user');
        import('ORG.Util.Page'); 
        $map = array('kj_id'=>$id);
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
 