<?php
/*
[Discuz!] Tools (C)2001-2008 Comsenz Inc.
This is NOT a freeware, use is subject to license terms

$Id: uctools.php 1193 2010-01-20 09:35:41Z songlixin $
*/

/**********************	���������� ��ʼ*******************************/
$tool_password = '11'; // ������� ��������һ�����߰��ĸ�ǿ�����룬����Ϊ�գ��������
/**********************	���������� ����*******************************/
error_reporting(E_ERROR | E_PARSE);	//E_ERROR | E_WARNING | E_PARSE | E_ALL
@set_time_limit(0);
define('TOOLS_ROOT', dirname(__FILE__)."/");
define('VERSION', '2009');
define('Release','100120');
$functionall = array(
	array('all', 'all_repair', '�����޸����ݿ�', '���������ݱ���м���޸�������'),
	array('all', 'all_runquery', '��������(SQL)', '������������SQL��䣬�����á�'),
	array('all', 'all_restore', '�ָ����ݿⱸ��', '�ָ���̳���ݱ��ݡ�'),
	array('all', 'all_setadmin', '���ô�ʼ������', '���� UCenter �Ĵ�ʼ������'),
);
$toolbar = array(
	array('phpinfo','INFO'),
	array('datago','ת��'),
	array('all_logout','�˳�'),	
);
//��ʼ��
$plustitle = '';
$lockfile = '';
//��ʱ�ļ����õ�Ŀ¼��getplace()����������
$docdir = '';
$action = '';
$target_fsockopen = '0'; 
$alertmsg = ' onclick="alert(\'���ȷ����ʼ����,������Ҫһ��ʱ��,���Ժ�\');"';
foreach(array('_COOKIE', '_POST', '_GET') as $_request) { 
	foreach($$_request as $_key => $_value) {
		($_key{0} != '_' && $_key != 'tool_password' && $_key != 'lockfile') && $$_key = taddslashes($_value);
	}
}
$whereis = getplace();

require_once $cfgfile;

