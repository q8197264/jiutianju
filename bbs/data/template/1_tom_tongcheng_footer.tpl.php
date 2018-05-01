<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<section class="foot-sec"></section>
<footer class="footer">
   <section class="wrap">
        <section class="footer-bar">
             <ul>
                  <li <?php if($_GET['mod'] == 'index' ) { ?>class="on"<?php } ?>>
                      <a href="plugin.php?id=tom_tongcheng&amp;site=<?php echo $site_id;?>&amp;mod=index&amp;prand=<?php echo $prand;?>">
                          <font class="iconfont">
                          <?php if($_GET['mod'] == 'index' ) { ?>
                          <img src="source/plugin/tom_tongcheng/images/footer_nav_index_hover.png" style="width: 25px;">
                          <?php } else { ?>
                          <img src="source/plugin/tom_tongcheng/images/footer_nav_index.png" style="width: 25px;">
                          <?php } ?>
                          </font>
                          <section class="text1">首页</section>
                      </a>
                  </li>
                  <li <?php if($_GET['mod'] == 'search' ) { ?>class="on"<?php } ?>>
                      <a href="plugin.php?id=tom_tongcheng&amp;site=<?php echo $site_id;?>&amp;mod=search&amp;prand=<?php echo $prand;?>">
                          <font class="iconfont">
                          <?php if($_GET['mod'] == 'search' ) { ?>
                          <img src="source/plugin/tom_tongcheng/images/footer_nav_search_hover.png" style="width: 25px;">
                          <?php } else { ?>
                          <img src="source/plugin/tom_tongcheng/images/footer_nav_search.png" style="width: 25px;">
                          <?php } ?>
                          </font>
                          <section class="text1">分类</section>
                      </a>
                  </li>
                  <li>
                      <?php if($_GET['mod'] == 'list' ) { ?>
                      <a href="<?php echo $fabuUrl;?>">
                      <?php } else { ?>
                      <a href="plugin.php?id=tom_tongcheng&amp;site=<?php echo $site_id;?>&amp;mod=fabu&amp;prand=<?php echo $prand;?>">
                      <?php } ?>
                        <section class="img"></section>
                        <section class="text1">发布</section>
                      </a>
                  </li>
                  <li <?php if($_GET['mod'] == 'message' ) { ?>class="on"<?php } ?>>
                      <a href="plugin.php?id=tom_tongcheng&amp;site=<?php echo $site_id;?>&amp;mod=message&amp;prand=<?php echo $prand;?>">
                        <font class="iconfont">
                        <?php if($pmNewNum > 0 ) { ?><i><?php echo $pmNewNum;?></i><?php } ?>
                        <?php if($_GET['mod'] == 'message' ) { ?>
                          <img src="source/plugin/tom_tongcheng/images/footer_nav_message_hover.png" style="width: 25px;">
                          <?php } else { ?>
                          <img src="source/plugin/tom_tongcheng/images/footer_nav_message.png" style="width: 25px;">
                          <?php } ?>
                        </font>
                        <section class="text1">消息</section>
                      </a>
                  </li>
                  <li <?php if($_GET['mod'] == 'personal' ) { ?>class="on"<?php } ?>>
                      <a href="plugin.php?id=tom_tongcheng&amp;site=<?php echo $site_id;?>&amp;mod=personal&amp;prand=<?php echo $prand;?>">
                          <font class="iconfont">
                          <?php if($_GET['mod'] == 'personal' ) { ?>
                          <img src="source/plugin/tom_tongcheng/images/footer_nav_personal_hover.png" style="width: 25px;">
                          <?php } else { ?>
                          <img src="source/plugin/tom_tongcheng/images/footer_nav_personal.png" style="width: 25px;">
                          <?php } ?>
                          </font>
                          <section class="text1">我的</section>
                      </a>
                  </li>
             </ul>
        </section>
   </section>
</footer>