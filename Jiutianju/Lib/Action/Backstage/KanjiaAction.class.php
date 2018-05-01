<?php
class KanjiaAction extends CommonAction {
	 
  
 
    public function index() {
        $Tomrenqi = D('Tom_kanjia');
        import('ORG.Util.Page'); 
        $map = array('closed' => 0);
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
	
   public function audit($id=0 ) {
        $obj = D('Tom_kanjia');
        if (is_numeric($id) && ($id = (int) $id)) {
            if($obj->where(array('id' => $id))->save(array('audit' => 1))){
				$this->baoSuccess('审核成功！', U('kanjia/index'));
			}else{
				$this->baoError('审核1失败');
			}
        } else {
            $id = $this->_post('id', false);
            if (is_array($id)) {
                foreach ($id as $id) {
                    $obj->save(array('id' => $id, 'audit' => 1));
                }
                $this->baoSuccess('审核成功！', U('kanjia/index'));
            }
            $this->baoError('请选择要审核的攒人气');
        }
    }

      public function fh($id=0 ) {
        $obj = D('Tom_renqi_user');
        if (is_numeric($id) && ($id = (int) $id)) {
            if($obj->where(array('id' => $id))->save(array('status' => 1))){
				$this->baoSuccess('封号成功！', U('kanjia/index'));
			}else{
				$this->baoError('封号失败');
			}
        } else {
            $id = $this->_post('id', false);
            if (is_array($id)) {
                foreach ($id as $id) {
                    $obj->save(array('id' => $id, 'status' => 1));
                }
                $this->baoSuccess('封号成功！', U('kanjia/index'));
            }
            $this->baoError('请选择要封号的用户');
        }
    }
    public function jf($id=0 ) {
        $obj = D('Tom_kanjia_user');
        if (is_numeric($id) && ($id = (int) $id)) {
            if($obj->where(array('id' => $id))->save(array('status' => 0))){
				$this->baoSuccess('解封成功！', U('kanjia/index'));
			}else{
				$this->baoError('解封失败');
			}
        } else {
            $id = $this->_post('id', false);
            if (is_array($id)) {
                foreach ($id as $id) {
                    $obj->save(array('id' => $id, 'status' => 0));
                }
                $this->baoSuccess('解封成功！', U('kanjia/index'));
            }
            $this->baoError('请选择要解封的用户');
        }
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
	    public function delete($id = 0) {
         $obj = D('Tom_renqi');
        if (is_numeric($id) && ($id = (int) $id)) {
            if($obj->where(array('id' => $id))->save(array('closed' => 1))){
				$this->baoSuccess('删除成功！', U('renqi/index'));
			}else{
				$this->baoError('删除失败');
			}
        } else {
            $id = $this->_post('id', false);
            if (is_array($id)) {
                foreach ($id as $id) {
                    $obj->save(array('id' => $id, 'closed' => 1));
                }
                $this->baoSuccess('删除成功！', U('renqi/index'));
            }
            $this->baoError('请选择要删除的攒人气项目');
        }
    }

    public function create() {
		
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Tom_renqi');
            if ($obj->add($data)) {
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
        $data = $this->checkFields($this->_post('data', false), $this->create_fields);
        
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
        $data['must_gz'] = 0;
       $data['must_gz_help'] = 0;
       
		$data['audit'] = 0;
        $data['paixu'] = htmlspecialchars($data['paixu']);
        $data['add_time'] = NOW_TIME;
        
        $data['create_ip'] = get_client_ip();
        return $data;
    }



    public function edit($activity_id = 0) {
        if ($activity_id = (int) $activity_id) {
            $obj = D('Activity');
            if (!$detail = $obj->find($activity_id)) {
                $this->baoError('请选择要编辑的活动');
            }
            if ($this->isPost()) {
                $data = $this->editCheck();
                $data['activity_id'] = $activity_id;
                if (false !== $obj->save($data)) {
                    $this->baoSuccess('操作成功', U('activity/index'));
                }
                $this->baoError('操作失败');
            } else {
                $thumb = unserialize($detail['thumb']);
                $tuan = D('Tuan')->where(array('shop_id'=>$detail['shop_id']))->select();
                $this->assign('tuan',$tuan);
                $this->assign('thumb', $thumb);
                $this->assign('users', D('Users')->find($detail['user_id']));
                $this->assign('cates', D('Activitycate')->fetchAll());
			    $this->assign('shops', D('Shop')->find($detail['shop_id']));
                $this->assign('detail', $detail);
                $this->display();
            }
        } else {
            $this->baoError('请选择要编辑的活动');
        }
    }

    private function editCheck() {
        $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
        $data['cate_id'] = (int) $data['cate_id'];
        if (empty($data['cate_id'])) {
            $this->baoError('类型ID不能为空');
        }
        $data['shop_id'] = $this->shop_id;
        $data['tuan_id'] = (int) $data['tuan_id'];
        $shop = D('Shop')->find($this->shop_id);
        $data['city_id'] = $shop['city_id'];
        $data['area_id'] = $shop['area_id'];
        $data['business_id'] = $shop['business_id'];
        $data['title'] = htmlspecialchars($data['title']);
        if (empty($data['title'])) {
            $this->baoError('活动标题不能为空');
        } $data['intro'] = htmlspecialchars($data['intro']);
        if (empty($data['intro'])) {
            $this->baoError('活动简介不能为空');
        } $data['photo'] = htmlspecialchars($data['photo']);
        if (!isImage($data['photo'])) {
            $this->baoError('请上传正确的图片');
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
        $data['details'] = SecurityEditorHtml($data['details']);
        if (empty($data['details'])) {
            $this->baoError('活动内容不能为空');
        } $data['price'] = htmlspecialchars($data['price']);
        if (empty($data['price'])) {
            $this->baoError('价格不能为空');
        } $data['bg_date'] = htmlspecialchars($data['bg_date']);
        if (empty($data['bg_date'])) {
            $this->baoError('活动开始时间不能为空');
        } $data['end_date'] = htmlspecialchars($data['end_date']);
        if (empty($data['end_date'])) {
            $this->baoError('活动结束时间不能为空');
        }$data['sign_end'] = htmlspecialchars($data['sign_end']);
        if (empty($data['sign_end'])) {
            $this->baoError('报名截止时间不能为空');
        }$data['time'] = htmlspecialchars($data['time']);
        if (empty($data['time'])) {
            $this->baoError('活动具体时间不能为空');
        } $data['addr'] = htmlspecialchars($data['addr']);
        if (empty($data['addr'])) {
            $this->baoError('活动地址不能为空');
        }
        if ($words = D('Sensitive')->checkWords($data['details'])) {
            $this->baoError('活动内容含有敏感词：' . $words);
        }
        if ($words = D('Sensitive')->checkWords($data['title'])) {
            $this->baoError('活动标题含有敏感词：' . $words);
        }
        if ($words = D('Sensitive')->checkWords($data['intro'])) {
            $this->baoError('活动简介含有敏感词：' . $words);
        }
		 $data['orderby'] = (int) $data['orderby'];
        return $data;
    }

   

}
 