if($whereis && !in_array($whereis, array('is_uc'))) {
	$alertmsg = '';
	errorpage('<ul><li>�����䣨UCenterר�ð棩������� UCenter �ĸ�Ŀ¼�²�������ʹ�á�</li><li>�����ȷʵ��������������Ŀ¼�£��������������������ļ���config���Ŀɶ�дȨ���Ƿ���ȷ</li>');
}
if(@file_exists($lockfile)) { 
	$alertmsg = '';
	errorpage("<h6>�����䣨UCenterר�ð棩�ѹرգ����迪��ֻҪͨ�� FTP ɾ�� $lockfile �ļ����ɣ� </h6>");
} elseif($tool_password == '') {
	$alertmsg = '';
	errorpage('<h6>�����䣨UCenterר�ð棩����Ĭ��Ϊ�գ���һ��ʹ��ǰ�����޸ı��ļ���$tool_password�������룡</h6>');
}
if($action == 'login') {
	setcookie('toolpassword',md5($toolpassword), 0);
	echo '<meta http-equiv="refresh" content="2 url=?">';
	errorpage("<h6>���Եȣ������¼�У�</h6>");
}
if(isset($toolpassword)) {
	if($toolpassword != md5($tool_password)) {
		$alertmsg = '';	
		errorpage("login");
	}
} else {
	$alertmsg = '';
	errorpage("login");
}
getdbcfg();
$mysql = mysql_connect($dbhost, $dbuser, $dbpw);
mysql_select_db($dbname);
$my_version = mysql_get_server_info();
if($my_version > '4.1') {
	$serverset = $dbcharset ? 'character_set_connection='.$dbcharset.', character_set_results='.$dbcharset.', character_set_client=binary' : '';
	$serverset .= $my_version > '5.0.1' ? ((empty($serverset))? '' : ',').'sql_mode=\'\'' : '';
	$serverset && mysql_query("SET $serverset");
}
//���̿�ʼ
if($action == 'all_repair') {
	$counttables = $oktables = $errortables = $rapirtables = 0;
	$doc = $docdir.'/repaireport.txt';
	if($check) {
		$tables = mysql_query("SHOW TABLES");
		if($iterations) {
			$iterations --;
		}
		while($table = mysql_fetch_row($tables)) {
				$counttables += 1;
				$answer = checktable($table[0],$iterations,$doc);
		}
		if($simple) {
			htmlheader();
			echo '<h4>����޸����ݿ�</h4>
			    <h5>�����:</h5>
					<table>
						<tr><th>����(��)</th><th>������(��)</th><th>�޸��ı�(��)</th><th>����(��)</th></tr>
						<tr><td>'.$counttables.'</td><td>'.$oktables.'</td><td>'.$rapirtables.'</td><td>'.$errortables.'</td></tr>
					</table>
				<p>�����û�д�����뷵�ع����䣨UCenterר�ð棩��ҳ��֮������޸�</p>
				<p><b><a href="uctools.php?action=all_repair">�����޸�</a>&nbsp;&nbsp;&nbsp;&nbsp;<b><a href="'.$doc.'">�޸�����</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="uctools.php">������ҳ</a></b></p>
				</td></tr></table>';
			specialdiv();
		}
	} else {
		htmlheader();
		@unlink($doc);
		echo "<h4>����޸����ݿ�</h4>
		<div class='specialdiv'>
				������ʾ��
				<ul>
				<li>������ͨ������ķ�ʽ�޸��Ѿ��𻵵����ݿ⡣����������ĵȴ��޸������</li>
				<li>����������޸����������ݿ���󣬵��޷���֤�����޸����е����ݿ����(��Ҫ MySQL 3.23+)</li>
				</ul>
				</div>
				<h5>������</h5>
				<ul>
				<li><a href=\"?action=all_repair&check=1&simple=1\">��鲢�����޸����ݿ�1��</a>
				<li><a href=\"?action=all_repair&check=1&iterations=5&simple=1\">��鲢�����޸����ݿ�5��</a> (��Ϊ���ݿ��д��ϵ������ʱ��Ҫ���޸����β�����ȫ�޸��ɹ�)
				</ul>";
		specialdiv();
	}
	htmlfooter();
} elseif($action == 'all_restore') {//�������ݿⱸ��
	ob_implicit_flush();
	$backdirarray = array( //��ͬ�ĳ����ű����ļ���Ŀ¼�ǲ�ͬ��
						'is_dz' => 'forumdata',
						'is_uc' => 'data/backup',
						'is_uch' => 'data',
						'is_ss' => 'data'
	);
	if(!get_cfg_var('register_globals')) {
		@extract($HTTP_GET_VARS);
	}
	$sqldump = '';
	htmlheader();
	?><h4>���ݿ�ָ�ʵ�ù��� </h4><?php
	echo "<div class=\"specialdiv\">������ʾ��<ul>
		<li>ֻ�ָܻ�����ڷ�����(Զ�̻򱾵�)�ϵ������ļ�,����������ݲ��ڷ�������,���� FTP �ϴ�</li>
		<li>�����ļ�����Ϊ Discuz! ������ʽ,��������Ӧ����ʹ PHP �ܹ���ȡ</li>
		<li>�뾡��ѡ�����������ʱ�β���,�Ա��ⳬʱ.����򳤾�(���� 10 ����)����Ӧ,��ˢ��</li></ul></div>";
	if($file) {
		if(!mysql_select_db($dbname)) {
			mysql_query("CREATE DATABASE $dbname;");
		}
		if(strtolower(substr($file, 0, 7)) == "http://") {
			echo "��Զ�����ݿ�ָ����� - ��ȡԶ������:<br><br>";
			echo "��Զ�̷�������ȡ�ļ� ... ";
			$sqldump = @fread($fp, 99999999);
			@fclose($fp);
			if($sqldump) {
				echo "�ɹ�<br><br>";
			} elseif(!$multivol) {
				cexit("ʧ��<br><br><b>�޷��ָ�����</b>");
			}
		} else {
			echo "<div class=\"specialtext\">�ӱ��ػָ����� - ��������ļ�:<br><br>";
			if(file_exists($file)) {
				echo "�����ļ� $file ���ڼ�� ... �ɹ�<br><br>";
			} elseif(!$multivol) {
				cexit("�����ļ� $file ���ڼ�� ... ʧ��<br><br><br><b>�޷��ָ�����</b></div>");
			}
			if(is_readable($file)) {
				echo "�����ļ� $file �ɶ���� ... �ɹ�<br><br>";
				@$fp = fopen($file, "r");
				@flock($fp, 3);
				$sqldump = @fread($fp, filesize($file));
				@fclose($fp);
				echo "�ӱ��ض�ȡ���� ... �ɹ�<br><br>";
			} elseif(!$multivol) {
				cexit("�����ļ� $file �ɶ���� ... ʧ��<br><br><br><b>�޷��ָ�����</b></div>");
			}
		}
		if($multivol && !$sqldump) {
			cexit("�־��ݷ�Χ��� ... �ɹ�<br><br><b>��ϲ��,�����Ѿ�ȫ���ɹ��ָ�!��ȫ���,�����ɾ��������.</b></div>");
		}
		echo "�����ļ� $file ��ʽ��� ... ";
		if($whereis == 'is_uc') {
			
			$identify = explode(',', base64_decode(preg_replace("/^# Identify:\s*(\w+).*/s", "\\1", substr($sqldump, 0, 256))));		
			$method = 'multivol';
			$volume = $identify[4];
		} else {
			@list(,,,$method, $volume) = explode(',', base64_decode(preg_replace("/^# Identify:\s*(\w+).*/s", "\\1", preg_replace("/^(.+)/", "\\1", substr($sqldump, 0, 256)))));
		}
		if($method == 'multivol' && is_numeric($volume)) {
			echo "�ɹ�<br><br>";
		} else {
			cexit("ʧ��<br><br><b>���ݷ� Discuz! �־��ݸ�ʽ,�޷��ָ�</b></div>");
		}
		if($onlysave == "yes") {
			echo "�������ļ����浽���ط����� ... ";
			$filename = TOOLS_ROOT.'./'.$backdirarray[$whereis].strrchr($file, "/");
			@$filehandle = fopen($filename, "w");
			@flock($filehandle, 3);
			if(@fwrite($filehandle, $sqldump)) {
				@fclose($filehandle);
				echo "�ɹ�<br><br>";
			} else {
				@fclose($filehandle);
				die("ʧ��<br><br><b>�޷���������</b>");
			}
			echo "�ɹ�<br><br><b>��ϲ��,�����Ѿ��ɹ����浽���ط����� <a href=\"".strstr($filename, "/")."\">$filename</a>.��ȫ���,�����ɾ��������.</b></div>";
		} else {
			$sqlquery = splitsql($sqldump);
			echo "��ֲ������ ... �ɹ�<br><br>";
			unset($sqldump);

			echo "���ڻָ�����,��ȴ� ... </div>";
			foreach($sqlquery as $sql) {
				$dbversion = mysql_get_server_info();
				$sql = syntablestruct(trim($sql), $dbversion > '4.1', $dbcharset);
				if(trim($sql)) {
					@mysql_query($sql);
				}
			}
			if($auto == 'off') {
				$nextfile = str_replace("-$volume.sql", '-'.($volume + 1).'.sql', $file);
				cexit("<ul><li>�����ļ� <b>$volume#</b> �ָ��ɹ�,�������Ҫ������ָ������������ļ�</li><li>����<b><a href=\"?action=all_restore&file=$nextfile&multivol=yes\">ȫ���ָ�</a></b>	�������ָ���һ�������ļ�<b><a href=\"?action=all_restore&file=$nextfile&multivol=yes&auto=off\">�����ָ���һ�����ļ�</a></b></li></ul>");
			} else {
				$nextfile = str_replace("-$volume.sql", '-'.($volume + 1).'.sql', $file);
				echo "<ul><li>�����ļ� <b>$volume#</b> �ָ��ɹ�,���ڽ��Զ����������־�������.</li><li><b>����ر���������жϱ���������</b></li></ul>";
				redirect("?action=all_restore&file=$nextfile&multivol=yes");
			}
		}
	} else {
		$exportlog = array();
		if(is_dir(TOOLS_ROOT.'./'.$backdirarray[$whereis])) {
			$dir = dir(TOOLS_ROOT.'./'.$backdirarray[$whereis]);
			while($entry = $dir->read()) {
				$entry = "./".$backdirarray[$whereis]."/$entry";
				if(is_file($entry) && preg_match("/\.sql/i", $entry)) {
					$filesize = filesize($entry);
					$fp = @fopen($entry, 'rb');
					@$identify = explode(',', base64_decode(preg_replace("/^# Identify:\s*(\w+).*/s", "\\1", fgets($fp, 256))));
					@fclose ($fp);
						if(preg_match("/\-1.sql/i", $entry) || $identify[3] == 'shell') {
							$exportlog[$identify[0]] = array(	'version' => $identify[1],
												'type' => $identify[2],
												'method' => $identify[3],
												'volume' => $identify[4],
												'filename' => $entry,
												'size' => $filesize);
						}
				} elseif(is_dir($entry) && preg_match("/backup\_/i", $entry)) {
					$bakdir = dir($entry);
						while($bakentry = $bakdir->read()) {
							$bakentry = "$entry/$bakentry";
							if(is_file($bakentry)) {
								@$fp = fopen($bakentry, 'rb');
								@$bakidentify = explode(',', base64_decode(preg_replace("/^# Identify:\s*(\w+).*/s", "\\1", fgets($fp, 256))));
								@fclose ($fp);
								if(preg_match("/\-1\.sql/i", $bakentry) || $bakidentify[3] == 'shell') {
									$identify['bakentry'] = $bakentry;
								}
							}
						}
						if(preg_match("/backup\_/i", $entry)) {
							$exportlog[filemtime($entry)] = array(	'version' => $bakidentify[1],
												'type' => $bakidentify[2],
												'method' => $bakidentify[3],
												'volume' => $bakidentify[4],
												'bakentry' => $identify['bakentry'],
												'filename' => $entry);
						}
				}
			}
			$dir->close();
		} else {
			echo 'error';
		}
		krsort($exportlog);
		reset($exportlog);

		$title = '<h5><a href="?action=all_restore">���ָ����ݡ�</a>';
		if($dz_version >= 700 || $whereis == 'is_uc' || $whereis == 'is_uch' || $ss_version >= 70) {
			$title .= '&nbsp;&nbsp;&nbsp;<a href="?action=all_backup&begin=1">���������ݡ�</a></h5>';
		} else {
			$title .= '</h5>';	
		}
		$exportinfo = $title.'<table><caption>&nbsp;&nbsp;&nbsp;���ݿ��ļ���</caption><tr><th>������Ŀ</th><th>�汾</th><th>ʱ��</th><th>����</th><th>�鿴</th><th>����</th></tr>';
		foreach($exportlog as $dateline => $info) {
			$info['dateline'] = is_int($dateline) ? gmdate("Y-m-d H:i", $dateline + 8*3600) : 'δ֪';
				switch($info['type']) {
					case 'full':
						$info['type'] = 'ȫ������';
						break;
					case 'standard':
						$info['type'] = '��׼����(�Ƽ�)';
						break;
					case 'mini':
						$info['type'] = '��С����';
						break;
					case 'custom':
						$info['type'] = '�Զ��屸��';
						break;
				}
			$info['volume'] = $info['method'] == 'multivol' ? $info['volume'] : '';
			$info['method'] = $info['method'] == 'multivol' ? '���' : 'shell';
			$info['url'] = str_replace(".sql", '', str_replace("-$info[volume].sql", '', substr(strrchr($info['filename'], "/"), 1)));
			$exportinfo .= "<tr>\n".
				"<td>".$info['url']."</td>\n".
				"<td>$info[version]</td>\n".
				"<td>$info[dateline]</td>\n".
				"<td>$info[type]</td>\n";
			if($info['bakentry']) {
			$exportinfo .= "<td><a href=\"?action=all_restore&bakdirname=".$info['url']."\">�鿴</a></td>\n".
				"<td><a href=\"?action=all_restore&file=$info[bakentry]&importsubmit=yes\">[ȫ������]</a></td>\n</tr>\n";
			} else {
			$exportinfo .= "<td><a href=\"?action=all_restore&filedirname=".$info['url']."\">�鿴</a></td>\n".
				"<td><a href=\"?action=all_restore&file=$info[filename]&importsubmit=yes\">[ȫ������]</a></td>\n</tr>\n";
			}
		}
		$exportinfo .= '</table>';
		echo $exportinfo;
		unset($exportlog);
		unset($exportinfo);
		echo "<br>";
	//�鿴Ŀ¼��ı����ļ��б�һ��Ŀ¼��
		if(!empty($filedirname)) {
			$exportlog = array();
			if(is_dir(TOOLS_ROOT.'./'.$backdirarray[$whereis])) {
				$dir = dir(TOOLS_ROOT.'./'.$backdirarray[$whereis]);
				while($entry = $dir->read()) {
					$entry = "./".$backdirarray[$whereis]."/$entry";
					if(is_file($entry) && preg_match("/\.sql/i", $entry) && preg_match("/$filedirname/i", $entry)) {
						$filesize = filesize($entry);
						@$fp = fopen($entry, 'rb');
						@$identify = explode(',', base64_decode(preg_replace("/^# Identify:\s*(\w+).*/s", "\\1", fgets($fp, 256))));
						@fclose ($fp);
	
						$exportlog[$identify[0]] = array(	'version' => $identify[1],
											'type' => $identify[2],
											'method' => $identify[3],
											'volume' => $identify[4],
											'filename' => $entry,
											'size' => $filesize);
					}
				}
				$dir->close();
			}
			krsort($exportlog);
			reset($exportlog);
	
			$exportinfo = '<table>
							<caption>&nbsp;&nbsp;&nbsp;���ݿ��ļ��б�</caption>
							<tr>
							<th>�ļ���</th><th>�汾</th>
							<th>ʱ��</th><th>����</thd>
							<th>��С</th><td>��ʽ</th>
							<th>���</th><th>����</th></tr>';
			foreach($exportlog as $dateline => $info) {
				$info['dateline'] = is_int($dateline) ? gmdate("Y-m-d H:i", $dateline + 8*3600) : 'δ֪';
					switch($info['type']) {
						case 'full':
							$info['type'] = 'ȫ������';
							break;
						case 'standard':
							$info['type'] = '��׼����(�Ƽ�)';
							break;
						case 'mini':
							$info['type'] = '��С����';
							break;
						case 'custom':
							$info['type'] = '�Զ��屸��';
							break;
					}
				$info['volume'] = $info['method'] == 'multivol' ? $info['volume'] : '';
				$info['method'] = $info['method'] == 'multivol' ? '���' : 'shell';
				$exportinfo .= "<tr>\n".
					"<td><a href=\"$info[filename]\" name=\"".substr(strrchr($info['filename'], "/"), 1)."\">".substr(strrchr($info['filename'], "/"), 1)."</a></td>\n".
					"<td>$info[version]</td>\n".
					"<td>$info[dateline]</td>\n".
					"<td>$info[type]</td>\n".
					"<td>".get_real_size($info[size])."</td>\n".
					"<td>$info[method]</td>\n".
					"<td>$info[volume]</td>\n".
					"<td><a href=\"?action=all_restore&file=$info[filename]&importsubmit=yes&auto=off\">[����]</a></td>\n</tr>\n";
			}
			$exportinfo .= '</table>';
			echo $exportinfo;
		}
		// �鿴Ŀ¼��ı����ļ��б� ����Ŀ¼�£����ж���Ŀ¼�����������
		if(!empty($bakdirname)) {
			$exportlog = array();
			$filedirname = TOOLS_ROOT.'./'.$backdirarray[$whereis].'/'.$bakdirname;
			if(is_dir($filedirname)) {
				$dir = dir($filedirname);
				while($entry = $dir->read()) {
					$entry = $filedirname.'/'.$entry;
					if(is_file($entry) && preg_match("/\.sql/i", $entry)) {
						$filesize = filesize($entry);
						@$fp = fopen($entry, 'rb');
						@$identify = explode(',', base64_decode(preg_replace("/^# Identify:\s*(\w+).*/s", "\\1", fgets($fp, 256))));
						@fclose ($fp);
	
						$exportlog[$identify[0]] = array(	
											'version' => $identify[1],
											'type' => $identify[2],
											'method' => $identify[3],
											'volume' => $identify[4],
											'filename' => $entry,
											'size' => $filesize);
					}
				}
				$dir->close();
			}
			krsort($exportlog);
			reset($exportlog);
	
			$exportinfo = '<table>
					<caption>&nbsp;&nbsp;&nbsp;���ݿ��ļ��б�</caption>
					<tr>
					<th>�ļ���</th><th>�汾</th>
					<th>ʱ��</th><th>����</th>
					<th>��С</th><th>��ʽ</th>
					<th>���</th><th>����</th></tr>';
			foreach($exportlog as $dateline => $info) {
				$info['dateline'] = is_int($dateline) ? gmdate("Y-m-d H:i", $dateline + 8*3600) : 'δ֪';
				switch($info['type']) {
					case 'full':
						$info['type'] = 'ȫ������';
						break;
					case 'standard':
						$info['type'] = '��׼����(�Ƽ�)';
						break;
					case 'mini':
						$info['type'] = '��С����';
						break;
					case 'custom':
						$info['type'] = '�Զ��屸��';
						break;
				}
				$info['volume'] = $info['method'] == 'multivol' ? $info['volume'] : '';
				$info['method'] = $info['method'] == 'multivol' ? '���' : 'shell';
				$exportinfo .= "<tr>\n".
						"<td><a href=\"$info[filename]\" name=\"".substr(strrchr($info['filename'], "/"), 1)."\">".substr(strrchr($info['filename'], "/"), 1)."</a></td>\n".
						"<td>$info[version]</td>\n".
						"<td>$info[dateline]</td>\n".
						"<td>$info[type]</td>\n".
						"<td>".get_real_size($info[size])."</td>\n".
						"<td>$info[method]</td>\n".
						"<td>$info[volume]</td>\n".
						"<td><a href=\"?action=all_restore&file=$info[filename]&importsubmit=yes&auto=off\">[����]</a></td>\n</tr>\n";
			}
			$exportinfo .= '</table>';
			echo $exportinfo;
		}
		echo "<br>";
		cexit("");
	}
} elseif($action == 'all_runquery') {//����sql
		if(!empty($_POST['sqlsubmit']) && $_POST['queries']) {
			runquery($queries);
		}
		htmlheader();
		runquery_html();
		htmlfooter();	
} elseif($action == 'all_setadmin') {//���ù���Ա�ʺ����룬
	$sql_findadmin = '';
	$sql_select = '';
	$sql_update = '';
	$sql_rspw = '';
	$secq = '';
	$rspw = '';
	$username = '';
	$uid = '';
	all_setadmin_set($tablepre,$whereis);
	$info = '';
	$info_uc = '';	
	htmlheader();
	?>
	<h4>���ô�ʼ������</h4>
	<?php
		//��ѯ�Ѿ����ڵĹ���
	if(!empty($_POST['loginsubmit'])) {
		if($whereis == 'is_uc') {
			define(ROOT_DIR,dirname(__FILE__)."/");
			$configfile = ROOT_DIR."./data/config.inc.php";
			$uc_password = $_POST["password"];
			$salt = substr(uniqid(rand()), 0, 6);
			if(!$uc_password) {
				$info = "���벻��Ϊ��";
			} else {
				$md5_uc_password = md5(md5($uc_password).$salt);
				$config = file_get_contents($configfile);
				$config = preg_replace("/define\('UC_FOUNDERSALT',\s*'.*?'\);/i", "define('UC_FOUNDERSALT', '$salt');", $config);
				$config = preg_replace("/define\('UC_FOUNDERPW',\s*'.*?'\);/i", "define('UC_FOUNDERPW', '$md5_uc_password');", $config);
				$fp = @fopen($configfile, 'w');
				@fwrite($fp, $config);
				@fclose($fp);
				$info = "UCenter��ʼ��������ĳɹ�Ϊ��$uc_password";
			}
		}
		errorpage($info,'���ù���Ա�ʺ�',0,0);
	} else {
	?>
	<form action="?action=all_setadmin" method="post">
		<table>
	
			<?php
				if($rspw) {
			?>
				<tr>
					<th width="30%">����������</th>
					<td width="70%"><input class="textinput" type="text" name="password" size="25"></td>
				</tr>
			<?php
				} else {
			?>
				<tr>
					<th width="30%">�����޸���ʾ</th>
					<td width="70%">����Ա�������¼UC��̨ȥ�ġ�<a href=11 target='_blank'>�������UC��̨</a> </td>
				</tr>
			<?php
				}
				if($secq) {
			?>
				<tr>
					<th width="30%">�Ƿ������ȫ����</th>
					<td width="70%"><input class="radio" name="issecques" value="1" checked="checked" type="radio">��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="issecques" value="" class="radio" type="radio">��</td>
				</tr>
			<?php
				}
			?>
		</table>
		<input type="submit" name="loginsubmit" value="�� &nbsp; ��">
	</form>
	<?php
	}
	specialdiv();
	htmlfooter();
} elseif($action == 'all_setlock') {//����������
	touch($lockfile);
	if(file_exists($lockfile)) {
		echo '<meta http-equiv="refresh" content="3 url=?">';
		errorpage("<h6>�ɹ��رչ����䣨UCenterר�ð棩��ǿ�ҽ������ڲ���Ҫ�������ʱ��ʱ����ɾ��</h6>",'���������䣨UCenterר�ð棩');
	} else {
		errorpage('ע������Ŀ¼û��д��Ȩ�ޣ������޷������ṩ��ȫ���ϣ���ɾ����̳��Ŀ¼�µ�tool.php�ļ���','���������䣨UCenterר�ð棩');
	}
} elseif($action == 'all_logout') {//�˳���½
	setcookie('toolpassword', '', -86400 * 365);
	errorpage("<h6>���ѳɹ��˳�,��ӭ�´�ʹ��.ǿ�ҽ������ڲ�ʹ��ʱɾ�����ļ�.</h6>");
} elseif($action == 'phpinfo') {
	echo phpinfo(13);exit;
} elseif($action == 'datago') {
	htmlheader();
	!$tableno && $tableno = 0;
	!$do && $do = 'create';
	!$start && $start = 0;
	$limit = 2000;
	echo '<h4>���ݿ����ת��</h4>';
	echo "<div class=\"specialdiv\">������ʾ��<ul>
		<li><font color=red>ת�����������޸������ļ��е����ݿ�ǰ׺��ҳ����롢���ݿ����</font></li>
		<li>��ϸת���̳̣�<a href='http://www.discuz.net/thread-1460873-1-1.html'><font color=red>ʹ��Toolsת�����ݿ����̳�</font></a></li>
		<li>������ݿ���󣬿�����Ҫ����ʱ��</li>
		</ul></div>";
	if($submit) {
		do_datago($mysql,$tableno,$do,$start,$limit);
	} elseif($my_version > '4.1') {
		datago_output();
	} else {
		echo '���ݿ�汾��֧�����ݿ����';
	}
	htmlfooter();
} elseif($action == 'all_backup') {
	htmlheader();
	echo "<script type='text/javascript'>
			function jumpurl(url){
				location.href = url;
				return false;
			}
		</script>";
	if($begin == '1') {
		echo "<h4>���ݿⱸ��</h4><div class=\"specialdiv\">������ʾ��<ul>
			<li>���ݿⱸ��ͨ��api/dbbak.php��ִ�У���ȷ������ļ�����</li>
			<li>����ǰ��ر���̳���ʣ����ⱸ�����ݲ�����</li>
			<li>�뾡��ѡ�����������ʱ�β���,�Ա��ⳬʱ������򳤾�(���� 10 ����)����Ӧ,��ˢ��</li></ul></div>";
		$title = '<h5><a href="?action=all_restore">���ָ����ݡ�</a>';
		$title .= '&nbsp;&nbsp;&nbsp;<a href="?action=all_backup&begin=1">���������ݡ�</a></h5>';
		echo $title;
		$begin = '<button style="margin:0px;" onclick=jumpurl("uctools.php?action=all_backup")>��ʼ����</button>';
		cexit($begin);
	}
	$notice = "<div class=\"specialdiv\">������ʾ��<ul>
			<li>�ӿ��ļ������ڣ�</li>
			</ul></div>";
	if(!file_exists('./api/dbbak.php')) {
		cexit($notice);
	}
	if($nexturl) {
		$url = $nexturl;	
	} else {
		$url = getbakurl($whereis);
	}	
	dobak($url,$num);
	htmlfooter();
} else {
	htmlheader();
	echo '<h4>��ӭ��ʹ�� Comsenz ϵͳά�������䣨UCenterר�ð棩</h4>
		<tr><td><br>';
	echo '<h5>Comsenz ϵͳά�������䣨UCenterר�ð棩���ܼ�飺</h5><ul>';
	foreach($functionall as  $value) {
		$apps = explode('_', $value['0']);
		if(in_array(substr($whereis, 3), $apps) || $value['0'] == 'all') {	
				echo '<li>'.$value[2].'��'.$value[3].'</li>';
		}
	}
	echo '</ul>';
	htmlfooter();
}
//������
function cexit($message) {
	echo $message;
	specialdiv();
	htmlfooter();
}


