<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:37:"template/b_new/html/gbook\report.html";i:1577270742;}*/ ?>

<!--登录弹窗开始-->
<div class="mac_report reply_box">
    <form class="gbook_form">
        <textarea class="gbook_content" name="gbook_content"><?php echo $param['name']; ?></textarea>
        <div class="msg_code">
            <div class="remaining-r">还可输入<span class="gbook_remaining remaining " >200</span>字</div>
            <input type="text" name="verify" placeholder="验证码" class="verify"><img class="comm-code" src="<?php echo mac_url('verify/index'); ?>" data-role="<?php echo mac_url('verify/index'); ?>" title="看不清楚? 换一张！" onClick="this.src=this.src+'?v=<?php echo time(); ?>'" />
        </div>
        <div style="text-align: center;display: block;overflow: hidden;">
            <input type="button" class="gbook_submit submit_btn" style="width:100%" value="提交留言">
        </div>

    </form>
</div>
<!--登录弹窗结束-->
<script>
    $(function(){
        MAC.Gbook.Login = <?php echo $gbook['login']; ?>;
        MAC.Gbook.Verify = <?php echo $gbook['verify']; ?>;
        MAC.Gbook.Init();
    });$(".mac_pop_bg").click(function() {$(".mac_pop,.mac_pop_bg").remove();})	
	</script>
