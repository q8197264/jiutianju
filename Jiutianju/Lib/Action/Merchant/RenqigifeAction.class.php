<?php
class RenqiAction extends CommonAction {
	 
      public function gifeindex() {
        $Tomrenqiprize = D('Tom_renqi_prize');
        import('ORG.Util.Page'); 
        $map = array('rq_id'=>$this->id);
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
	
  

    
public function creategife(){
	 
        if ($this->isPost()) {
            $data = $this->checkCreategife();
            $obj = D('Tom_renqi_prize');
            if ($obj->add($data)) {
                $this->fengmiMsg('奖品增加成功', U('renqi/index'));
            }
            $this->fengmiMsg('操作失败！');
        } else {
            $this->display();
        }
    }
    public function checkCreategife(){
        $data = $this->checkFields($this->_post('data', false), array('rq_id','prize_name','prize_desc','prize_pwd','prize_rq_num','prize_num','add_time'));
        $data['rq_id'] = $this->id;
        $data['prize_name'] = htmlspecialchars($data['prize_name']);
        if (empty($data['prize_name'])) {
            $this->fengmiMsg('奖品名不能为空');
        }
        $data['prize_pic_url'] = htmlspecialchars($data['prize_pic_url']);
        if (empty($data['prize_pic_url'])) {
            $this->fengmiMsg('奖品图片不能为空');
        }
       
        $data['prize_desc'] = htmlspecialchars($data['prize_desc']);
        if (empty($data['prize_desc'])) {
            $this->fengmiMsg('奖品描述内容不能为空');
        }
       $data['prize_pwd'] = htmlspecialchars($data['prize_pwd']);
        if (empty($data['prize_pwd'])) {
            $this->fengmiMsg('兑奖密码不能为空');
        }
       $data['prize_rq_num'] = htmlspecialchars($data['prize_rq_num']);
        if (empty($data['prize_rq_num'])) {
            $this->fengmiMsg('奖品人气数不能为空');
        }
		$data['prize_num'] = htmlspecialchars($data['prize_num']);
        if (empty($data['prize_num'])) {
            $this->fengmiMsg('奖品数不能为空');
        }
       
        $data['add_time'] = NOW_TIME;
        
        return $data;
    }
	
}
 