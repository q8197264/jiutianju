<?php
class PaoAction extends CommonAction{
    public function set(){
        if ($this->isPost()) {
            $data = I("post.");//$this->_post('data', false);
            D('PaoSetting')->where("setting_id=1")->save(array('setting_value' => $data['setting_value'], 'setting_update' => time()));
            $this->baoSuccess('设置成功', U('pao/set'));
        } else {
            $pao = D("PaoSetting");
            $info = $pao->field("setting_value")->where("setting_id=1")->find();
            $this->assign('info',$info);
            $this->display();
        }
    }

    public function category(){
        $paocategory = D('PaoCategory');
        import('ORG.Util.Page');
        // 导入分页类
        $map = array();
        $keyword = $this->_param('keyword', 'htmlspecialchars');
        if ($keyword) {
            $map['category_name'] = array('LIKE', '%' . $keyword . '%');
        }
        $count = $paocategory->where($map)->count();
        // 查询满足要求的总记录数
        $Page = new Page($count, 25);
        // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();
        // 分页显示输出
        $list = $paocategory->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        //数据查询结束
        $this->assign('keyword', $keyword);
        $this->assign('list', $list);
        // 赋值数据集www.hatudou.com  二开开发qq  120585022
        $this->assign('page', $show);
        // 赋值分页输出
        $this->display();
        // 输出模板
    }

    public function addcategory(){
        if ($this->isPost()) {
            $obj = D('PaoCategory');
            $data = I("post.");
            $time = time();
            $data['category_add'] = $time;
            $data['category_update'] = $time;
            if ($obj->add($data)) {
                $obj->cleanCache();
                $this->baoSuccess('添加成功', U('pao/category'));
            }
            $this->baoError('操作失败！');
        } else {
            $this->assign('detail',array());
            $this->display();
        }
    }

    public function editcategory(){
        $category_id = I("get.category_id");
        if ($category_id>0) {
            $obj = D('PaoCategory');
            $detail = $obj->find($category_id);
            if (!($detail)) {
                $this->baoError('请选择要编辑的分类管理');
            }
            if ($this->isPost()) {
                $data = I("post.");
                $time = time();
                $map['category_id'] = $category_id;
                $data['category_update'] = $time;
                if (false !== $obj->where($map)->save($data)) {
                    $obj->cleanCache();
                    $this->baoSuccess('操作成功', U('pao/category'));
                }
                $this->baoError('操作失败');
            } else {
                $this->assign('detail', $detail);
                $this->display();
            }
        } else {
            $this->baoError('请选择要编辑的分类管理');
        }
    }

    public function deletecategory(){
        $category_id = I('get.category_id');
        if (is_numeric($category_id)) {
            $obj = D('PaoCategory');
            $obj->where("category_id='".$category_id."'")->delete();
            $obj->cleanCache();
            $this->baoSuccess('删除成功！', U('pao/category'));
        } else {
            $category_id = $this->_post('category_id', false);
            if (is_array($category_id)) {
                $obj = D('PaoCategory');
                foreach ($category_id as $id) {
                    $obj->where("category_id='".$id."'")->delete();
                }
                $obj->cleanCache();
                $this->baoSuccess('删除成功！', U('pao/category'));
            }
            $this->baoError('请选择要删除的分类管理');
        }
    }

    public function records(){
        $paorecords = D('PaoRecords');
        import('ORG.Util.Page');
        // 导入分页类
        $map = array();
        $map['is_close'] = 0;
        $keyword = $this->_param('keyword', 'htmlspecialchars');
        if ($keyword) {
            $map['record_title'] = array('LIKE', '%' . $keyword . '%');
        }
        $record_status = $this->_param('record_status', false);
        if (!empty($record_status)) {
            $map['record_status'] = $record_status;
        }
        $is_finished = $this->_param('is_finished', false);
        if (!empty($is_finished)) {
            $map['is_finished'] = $is_finished-1;
        }
        $count = $paorecords->where($map)->count();
        // 查询满足要求的总记录数
        $Page = new Page($count, 25);
        // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();
        // 分页显示输出
        $list = $paorecords->field("record_id,record_title,category_id,record_price,fabu_uid,jie_uid,record_status,is_finished,fenpei_update")->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
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
        //数据查询结束
        $this->assign('record_status', $record_status);
        $this->assign('is_finished', $is_finished);
        $this->assign('keyword', $keyword);
        $this->assign('list', $list);
        $this->assign('categorylist', $categorylist);
        // 赋值数据集www.hatudou.com  二开开发qq  120585022
        $this->assign('page', $show);
        // 赋值分页输出
        $this->display();
        // 输出模板
    }

    public function deleterecord(){
        $record_id = I('get.record_id');
        if (is_numeric($record_id)) {
            $obj = D('PaoRecords');
            $obj->where("record_id='".$record_id."'")->save(array('is_close' => 1,'fenpei_update'=>time()));
            $obj->cleanCache();
            $this->baoSuccess('删除成功！', U('pao/records'));
        }
    }

    public function viewrecord(){
        $record_id = I("get.record_id");
        if ($record_id>0) {
            $obj = D('PaoRecords');
            $detail = $obj->where("record_id='".$record_id."'")->find();
            if (!($detail)) {
                $this->baoError('请选择要查看的记录管理');
            }
            //获取发表者和跑腿者
            $users = D("Users");
            $fabuzhe = $users->field("account,face,nickname")->where("user_id='".$detail['fabu_uid']."'")->find();
            $paozhe = $users->field("account,face,nickname")->where("user_id='".$detail['jie_uid']."'")->find();
            $pao_category = D("PaoCategory");
            $categorylist = $pao_category->field("category_id,category_name")->where("category_status=1")->select();
            $this->assign('detail', $detail);
            $this->assign('fabuzhe', $fabuzhe);
            $this->assign('paozhe', $paozhe);
            $this->assign('categorylist', $categorylist);
            $this->display();
        } else {
            $this->baoError('请选择要查看的记录管理');
        }
    }
}