<?php
class LoginAction extends CommonAction{
    public function index(){
        $this->display();
    }
    public function loging(){
        $yzm = $this->_post('yzm');
        if (strtolower($yzm) != strtolower(session('verify'))) {
            session('verify', null);
            $this->baoError('验证码不正确!', 2000, true);
        }
        $username = $this->_post('username', 'trim');
        $password = $this->_post('password', 'trim,md5');
        $adminObj = D('Admin');
        $adminlog = D('Admin_log');
        $admin = $adminObj->getAdminByUsername($username);
		
        if (empty($admin)) {
           $last_ip = get_client_ip();
          $adminlog->Add(array(
          
          'username' => $username,
			'last_time' => NOW_TIME, 
			'last_ip' => $last_ip, 
            'audit' => 0,
			
		));
            session('verify', null);
            $this->baoError('账户不能为空', 2000, true);
        }
		
		if ($admin['closed'] == 1) {//关闭账户
            session('verify', null);
            $this->baoError('该账户已经被禁用!', 2000, true);
        }
        if ($admin['role_id'] == 2) {//类型错误
            session('verify', null);
            $this->baoError('分站管理员请登录分站后台', 2000, true);
        }
		
     
        if ($admin['password'] != $password) {
           $last_ip = get_client_ip();
          $adminlog->Add(array(
          
          'username' => $username,
			'last_time' => NOW_TIME, 
			'last_ip' => $last_ip, 
            'audit' => 0,
			
		));
            
        }
       
	   //判断IP
        $last_ip = get_client_ip();
		$t=time();
 		$time = date("Y-m-d H:i:s",$t);  
        if (!empty($ip)) {
            if ($admin['last_ip'] != $last_ip) {
                $adminObj->where(array('admin_id' => $admin['admin_id']))->save(array('is_ip' => 1));
				D('Sms')->sms_admin_login_admin($admin['mobile'],$admin['username'],$time);
            }
        }
        $adminObj->where(array('user_id' => $admin['user_id']))->save(array(
			'last_time' => NOW_TIME, 
			'last_ip' => $last_ip, 
			'is_admin_lock' => 0, 
			'lock_admin_mum' => 0, 
			'is_admin_lock_time' => ''
		));
      
		$adminlog->Add(array(
          
          'username' => $username,
			'last_time' => NOW_TIME, 
			'last_ip' => $last_ip, 
			'audit' => 1,
		));
        session('admin', $admin);
        $this->baoSuccess('登录成功！', U('index/index'));
    }
    public function logout(){
        $admin_ids = $this->_admin = session('admin');
		
         D('Admin')->where(array('user_id' => $admin_ids['user_id']))->save(array(
			'is_ip' => 0, 
			'is_lock' => 0, 
			'lock_num' => 0, 
			'is_lock_time' => ''
		));
		
        session('admin', null);
        $this->success('退出成功', U('login/index'));
    }
	
    public function verify(){
        import('ORG.Util.Image');
        Image::buildImageVerify(5, 2, 'png', 60, 30);
    }

	
    public function close2(){
        if (IS_AJAX) {
            $admin_id = $_POST['admin_id'];
            D('Admin')->where(array('admin_id' => $admin_id))->save(array('is_ip' => 00));
        }
    }
}