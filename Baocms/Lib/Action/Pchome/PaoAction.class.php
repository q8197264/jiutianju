<?php
class PaoAction extends CommonAction {
    protected  $sharecates = array();
    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        $Post = D('PaoRecords');
        import('ORG.Util.Pageabc'); // 导入分页类
        $map = array('record_status' => 2, 'is_close' => 0);
        $pao_category = D("PaoCategory");
        $cate_list = $pao_category->field("category_id,category_name")->where("category_status=1")->order("category_sortby asc,category_id desc")->select();
        $cid = (int) $this->_param('cid');
        if ($cid>0) {
            $map['category_id'] = $cid;
        }
        $this->assign('cat', $cat);
        if ($keyword = $this->_param('keyword', 'htmlspecialchars')) {
            $map['record_title'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
        $new_cate_list = array();
        if(!empty($cate_list)){
            foreach($cate_list as $kk => $vv){
                $new_cate_list[$vv['category_id']] = $vv['category_name'];
            }
        }
        $orderby = "is_fenpei asc,record_id desc";
        $cate_name = $new_cate_list[$cid];
        $count = $Post->where($map)->count(); // 查询满足要求的总记录数
        $count = $count>0?$count:0;
        $Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $Post->where($map)->order($orderby)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('cate_list', $cate_list);
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('counts',$count);
        $this->assign('cate_name',$cate_name);
        $this->assign('cid',$cid);
        $this->display(); // 输出模板
    }

    public function detail() {
        $post_id = (int) $this->_get('id');
        $detail = D('PaoRecords')->where("record_id='".$post_id."' and is_close=0")->find();
        if(empty($detail)) {
            $this->error('您查看的内容不存在！');
            die;
        }
        $pao_category = D("PaoCategory");
        $cate_list = $pao_category->field("category_id,category_name")->where("category_status=1")->order("category_sortby asc,category_id desc")->select();
        $uid = $this->uid;
        $this->assign('uid', $uid);
        $this->assign('detail', $detail);
        $this->assign('cate_list',$cate_list);
        $this->display(); // 输出模板
    }

    public function doqd(){
        $id = I("post.id");
        if($id>0){
            $detail = D('PaoRecords')->field("record_id")->where("record_id='".$id."' and is_close=0 and fabu_uid!='".$this->uid."' and jie_uid<1 and record_status=2 and is_finished=0")->find();
            if(!empty($detail)){
                $vo = D('PaoRecords')->where("record_id='".$id."'")->save(array('jie_uid'=>$this->uid,'fenpei_update'=>time()));
                if(false===$vo){
                    echo '0';
                    exit;
                }
                else{
                    echo '1';
                    exit;
                }
            }
            else{
                echo '-1';
                exit;
            }
        }
        else{
            echo '0';
            exit;
        }
    }

    
	public function Login(){
		
        if (empty($this->uid)) {
            $this->ajaxLogin();
        }
		
	}
}
