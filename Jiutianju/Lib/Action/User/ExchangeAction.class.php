<?php

class ExchangeAction extends CommonAction {
	
	protected function _initialize() {
        parent::_initialize();
		if ($this->_CONFIG['operation']['jifen'] == 0) {
			$this->error('此功能已关闭');die;
		}
    }

	public function index() {
		$this->display();
	}

	public function exchangeloading() {
		$Integralexchange = D('Integralexchange');
		import('ORG.Util.Page'); 
		$map = array('user_id' => $this->uid);
		$count = $Integralexchange->where($map)->count();
		$Page = new Page($count, 25); 
		$show = $Page->show(); 
		$var = C('VAR_PAGE') ? C('VAR_PAGE') : 'p';
		$p = $_GET[$var];
		if ($Page->totalPages < $p) {
			die('0');
		}
		$list = $Integralexchange->where($map)->order(array('exchange_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$shop_ids = $good_ids = $addr_ids = array();
		foreach ($list as $val) {
			$shop_ids[$val['shop_id']] = $val['shop_id'];
			$good_ids[$val['goods_id']] = $val['goods_id'];
			$addr_ids[$val['addr_id']] = $val['addr_id'];
		}
		$this->assign('areas', D('Area')->fetchAll());
		$this->assign('business', D('Business')->fetchAll());
		$this->assign('shops', D('Shop')->itemsByIds($shop_ids));
		$this->assign('goods', D('Integralgoods')->itemsByIds($good_ids));
		$this->assign('addrs', D('Useraddr')->itemsByIds($addr_ids));
		$this->assign('list', $list); 
		$this->assign('page', $show);
		$this->display(); 
	}


}