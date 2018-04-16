<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

defined('THINK_PATH') or exit();

/**
 * 数据库方式Session驱动
 *    CREATE TABLE think_session (
 *      session_id varchar(255) NOT NULL,
 *      session_expire int(11) NOT NULL,
 *      session_data blob,
 *      UNIQUE KEY `session_id` (`session_id`)
 *    );
 * @category   Extend
 * @package  Extend
 * @subpackage  Driver.Session
 * @author    liu21st <liu21st@gmail.com>
 */
class SessionDb {

    /**
     * Session有效时间
     */
    protected $lifeTime = '';

    /**
     * session保存的数据库名
     */
    protected $sessionTable = '';

    /**
     * 数据库句柄
     */
    protected $hander = array();

    public function __construct()
    {
        if ( !extension_loaded('mysqli') ) {
            throw_exception(L('_NOT_SUPPERT_').':mysqli');
        }
    }

    /**
     * 打开Session 
     * @access public 
     * @param string $savePath 
     * @param mixed $sessName  
     */
    public function open($savePath, $sessName) {
        $this->lifeTime = C('SESSION_EXPIRE') ? C('SESSION_EXPIRE') : ini_get('session.gc_maxlifetime');
        $this->sessionTable = C('SESSION_TABLE') ? C('SESSION_TABLE') : C("DB_PREFIX") . "session";

        //分布式数据库
        $host = explode(',', C('DB_HOST'));
        $port = explode(',', C('DB_PORT'));
        $name = explode(',', C('DB_NAME'));
        $user = explode(',', C('DB_USER'));
        $pwd = explode(',', C('DB_PWD'));
        if (1 == C('DB_DEPLOY_TYPE')) {
            //读写分离
            if (C('DB_RW_SEPARATE')) {
                $w = floor(mt_rand(0, C('DB_MASTER_NUM') - 1));
                if (is_numeric(C('DB_SLAVE_NO'))) {//指定服务器读
                    $r = C('DB_SLAVE_NO');
                } else {
                    $r = floor(mt_rand(C('DB_MASTER_NUM'), count($host) - 1));
                }

                //主数据库链接
                $mysqli = $this->connect(
                        $host[$w] . (isset($port[$w]) ? ':' . $port[$w] : ':' . $port[0]),
                    isset($user[$w]) ? $user[$w] : $user[0],
                    isset($pwd[$w]) ? $pwd[$w] : $pwd[0],
                    isset($name[$w]) ? $name[$w] : $name[0]
                );
                if (!$mysqli)
                    return false;
                $this->hander[0] = $mysqli;

                //从数据库链接
                $mysqli = $this->connect(
                        $host[$r] . (isset($port[$r]) ? ':' . $port[$r] : ':' . $port[0]),
                    isset($user[$r]) ? $user[$r] : $user[0],
                    isset($pwd[$r]) ? $pwd[$r] : $pwd[0],
                    isset($name[$r]) ? $name[$r] : $name[0]
                );
                if (!$mysqli)
                    return false;
                $this->hander[1] = $mysqli;
                return true;
            }
        }
        //从数据库链接
        $r = floor(mt_rand(0, count($host) - 1));
        $mysqli = $this->connect(
                $host[$r] . (isset($port[$r]) ? ':' . $port[$r] : ':' . $port[0]),
            isset($user[$r]) ? $user[$r] : $user[0],
            isset($pwd[$r]) ? $pwd[$r] : $pwd[0],
            isset($name[$r]) ? $name[$r] : $name[0]
        );

        if (!$mysqli) return false;

        $this->hander = $mysqli;
        return true;
    }

    /**
     * 数据库句柄
     * @param      $host
     * @param      $username
     * @param      $password
     * @param      $dbname
     * @param null $port
     * @param null $socket
     *
     * @return mysqli
     */
    protected function connect($host, $username, $password, $dbname, $port=null, $socket=null)
    {
        $port = is_null($port) ? ini_get("mysqli.default_port") : $port;
        $socket = is_null($socket) ? ini_get('mysqli.default_socket') : $socket;
        $mysqli = new mysqli($host, $username, $password, $dbname, $port, $socket);
        if ($mysqli->connect_errno) {
            die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
        }
        // 设置数据库编码
        $mysqli->query("SET NAMES '".C('DB_CHARSET')."'");

        return $mysqli;
    }


    /**
     * 关闭Session 
     * @access public 
     */
    public function close() {
        if (is_array($this->hander)) {
            $this->gc($this->lifeTime);
            return (mysqli_close($this->hander[0]) && mysqli_close($this->hander[1]));
        }
        $this->gc($this->lifeTime);
        return mysqli_close($this->hander);
    }

    /**
     * 读取Session 
     * @access public 
     * @param string $sessID 
     */
    public function read($sessID) {
        $hander = is_array($this->hander) ? $this->hander[1] : $this->hander;
        $sql = <<<EOF
            SELECT 
                `session_data` AS data 
            FROM {$this->sessionTable} 
            WHERE 
                `session_id` = ? AND `session_expire` > ?
EOF;
        $stmt = $hander->prepare($sql);
        $stmt->bind_param('si', $sessID, time());
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        if ($res) {
            $row = $res->fetch_assoc();
            return urldecode($row['data']);
        }
        return "";
    }

    /**
     * 写入Session 
     * @access public 
     * @param string $sessID 
     * @param String $sessData  
     */
    public function write($sessID, $sessData)
    {
        if(empty($sessData)) return true;
        $hander = is_array($this->hander) ? $this->hander[0] : $this->hander;
        $expire = time() + $this->lifeTime;
        $sessData = urlencode($sessData);
        $sql = <<<EOF
            REPLACE INTO  {$this->sessionTable}
            (
                `session_id`, 
                `session_expire`, 
                `session_data`
            ) VALUES(
                ?,?,?
            )
EOF;

        $stmt = $hander->prepare($sql);
        $stmt->bind_param('sis', $sessID, $expire, $sessData);
        $stmt->execute();
        $stmt->close();
        if ($stmt->affected_rows)
            return true;
        return false;
    }

    /**
     * 删除Session 
     * @access public 
     * @param string $sessID 
     */
    public function destroy($sessID) {
        $hander = is_array($this->hander) ? $this->hander[0] : $this->hander;
        $sql = <<<EOF
            DELETE FROM `{$this->sessionTable}` WHERE `session_id` = ?
EOF;

        $stmt = $hander->prepare($sql);
        $stmt->bind_param('s', $sessID);
        $stmt->execute();
        if ($stmt->affected_rows)
            return true;
        return false;
       
    }

    /**
     * Session 垃圾回收
     * @access public 
     * @param string $sessMaxLifeTime 
     */
    public function gc($sessMaxLifeTime) {
        $rand = rand(1, 20);
        if ($rand == 8) {
            $hander = is_array($this->hander) ? $this->hander[0] : $this->hander;
            $sql = <<<EOF
                DELETE FROM `{$this->sessionTable}` WHERE `session_expire` < ?
EOF;
            $stmt = $hander->prepare($sql);
            $stmt->bind_param('i', time());
            $stmt->execute();

            return $stmt->affected_rows;
        }
        return false;
    }

    /**
     * 打开Session 
     * @access public 
     */
    public function execute() {
        ini_set('session.save_handler', 'user');
        session_set_save_handler(array(&$this, "open"), array(&$this, "close"), array(&$this, "read"), array(&$this, "write"), array(&$this, "destroy"), array(&$this, "gc"));
    }

}