function get_real_size($size){
	$kb = 1024;
	$mb = 1024 * $kb;
	$gb = 1024 * $mb;
	$tb = 1024 * $gb;

	if($size < $kb) {
		return $size.' Byte';
	} elseif($size < $mb) {
		return round($size/$kb,2).' KB';
	} elseif($size < $gb) {
		return round($size/$mb,2).' MB';
	} elseif($size < $tb) {
		return round($size/$gb,2).' GB';
	} else {
		return round($size/$tb,2).' TB';
	}
}

function htmlheader() {
	global $uch_version,$alertmsg, $whereis, $functionall,$dz_version,$ss_version,$toolpassword,$tool_password,$toolbar,$plustitle;
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=GBK">
		<title>Comsenz ϵͳά�������䣨UCenterר�ð棩 '.VERSION.'-New</title>
		<style type="text/css"><!--
		body {font-family: Tahoma,Arial, Helvetica, sans-serif, "����";font-size: 12px;color:#000;line-height: 120%;padding:0;margin:0;background:#DDE0FF;overflow-x:hidden;word-break:break-all;white-space:normal;scrollbar-3d-light-color:#606BFF;scrollbar-highlight-color:#E3EFF9;scrollbar-face-color:#CEE3F4;scrollbar-arrow-color:#509AD8;scrollbar-shadow-color:#F0F1FF;scrollbar-base-color:#CEE3F4;}
        a:hover {color:#60F;}
		ul {padding:2px 0 10px 0;margin:0;}
		textarea,table,td,th,select{border:1px solid #868CFF;border-collapse:collapse;}
		table li {margin-left:10px;}
		input{margin:10px 0 0px 30px;border-width:1px;border-style:solid;border-color:#FFF #64A7DD #64A7DD #FFF;padding:2px 8px;background:#E3EFF9;}
			input.radio,input.checkbox,input.textinput,input.specialsubmit {margin:0;padding:0;border:0;padding:0;background:none;}
			input.textinput,input.specialsubmit {border:1px solid #AFD2ED;background:#FFF;}
			input.textinput {padding:4px 0;} 			input.specialsubmit {border-color:#FFF #64A7DD #64A7DD #FFF;background:#E3EFF9;padding:0 5px;}
		option {background:#FFF;}
		select {background:#F0F1FF;}
		#header {border-top:4px solid #86B9D6;height:60px;width:100%;padding:0;margin:0;}
		    h2 {font-size:20px;font-weight:normal;position:absolute;top:20px;left:20px;padding:10px;margin:0;}
		    h3 {font-size:14px;position:absolute;top:28px;right:20px;padding:10px;margin:0;}
		#content {height:510px;background:#F0F1FF;overflow-x:hidden;z-index:1000;}
		    #nav {top:60px;left:0;height:510px;width:180px;border-right:1px solid #DDE0FF;position:absolute;z-index:2000;}
		        #nav ul {padding:0 10px;padding-top:30px;}
		        #nav li {list-style:none;}
		        #nav li a {font-size:14px;line-height:180%;font-weight:400;color:#000;}
		        #nav li a:hover {color:#60F;}
		    #textcontent {padding-left:200px;height:510px;width:80%;*width:100%;line-height:160%;overflow-y:auto;overflow-x:hidden;}
			    h4,h5,h6 {padding:4px;font-size:16px;font-weight:bold;margin-top:20px;margin-bottom:5px;color:#006;}
				h5,h6 {font-size:14px;color:#000;}
				h6 {color:#F00;padding-top:5px;margin-top:0;}
				.specialdiv {width:70%;border:1px dashed #C8CCFF;padding:0 5px;margin-top:20px;background:#F9F9FF;}
				.specialdiv2 {height:240px;width:60%;border:1px dashed #C8CCFF;padding:15px;margin-top:20px;background:#F9F9FF;overflow-y:scroll;}
				#textcontent ul {margin-left:30px;}
				textarea {width:78%;height:300px;text-align:left;border-color:#AFD2ED;}
				select {border-color:#AFD2ED;}
				table {width:74%;font-size:12px;margin-left:18px;margin-top:10px;}
				    table.specialtable,table.specialtable td {border:0;}
					td,th {padding:5px;text-align:left;}
				    caption {font-weight:bold;padding:8px 0;color:#3544FF;text-align:left;}
				    th {background:#D9DCFF;font-weight:600;}
					td.specialtd {text-align:left;}
				.specialtext {background:#FCFBFF;margin-top:20px;padding:5px 40px;width:64.5%;margin-bottom:10px;color:#006;}
		#footer p {padding:0 5px;text-align:center;}
		#jsmenu {margin-left:-200px;margin-top:-110px;border:5px solid #868CFF;width:400px;height:140px;padding:4px 10px 0 10px; text-align:left;background:#FFF; left:50%; top:50%; position:absolute; font:12px;zIndex:10001;}
		.button {margin-top:20px;}
		.infobox {background:#FFF;border-bottom:4px solid #868CFF;border-top:4px solid #868CFF;margin-bottom:10px;padding:30px;text-align:center;width:90%;}
		pre {*margin-top:10px;}
		.current { font-weight: bold; color: #090 !important; border-bottom-color: #F90 !important; }
		-->
		</style>
		</head>
		<script>function $(id) {return document.getElementById(id);}
		function menuclose(){
			$(\'jsmenu\').style.display = \'none\';
		}
		</script>
		<body>
	<div id = "jsmenu" style="display:none">
	<h6>��ʾ��</h6>
	��ʾ���ڽ��д˲���ǰ���鱸�����ݿ⣬���⴦������г��ִ���������ݶ�ʧ����<br/>
	<input class=button onclick=menuclose() type=button value=��֪����></input>
	</div>
        <div id="header">
		<h2>< Comsenz Tools '.VERSION.' > Ucenter ר��</h2>
		<h3>[ <a href="?" target="_self">��ҳ</a> ]&nbsp;
		[ <a href="?action=all_setlock" target="_self">����</a> ]&nbsp;';
	if($toolpassword == md5($tool_password)) {
		foreach($toolbar as $value) {
			echo '[ <a href="?action='.$value[0].'" target="_self">'.$value[1].'</a> ]&nbsp';
		}
	}
	echo '</h3></div>
		<div id="nav">';
		echo '<ul>';//�����˵��и��ݲ�ͬ��Ŀ¼��ʾ��ͬ
		if($toolpassword == md5($tool_password)) {
			foreach($functionall as  $value) {
				$apps = explode('_', $value['0']);
				if(in_array(substr($whereis, 3), $apps) || $value['0'] == 'all') {	
					if($whereis == 'is_ss' && $value[1] == 'all_setadmin' && $ss_version<70 ) {
						continue;
					}
					echo '<li>[ <a href="?action='.$value[1].'" target="_self">'.$value[2].'</a> ]</li>';
				}
			}
		} else {
			echo '<li>[ <a href="uctools.php" target="_self">ʹ��ǰ���¼</a> ]</li>';	
		}
		echo '</ul>';
		echo '</div>
		<div id="content">
		<div id="textcontent">';
}
//ҳ��ײ�
function htmlfooter(){
	echo '</div></div>
		<div id="footer"><p>Comsenz ϵͳά�������䣨UCenterר�ð棩 &nbsp;Release:'.Release.'&nbsp;
		 &copy; <a href="http://www.comsenz.com" style="color: #000000; text-decoration: none">Comsenz Inc.</a> 2001-2009 </font></td></tr><tr style="font-size: 0px; line-height: 0px; spacing: 0px; padding: 0px; background-color: #698CC3">
		</p></div>
		</body>
		</html>';
	exit;
}
//������Ϣ
function errorpage($message,$title = '',$isheader = 1,$isfooter = 1){
	if($isheader) {
		htmlheader();
	}
	!$isheader && $title = '';
	if($message == 'login') {
		$message ='<h4>�������¼</h4>
				<form action="?" method="post">
					<table class="specialtable"><tr>
					<td width="20%"><input class="textinput" type="password" name="toolpassword"></input></td>
					<td><input class="specialsubmit" type="submit" value="�� ¼"></input></td></tr></table>
					<input type="hidden" name="action" value="login">
				</form>';
	} else {
		$message = "<h4>$title</h4><br><br><table><tr><th>��ʾ��Ϣ</th></tr><tr><td>$message</td></tr></table>";
	}
	echo $message;
	if($isfooter) {
		htmlfooter();
	}
}
//��ת
function redirect($url) {
	echo "<script>";
	echo "function redirect() {window.location.replace('$url');}\n";
	echo "setTimeout('redirect();', 2000);\n";
	echo "</script>";
	echo "<br><br><a href=\"$url\">������������û���Զ���ת����������</a>";
	cexit("");
}
/**
 * ���Ŀ¼���µ��ļ�Ȩ�޺���
 * @param unknown_type $directory
 */

//���sql���
function splitsql($sql){
	$sql = str_replace("\r", "\n", $sql);
	$ret = array();
	$num = 0;
	$queriesarray = explode(";\n", trim($sql));
	unset($sql);
	foreach($queriesarray as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= $query[0] == "#" ? NULL : $query;
		}
		$num++;
	}
	return($ret);
}

function syntablestruct($sql, $version, $dbcharset) {

	if(strpos(trim(substr($sql, 0, 18)), 'CREATE TABLE') === FALSE) {
		return $sql;
	}
	if(substr(trim($sql), 0, 9) == 'SET NAMES' && !$version) {
        return '';
    } 
	$sqlversion = strpos($sql, 'ENGINE=') === FALSE ? FALSE : TRUE;

	if($sqlversion === $version) {

		return $sqlversion && $dbcharset ? preg_replace(array('/ character set \w+/i', '/ collate \w+/i', "/DEFAULT CHARSET=\w+/is"), array('', '', "DEFAULT CHARSET=$dbcharset"), $sql) : $sql;
	}

	if($version) {
		return preg_replace(array('/TYPE=HEAP/i', '/TYPE=(\w+)/is'), array("ENGINE=MEMORY DEFAULT CHARSET=$dbcharset", "ENGINE=\\1 DEFAULT CHARSET=$dbcharset"), $sql);

	} else {
		return preg_replace(array('/character set \w+/i', '/collate \w+/i', '/ENGINE=MEMORY/i', '/\s*DEFAULT CHARSET=\w+/is', '/\s*COLLATE=\w+/is', '/ENGINE=(\w+)(.*)/is'), array('', '', 'ENGINE=HEAP', '', '', 'TYPE=\\1\\2'), $sql);
	}
}
function stay_redirect() {
	global $action, $actionnow, $step, $stay, $convertedrows, $allconvertedrows;
	$nextstep = $step + 1;
	echo '<h4>���ݿ�������������</h4><table>
			<tr><th>���ڽ���'.$actionnow.'</th></tr><tr>
			<td>';
	if($stay) {
		$actions = isset($action[$nextstep]) ? $action[$nextstep] : '����';
		echo "$actionnow �������.������<font color=red>{$convertedrows}</font>������.".($stay == 1 ? "&nbsp;&nbsp;&nbsp;&nbsp;" : '').'<br><br>';
		echo "<a href='?action=dz_mysqlclear&step=".$nextstep."&stay=1'>( $actions )���������</a><br>";
	} else {
		if(isset($action[$nextstep])) {
			echo '�������룺'.$action[$nextstep].'......';
		}
		$allconvertedrows = $allconvertedrows + $convertedrows;
		$timeout = $GLOBALS['debug'] ? 5000 : 2000;
		echo "<script>\r\n";
		echo "<!--\r\n";
		echo "function redirect() {\r\n";
		echo "	window.location.replace('?action=dz_mysqlclear&step=".$nextstep."&allconvertedrows=".$allconvertedrows."');\r\n";
		echo "}\r\n";
		echo "setTimeout('redirect();', $timeout);\r\n";
		echo "-->\r\n";
		echo "</script>\r\n";
		echo "[<a href='?action=dz_mysqlclear' style='color:red'>ֹͣ����</a>]<br><br><a href=\"".$scriptname."?step=".$nextstep."\">��������������ʱ��û���Զ���ת���������</a>";
	}
	echo '</td></tr></table>';
}
//������ݿ���ֶ�
function loadtable($table, $force = 0) {	
	global $carray;
	$discuz_tablepre = $carray['tablepre'];
	static $tables = array();

	if(!isset($tables[$table])) {
		if(mysql_get_server_info() > '4.1') {
			$query = @mysql_query("SHOW FULL COLUMNS FROM {$discuz_tablepre}$table");
		} else {
			$query = @mysql_query("SHOW COLUMNS FROM {$discuz_tablepre}$table");
		}
		while($field = @mysql_fetch_assoc($query)) {
			$tables[$table][$field['Field']] = $field;
		}
	}
	return $tables[$table];
}

//������ݱ��������С id ֵ
function validid($id, $table) {
	global $start, $maxid, $mysql, $tablepre;
	$sql = mysql_query("SELECT MIN($id) AS minid, MAX($id) AS maxid FROM {$tablepre}$table");
	$result = mysql_fetch_array($sql);
	$start = $result['minid'] ? $result['minid'] - 1 : 0;
	$maxid = $result['maxid'];
}
//��ʾ
function specialdiv() {
	echo '<div class="specialdiv">
		<h6>ע�⣺</h6>
		<ul>
		<li>����ʹ�����Comsenz ϵͳά�������䣨UCenterר�ð棩������������������ȷ��ϵͳ�İ�ȫ���´�ʹ��ǰֻ��Ҫ��/dataĿ¼��ɾ��tool.lock�ļ����ɿ�ʼʹ�á�</li></ul></div>';
}
//�ж�Ŀ¼
function getplace() {
	global $lockfile, $cfgfile, $docdir;
	$whereis = false;
	if(is_writeable('./config.inc.php') && is_writeable('./forumdata')) {//�ж�Discuz!Ŀ¼
			$whereis = 'is_dz';
			$lockfile = './forumdata/tools.lock';
			$cfgfile = './config.inc.php';
			$docdir = './forumdata';
	}
	if(is_writeable('./data/config.inc.php') && is_dir('./control')) {//�ж�UCenterĿ¼
			$whereis = 'is_uc';
			$lockfile = './data/tools.lock';
			$cfgfile = './data/config.inc.php';
			$docdir = './data';
	}
	if(is_writeable('./config.php') && is_dir('source')) {//�ж�UCenter HomeĿ¼
			$whereis = 'is_uch';
			$lockfile = './data/tools.lock';
			$cfgfile = './config.php';
			$docdir = './data';
	}
	if(is_writeable('./config.php') && file_exists('./batch.common.php')) {//�ж�SupeSiteĿ¼
			$whereis = 'is_ss';
			$lockfile = './data/tools.lock';
			$cfgfile = './config.php';
			$docdir = './data';
	}
	return $whereis;
}
//������ݿ�������Ϣ
function getdbcfg(){
	global $uc_dbcharset,$uc_dbhost,$uc_dbuser,$uc_dbpw,$uc_dbname,$uc_tablepre,$dbhost, $dbuser, $dbpw, $dbname, $dbcfg, $whereis, $cfgfile, $tablepre, $dbcharset,$dz_version,$ss_version,$uch_version;
	if(@!include($cfgfile)) {
			htmlheader();
			cexit("<h4>�����ϴ�config�ļ��Ա�֤�������ݿ����������ӣ�</h4>");
	}
	if(UC_DBHOST) {
		$uc_dbhost = UC_DBHOST;
		$uc_dbuser = UC_DBUSER;
		$uc_dbpw = UC_DBPW;
		$uc_dbname = UC_DBNAME;	
		$uc_tablepre =  UC_DBTABLEPRE;
		$uc_dbcharset = UC_DBCHARSET;
	}
	switch($whereis) {
		case 'is_dz':
			$dbhost = $dbhost;
			$dbuser = $dbuser;
			$dbpw = $dbpw;
			$dbname = $dbname;	
			$tablepre =  $tablepre;
			$dbcharset = !$dbcharset ? (strtolower($charset) == 'utf-8' ? 'utf8' : $charset): $dbcharset;
			define('IN_DISCUZ',true);
			@require_once "./discuz_version.php";
			$dz_version = DISCUZ_VERSION;
			if($dz_version >= '7.1') {
				$dz_version = intval(str_replace('.','',$dz_version)).'0';
			} else {
				$dz_version = intval(str_replace('.','',$dz_version));
				}
			break;
		case 'is_uc':
			$dbhost = UC_DBHOST;
			$dbuser = UC_DBUSER;
			$dbpw = UC_DBPW;
			$dbname = UC_DBNAME;	
			$tablepre =  UC_DBTABLEPRE;
			$dbcharset = !UC_DBCHARSET ? (strtolower(UC_CHARSET) == 'utf-8' ? 'utf8' : UC_CHARSET) : UC_DBCHARSET;
			break;
		case 'is_uch':
			$dbhost = $_SC["dbhost"];
			$dbuser = $_SC["dbuser"];
			$dbpw = $_SC["dbpw"];
			$dbname = $_SC["dbname"];	
			$tablepre =  $_SC["tablepre"];
			if(file_exists("./ver.php")) {
				require './ver.php';
				$uch_version = X_VER;
			} else {
				$common = 'common.php';
				$version = fopen($common,'r');
				$version = fread($version,filesize($common));
				$len = strpos($version,'define(\'D_BUG\')');
				$version = substr($version,0,$len);
				$cache = fopen('./data/version.php','w');
				fwrite($cache,$version);
				fclose($cache);
				require_once './data/version.php';
				$uch_version = intval(str_replace('.','',X_VER));
				unlink('./data/version.php');
			}		
			$uch_version = intval(str_replace('.','',$uch_version));
			$dbcharset = !$_SC['dbcharset'] ? (strtolower($_SC["charset"]) == 'utf-8' ? 'utf8' : $_SC["charset"]) : $_SC['dbcharset'] ;
			break;
		case 'is_ss':
			$dbhost = $dbhost ? $dbhos : $_SC['dbhost'];
			$dbuser = $dbuser ? $dbuser : $_SC['dbuser'];
			$dbpw = $dbpw ? $dbpw : $_SC['dbpw'];
			$dbname = $dbname ? $dbname : $_SC['dbname'];	
			$tablepre =  $tablepre ? $tablepre : $_SC['tablepre'];
			$dbcharset = !$dbcharset ? (strtolower($charset) == 'utf-8' ? 'utf8' : $charset) : $dbcharset;
			if(!$dbcharset) {
				$dbcharset = !$_SC['dbcharset'] ? (strtolower($_SC['charset']) == 'utf-8' ? 'utf8' : $_SC['charset']) : $_SC['dbcharset'];			
			}
			if($_SC['dbhost'] || $_SC['dbuser']) {
				$common = 'common.php';
				$version = fopen($common,'r');
				$version = fread($version,filesize($common));
				$len = strpos($version,'define(\'S_RELEASE\'');
				$version = substr($version,0,$len);
				$cache = fopen('./data/version.php','w');
				fwrite($cache,$version);
				fclose($cache);
				require_once './data/version.php';
				$ss_version = intval(str_replace('.','',S_VER));
				unlink('./data/version.php');
			}
			break;
		default:
			$dbhost = $dbuser = $dbpw = $dbname = $tablepre = $dbcharset = '';
			break;
	}
}

function taddslashes($string, $force = 0) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = taddslashes($val, $force);
			}
		} else {
			$string = addslashes($string);
		}
	}
	return $string;
}

function pregcharset($charset,$color = 0) {
		if(strpos('..'.strtolower($charset), 'gbk')) {
			if($color) {
				return '<font color="#0000CC">gbk</font>';
			} else {
				return 'gbk';
			}
		} elseif(strpos('..'.strtolower($charset), 'latin1')) {
			if($color) {
				return '<font color="#993399">latin1</font>';
			} else {
				return 'latin1';
			}
		} elseif(strpos('..'.strtolower($charset), 'utf8')) {
			if($color) {
				return '<font color="#993300">utf8</font>';
			} else {
				return 'utf8';
			}
		} elseif(strpos('..'.strtolower($charset), 'big5')) {
			if($color) {
				return '<font color="#006699">big5</font>';
			} else {
				return 'big5';	
			}
		} else {
	       return $charset;
		}
}

function show_tools_message($message, $url = 'uctools.php',$time = '2000') {
	echo "<script>";
	echo "function redirect() {window.location.replace('$url');}\n";
	echo "setTimeout('redirect();', $time);\n";
	echo "</script>";
	echo "<h4>$title</h4><br><br><table><tr><th>��ʾ��Ϣ</th></tr><tr><td>$message<br><a href=\"$url\">������������û���Զ���ת����������</a></td></tr></table>";
	exit;
}

function fileext($filename) {
	return trim(substr(strrchr($filename, '.'), 1, 10));
}
function cutstr($string, $length, $dot = ' ...') {
	global $charset;
	if(strlen($string) <= $length) {
		return $string;
	}
	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
	$strcut = '';
	if(strtolower($charset) == 'utf-8') {
		$n = $tn = $noc = 0;
		while($n < strlen($string)) {

			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t < 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}
			if($noc >= $length) {
				break;
			}
		}
		if($noc > $length) {
			$n -= $tn;
		}
		$strcut = substr($string, 0, $n);
	} else {
		for($i = 0; $i < $length; $i++) {
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}
	$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
	return $strcut.$dot;
}

function checkfiles($currentdir, $ext = '', $sub = 1, $skip = '') {
	global $md5data, $dz_files;
	$dir = @opendir($currentdir);
	$exts = '/('.$ext.')$/i';
	$skips = explode(',', $skip);

	while($entry = @readdir($dir)) {
		$file = $currentdir.$entry;
		if($entry != '.' && $entry != '..' && (preg_match($exts, $entry) || $sub && is_dir($file)) && !in_array($entry, $skips)) {
			if($sub && is_dir($file)) {
				checkfiles($file.'/', $ext, $sub, $skip);
			} else {
				$md5data[$file] = md5_file($file);
			}
		}
	}
}

function loadtable_ucenter($table, $force = 0) {	
	global $carray;
	$discuz_tablepre = $carray['UC_DBTABLEPRE'];
	static $tables = array();

	if(!isset($tables[$table])) {
		if(mysql_get_server_info() > '4.1') {
			$query = @mysql_query("SHOW FULL COLUMNS FROM {$discuz_tablepre}$table");
		} else {
			$query = @mysql_query("SHOW COLUMNS FROM {$discuz_tablepre}$table");
		}
		while($field = @mysql_fetch_assoc($query)) {
			$tables[$table][$field['Field']] = $field;
		}
	}
	return $tables[$table];
}

function runquery($queries){//ִ��sql���
	global $tablepre,$whereis;
	$sqlquery = splitsql(str_replace(array(' cdb_', ' {tablepre}', ' `cdb_'), array(' '.$tablepre, ' '.$tablepre, ' `'.$tablepre), $queries));
	$affected_rows = 0;
	foreach($sqlquery as $sql) {
	$sql = syntablestruct(trim($sql), $my_version > '4.1', $dbcharset);
	if(trim($sql) != '') {
		mysql_query(stripslashes($sql));
		if($sqlerror = mysql_error()) {
			break;
			} else {
			$affected_rows += intval(mysql_affected_rows());
			}
		}
	}
	if(strpos($queries,'seccodestatus') && $whereis == 'is_dz') {
		dz_updatecache();	
	}
	if(strpos($queries,'bbclosed') && $whereis == 'is_dz') {
		dz_updatecache();	
	}
	if(strpos($queries,'template') && $whereis == 'is_uch') {
		uch_updatecache();	
	}
	if(strpos($queries,'seccode_login') && $whereis == 'is_uch') {
		uch_updatecache();	
	}
	if(strpos($queries,'close') && $whereis == 'is_uch') {
		uch_updatecache();	
	}
	errorpage($sqlerror? $sqlerror : "���ݿ������ɹ�,Ӱ������: &nbsp;$affected_rows",'���ݿ�����');

	if(strpos($queries,'settings') && $whereis == 'is_dz') {
		require_once './include/cache.func.php';
		updatecache('settings');		
	}
}

function runquery_html(){ //����������õ�����ѡ��
	global $whereis,$tablepre;
	echo "<h4>��������(SQL)</h4>
		<form method=\"post\" action=\"uctools.php?action=all_runquery\">
		<h5>������ѡ��������õĿ�������</h4>
		<font color=red>��ʾ��</font>Ҳ�����Լ���дSQLִ�У�������ȷ����֪����SQL����;��������ɲ���Ҫ����ʧ.<br/><br/>";
	if($whereis == 'is_uc') {
		echo "<select name=\"queryselect\" onChange=\"queries.value = this.value\">
			<option value = ''>��ѡ��TOOLS�����������</option>
			<option value = \"TRUNCATE TABLE ".$tablepre."notelist;\">���֪ͨ�б�</option>
			</select>";
		}
		echo "<br />
			<br /><textarea name=\"queries\">$queries</textarea><br />
			<input type=\"submit\" name=\"sqlsubmit\" value=\"�� &nbsp; ��\">
			</form>";
}

function topattern_array($source_array) { //����������
	$source_array = preg_replace("/\{(\d+)\}/",".{0,\\1}",$source_array);
	foreach($source_array as $key => $value) {
		$source_array[$key] = '/'.$value.'/i';
	}
	return $source_array;
}

function all_setadmin_set($tablepre,$whereis){ //�������Ա���ݳ������ɸ��ֱ���
	global $ss_version,$dz_version,$sql_findadmin,$sql_select,$sql_update,$sql_rspw,$secq,$rspw,$username,$uid;
	if($whereis == 'is_uc') {
		$secq = 0;
		$rspw = 1;
	}
}

function datago_output($whereis){
	global $dbhost, $dbuser, $dbpw, $dbname, $dbcfg;
	$charsets=array('gbk','latin1','utf8');
	$options="<option value=''>";
	foreach($charsets as $value){
		$options.="<option value=\"$value\">$value";
	}
	echo '<h5>���ݿ����ת��</h5>';
	echo '<form method=get action=uctools.php?action=datago><table>
		<tbody>
		<input name=action type=hidden value=datago>
		<tr><th width=20%>Դ���ݿ�</th><td><input class=textinput name=fromdbname value='.$dbname.'></input>&nbsp;&nbsp;Ĭ��Ϊtools���ڳ�������ݿ�,�����֪���������벻Ҫ�޸�</td></tr>
		<tr><th width=20%>Ŀ�ı���</th><td><select name=todbcharset>'.$options.'</select>&nbsp;&nbsp;ת������\'latin1\'<=>\'gbk\',\'gbk\'<=>\'utf8\'</td></tr></tbody></table>
		<input name=submit type=submit value=ת��></input>
		</form>';
}

function do_datago($mysql,$tableno,$do,$start,$limit){
	global $whereis, $dbhost, $dbuser, $dbpw, $tablepre,$fromdbname, $todbcharset, $dbcfg,$dbcharset;
	$allowcharset = array('latin1' => 'gbk','gbk' => 'utf8','utf8' => 'latin1');
	$tablename = 'Tables_in_'.strtolower($fromdbname).' ('.$tablepre.'%)';
	$mysql = mysql_connect($dbhost, $dbuser, $dbpw);
	mysql_select_db($fromdbname);
	mysql_query("SET sql_mode=''");
	$query = mysql_query('SHOW TABLES LIKE \''.$tablepre.'%\'');
	while($t = mysql_fetch_array($query,MYSQL_ASSOC)) {
		$tablearray[] = $t[$tablename];
	}
	$table = $tablearray["$tableno"];
	$query = mysql_query('SHOW TABLE STATUS LIKE '.'\''.$table.'\'');
	$tableinfo = array();
	
	while($t = mysql_fetch_array($query,MYSQL_ASSOC)) {
		$charset = explode('_',$t['Collation']);
		$t['Collation'] = $charset[0];
		$tableinfo = $t;
	}
	if($allowcharset[$tableinfo['Collation']] != $todbcharset && $allowcharset[$todbcharset] != $tableinfo['Collation']){
		if(strpos($tableinfo['Name'],$todbcharset) == 0) {
			$table = '';
		} else {
			echo "<h4>$title</h4><br><br><table><tr><th>��ʾ��Ϣ</th></tr><tr><td>$tableinfo[Name] �����ݿ�������</td></tr></table>";
			exit;
		}
	}
	mysql_query("SET NAMES '$tableinfo[Collation]'");
	
	if($do == 'create') {
		$tablecreate=array();
		foreach ($tablearray as $key => $value){
			$query=mysql_query("SHOW CREATE TABLE $value");
			while($t = mysql_fetch_array($query,MYSQL_ASSOC)){
				$t['Create Table'] = str_replace($tablepre,$whereis.'_',$t['Create Table']);
				$t['Create Table'] = str_replace("$tableinfo[Collation]","$todbcharset",$t['Create Table']);
				$t['Create Table'] = str_replace($whereis.'_',$todbcharset.$whereis.'_',$t['Create Table']);
				$t['Table'] = str_replace($tablepre,$todbcharset.$whereis.'_',$t['Table']);
				$tablecreate[]=$t;
			}
		}
		mysql_query('SET NAMES \''.$todbcharset.'\'');
		if(mysql_get_server_info() > '5.0'){
			mysql_query("SET sql_mode=''");
		}
		foreach ($tablecreate as $key => $value){
			mysql_query("DROP TABLE IF EXISTS `$value[Table]`");
			mysql_query($value['Create Table']);
			$count++;			
		}
		$toolstip .= '���еı�����ɣ����ݿ⹲�� '.$count.' ����<br>';
		show_tools_message($toolstip,"uctools.php?action=datago&do=data&fromdbname=$fromdbname&todbcharset=$todbcharset&submit=%D7%AA%BB%BB");

	} elseif($do == 'data') {
		$count = 0;
		$data = array();
		$newtable = str_replace($tablepre,$todbcharset.$whereis.'_',$table);
		if($table) {
			mysql_query("SET NAMES '$tableinfo[Collation]'");
			$query = mysql_query("SELECT * FROM $table LIMIT $start,$limit");
			
			while($t = mysql_fetch_array($query,MYSQL_ASSOC)) {
				$data[] = $t;	
			}			
			unset($t);			
			$todbcharset2 = $todbcharset;
			if($tableinfo['Collation'] == 'utf8' || $todbcharset=='utf8'){
				$todbcharset2 = $tableinfo['Collation'];
			}
			mysql_query('SET NAMES \''.$todbcharset2.'\'');
			if(mysql_get_server_info() > '5.0'){
				mysql_query("SET sql_mode=''");
			}
			if($start == 0){
				mysql_query("TRUNCATE TABLE $newtable");
			}

			foreach($data as $key => $value){
				$sql='';
				foreach($value as $tokey => $tovalue){
					$tovalue = addslashes($tovalue);
					$sql = $sql ? $sql.",'".$tovalue."'" : "'".$tovalue."'";
				}
				mysql_query("INSERT INTO $newtable VALUES($sql)") or mysql_errno();
				$count++;
			}
			if($count == $limit) {
				$start += $count;
				show_tools_message("����ת�� $table ��Ĵ� $start ����¼��ʼ�ĺ� $limit ����¼","uctools.php?action=datago&do=data&fromdbname=$fromdbname&todbcharset=$todbcharset&tableno=$tableno&start=$start&submit=%D7%AA%BB%BB");
			} else {
				$tableno ++;
				show_tools_message("����ת�� $table ��Ĵ� $start ����¼��ʼ�ĺ� $limit ����¼","uctools.php?action=datago&do=data&fromdbname=$fromdbname&todbcharset=$todbcharset&tableno=$tableno&submit=%D7%AA%BB%BB",$time='1000');
			}
		} elseif($dbcharset == 'latin1' || $todbcharset == 'latin1') {
			echo "<div class=\"specialdiv2\" id=\"serialize\">ת����ʾ��<ul>
				</ul></div>";
			echo '<script>$("serialize").innerHTML+="<li>ת����ɣ�ת��������ݿ�ǰ׺Ϊ��<font color=red>'.$todbcharset.$whereis.'_ </font></li>";
				$("serialize").scrollTop=$("serialize").scrollHeight;</script>';
		} else {
			$toolstip = '���ݱ���ת����ϣ��޸����л����ݡ�';
			show_tools_message($toolstip,"uctools.php?action=datago&do=serialize&fromdbname=$fromdbname&todbcharset=$todbcharset&submit=%D7%AA%BB%BB");
		}
		
	} elseif($do == 'serialize' && $dbcharset!='latin1' && $todbcharset!='latin1') {
		if($whereis == 'is_ss') {
			$a = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f');
			foreach($a as $num) {
				mysql_query("TRUNCATE TABLE ".$todbcharset.$whereis.'_'."cache_".$num);
			}
		}
		$arr = getlistarray($whereis,'datago');
		$limit = '3000';
		echo "<div class=\"specialdiv2\" id=\"serialize\">ת����ʾ��<ul>
					</ul></div>";
		foreach($arr as $field) {
			$stable = $todbcharset.$whereis.'_'.$field[0];
			$sfield = $field[1];
			$sid	= $field[2];
			$query = mysql_query("SELECT $sid,$sfield FROM $stable ORDER BY $sid DESC LIMIT $limit");
			while($values = mysql_fetch_array($query,MYSQL_ASSOC)) {
				$data = $values[$sfield];
				$id   = $values[$sid];
				$data = preg_replace_callback('/s:([0-9]+?):"([\s\S]*?)";/','_serialize',$data);
				$data = taddslashes($data);
				if(mysql_query("update `$stable` set `$sfield`='$data' where `$sid`='$id'")) {
					$toolstip = $stable.' ��� '.$sid.' Ϊ '.$id.' �� '.$sfield.' �ֶΣ��޸��ɹ�<br/>';
				} else {
					$toolstip = $stable.' ��� '.$sid.' Ϊ '.$id.' �� '.$sfield.' �ֶΣ�<font color=red>�޸�ʧ��</font><br/>';
				}
				echo '<script>$("serialize").innerHTML+="'.$toolstip.'";
					$("serialize").scrollTop=$("serialize").scrollHeight;</script>';
			}
		}
		mysql_close($mysql);
		echo '<script>$("serialize").innerHTML+="<li>ת����ɣ������޸���¼��ת��������ݿ�ǰ׺Ϊ��<font color=red>'.$todbcharset.$whereis.'_ </font></li>";
			$("serialize").scrollTop=$("serialize").scrollHeight;</script>';
	}
}

function _config_form($title = '',$varname = '',$end = '1') {
	global $$varname;
	$ucapi = UC_API;
	$ucip = UC_IP;
	$form = '';
	$form .= "<th width=20%>$title</th>";
	if($$varname  || isset($$varname)) {
		$form .= "<td><input class=textinput name=".$varname."2 value=".$$varname."></input></td>";
	} else {
		$form .= "<td></td>";	
	}
	if($end == '1') {
		$form .= '';		
	} elseif ($end == '2') {
		$form .= '</tr>';
	} elseif ($end == '3') {
		$form .= '</tr><tr>';	
	}
	echo $form;	
}

function writefile($filename, $writetext, $openmod='w') {
	if(@$fp = fopen($filename, $openmod)) {
		flock($fp, 2);
		fwrite($fp, $writetext);
		fclose($fp);
		return true;
	} else {
		return false;
	}
}

function xml2array($xml) {
	$arr = xml_unserialize($xml, 1);
	preg_match('/<error errorCode="(\d+)" errorMessage="([^\/]+)" \/>/', $xml, $match);
	$arr['error'] = array('errorcode' => $match[1], 'errormessage' => $match[2]);
	return $arr;
}

function getbakurl($whereis,$action) {
	if ($whereis != 'is_uc') {
		require_once './uc_client/client.php';
		require_once './uc_client/model/base.php';
	} else {
		define('IN_UC',TRUE);
		define('UC_ROOT','./');
		require_once './model/base.php';	
	}

	$base = new base();
	$salt = substr(uniqid(rand()), -6);
	$action = !empty($action) ? $action : 'export';
	$url = 'http://'.$_SERVER['HTTP_HOST'].str_replace('uctools.php', 'api/dbbak.php', $_SERVER['PHP_SELF']);
	if($whereis == 'is_dz') {
		$apptype = 'discuz';
	} elseif ($whereis == 'is_uc') {
		$apptype = 'ucenter';
	} elseif ($whereis == 'is_uch') {
		$apptype = 'uchome';
	} elseif ($whereis == 'is_ss') {
		$apptype = 'supesite';
	}
	$url .= '?apptype='.$apptype;
	$code = $base -> authcode('&method='.$action.'&time='.time(), 'ENCODE', UC_KEY);
	$url .= '&code='.urlencode($code);
	return $url;
}

function dobak($url,$num = '1') {
	global $whereis;
	$num = !empty($num) ? $num : '0';
	$return = file_get_contents($url);
	if($whereis != 'is_uc') {
		require_once './uc_client/lib/xml.class.php';
	} else {
		require_once './lib/xml.class.php';	
	}
	$arr = xml2array($return);
	
	if($arr['error']['errormessage'] == 'explor_success') {
		echo "<div class=\"specialdiv\">������ʾ��<ul>
			<li>>>>>>>>>�������<<<<<<<<</li>
			<li>>>>>>>>>����".$num."���ļ�<<<<<<<<</li>
			</ul></div>";
	} else {
		$num ++;
		echo "<div class=\"specialdiv\">������ʾ��<ul>
			<li>".$arr['fileinfo']['file_name']."......".$arr['error']['errormessage']."</li>
			</ul></div>";
	}
	if($arr['nexturl']) {
		$url = './uctools.php?action=all_backup&nexturl='.urlencode($arr['nexturl']).'&num='.$num;
		show_tools_message($arr['fileinfo']['file_name'],$url,$time = 2000);
	}
}
	
function getgpc($k, $var='G') {
	switch($var) {
		case 'G': $var = &$_GET; break;
		case 'P': $var = &$_POST; break;
		case 'C': $var = &$_COOKIE; break;
		case 'R': $var = &$_REQUEST; break;
	}
	return isset($var[$k]) ? $var[$k] : NULL;
}

function getlistarray($whereis,$type) {
	global $dz_version,$ss_version,$uch_version;
	if($whereis == 'is_dz' && $dz_version >= '710') {
		if($type == 'datago') {
			$list = array(
				array('advertisements','parameters','advid'),
				array('request','value','variable'),
				array('settings','value','variable'),
			);
		}
	} elseif($whereis == 'is_uch' && $uch_version >= '15') {
		if($type == 'datago') {
			$list = array(
				array('ad','adcode','adid'),
				array('blogfield','tag','blogid'),
				array('blogfield','related','blogid'),
				array('feed','title_data','feedid'),
				array('feed','body_data','feedid'),
				array('share','body_data','sid'),
			);	
		}
	} elseif($whereis == 'is_uc') {
		if($type == 'datago') {
			$list = array(
				array('feed','title_data','feedid'),
				array('feed','body_data','feedid'),
				array('settings','v','k'),
			);
		}
	} elseif($whereis == 'is_ss' && $ss_version >=70) {
		if($type == 'datago') {
			$list = array(
				array('ads','parameters','adid'),
				array('blocks','blocktext','blockid'),
			);
		}
	}
	return $list;
}

function _serialize($str) {
	global $dbcharset,$todbcharset;
	$charset = $dbcharset == 'utf8' ? 'utf-8':$dbcharset;
	$tempdbcharset = $todbcharset == 'utf8' ? 'utf-8':$todbcharset;
	$charset = strtoupper($charset);
	$tempdbcharset = strtoupper($tempdbcharset);
	$temp = iconv($charset,$tempdbcharset,$str[2]);
	$l = strlen($temp);
	return 's:'.$l.':"'.$str[2].'";';
}

function infobox($str,$link) {
	if($link) {
		$button = "<input class='button' type='submit' onclick=\"location.href='".$link."'\" value='��ʼ' name='submit'/>";	
	}
	echo "<div class='infobox'><p>$str</p>$button</div>";
}

//������ݱ�
function checktable($table, $loops = 0,$doc) {
	global $db, $nohtml, $simple, $counttables, $oktables, $errortables, $rapirtables;
	$query = mysql_query("show create table $table");
	if($createarray = mysql_fetch_array($query)) {
		if(strpos($createarray[1], 'TYPE=HEAP')) {	
		   $counttables --;
			return ;
		}
	}
	$result = mysql_query("CHECK TABLE $table");
	if(!$result) {
		$counttables --;
		return ;
	}
	$message = "\n>>>>>>>>>>>>>Checking Table $table\r\n---------------------------------\r\n";
	@writefile($doc,$message,'a');
	$error = 0;
	while($r = mysql_fetch_row($result)) {
		if($r[2] == 'error') {
			if($r[3] == "The handler for the table doesn't support check/repair") {
				$r[2] = 'status';
				$r[3] = 'This table does not support check/repair/optimize';
				unset($bgcolor);
				$nooptimize = 1;
			} else {
				$error = 1;
				$bgcolor = 'red';
				unset($nooptimize);
			}
			$view = '����';
			$errortables += 1;
		} else {
			unset($bgcolor);
			unset($nooptimize);
			$view = '����';
			if($r[3] == 'OK') {
				$oktables += 1;
			} elseif($r[3] == 'The storage engine for the table doesn\'t support check') {
				$oktables += 1;
			}
		}
		$message = "$r[0] | $r[1] | $r[2] | $r[3]\r\n";
		@writefile($doc,$message,'a');
	}
	if($error) {
		$message = ">>>>>>>>�����޸��� / Repairing Table $table\r\n";
		@writefile($doc,$message,'a');
		$result2=mysql_query("REPAIR TABLE $table");
		while($r2 = mysql_fetch_row($result2)) {
			if($r2[3] == 'OK') {
				$bgcolor='blue';
				$rapirtables += 1;
			} else {
				unset($bgcolor);
			}
			$message = "$r2[0] | $r2[1] | $r2[2] | $r2[3]\r\n";
			@writefile($doc,$message,'a');
		}
	}
	if(($result2[3] == 'OK'||!$error)&&!$nooptimize) {
		$message = ">>>>>>>>>>>>>Optimizing Table $table\r\n";
		@writefile($doc,$message,'a');
		$result3 = mysql_query("OPTIMIZE TABLE $table");
		$error = 0;
		while($r3 = mysql_fetch_row($result3)) {
			if($r3[2] == 'error') {
				$error = 1;
				$bgcolor = 'red';
			} else {
				unset($bgcolor);
			}
			$message = "$r3[0] | $r3[1] | $r3[2] | $r3[3]\r\n\r\n";
			@writefile($doc,$message,'a');
		}
	}
	if($error && $loops) {
		checktable($table,($loops-1),$doc);
	}
}
?>