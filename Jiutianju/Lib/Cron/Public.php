<?php

	 {
           $obj = D('Userintegrallibrary');
           $list = $obj->where(array('integral_library_surplus' => array('gt', 0),'closed' => 0))->order(array('library_id' => 'asc'))->select();
           if ($list) {
                $i = 0;
                foreach($list as $k => $v) {
					if ($v['integral_library_total_success'] >= $v['integral_library_total'] || $v['integral_library_day'] >= $v['integral_library_surplus']) {
						unset($lists[$k]);
					}
					$restore_time = NOW_TIME;//返还时间
                    $day_time = strtotime(TODAY) - 60 * 60 * 24;
                    $restore_date = date('Y-m-d', $day_time);
                    $intro = '每日返积分，返利日期：' . $restore_date.'，当前已返还天数:'.($v['integral_library_total_success']+1).'，当前还剩余天数:'.($v['integral_library_total']-$v['integral_library_total_success']-1);
                    $count = D('Userintegralrestore')->where(array('library_id' => $v['library_id'], 'restore_date' => $restore_date))->count();
                    if (!$count) {
                        if(D('Users')->add_Integral_restore($v['library_id'],$v['user_id'], $v['integral_library_day'], $intro, 0,$restore_date)) $i++;
                    }
                }
                
            }
    }

    ?>