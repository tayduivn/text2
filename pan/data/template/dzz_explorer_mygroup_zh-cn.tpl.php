<?php if(!defined('IN_DZZ')) exit('Access Denied'); /*a:2:{s:66:"C:\wwwroot\lvyongjian.xyz\pan\/./dzz/explorer/template/mygroup.htm";i:1613354169;s:75:"C:\wwwroot\lvyongjian.xyz\pan\/./dzz/explorer/template/noFilePage-group.htm";i:1613354169;}*/?>
<div class="middlebottomMenu  scroll-y">
<div class="middle-padding-left height-100 padding-left-1">
        <div class="middletopMenu">
            <div class="uploadMenu clearfix">
            <div class="public-sizeMenu clearfix">
            	<div class="public-allgroup">
                	 		
                	 		<div class="dropdown">
                <button type="text" class="form-control" data-toggle="dropdown" value="全部群组" readonly id="mygrouptext">
                	<span class="dropdown_text">全部群组</span>
                <i class="dzz dzz-arrow-dropdown input-icon"></i>
                </button>
                <ul class="dropdown-menu group-dropdownbutton" id="mygroupsearch">
                	<li><a href="javascript:;" data-val="all">全部</a></li>
                	<li><a href="javascript:;" data-val="my">我创建的</a></li>
                	<li><a href="javascript:;" data-val="manage">我管理的</a></li>
                	<li><a href="javascript:;" data-val="partake">我参与的</a></li>
                </ul>
               </div>
            </div>                
                	<div class="groupmanage-time">
                		<div class="group-time-animate">
                            <input type="text" id="mygroupDate" class="form-control"
                                    placeholder="开始时间"/>
                        </div>
                        <label class="group-time-animate-label"><span class="dzz dzz-vline"></span></label>
                        <div class="group-time-animate">
                            <input type="text" id="mygroupDate1" class="form-control"
                                    placeholder="结束时间"/>
                        </div>               		
                	</div>
                	
</div>

            </div>
        </div>
       <!--文件夹列表开始-->
        <div class="group-filemember " id="group-filemember">
            <div class="group-filemember-header">
                <table class="table" width="100%" border="0">
                    <thead>
                    <tr>	                        
                        <th class="member_header member_header_0" width="50%">
                            <div class="member_header_td_div">
                                <span class="member_header_title">群组名称</span>
                                <a class="detail_header_icon" data-val="0" style="display: inline-block;"><span
                                            class="dzz dzz-expand-more"></span></a>
                            </div>
                        </th>
                        <th class="member_header_1" width="20%">
                            <div class="member_header_td_div">
                                <span class="member_header_type">创建者</span>
                            </div>
                        </th>
                        <th class="member_header_2" width="10%">
                        	<div class="member_header_td_div">
                                <span class="member_header_title">成员数</span>
                            </div>
                        	</th>
                        <th class="member_header member_header_3" width="20%">
                        	<div class="member_header_td_div">
                                <span class="member_header_title">创建日期</span>
                                <a class="detail_header_icon" data-val="1"><span
                                            class="dzz dzz-expand-more"></span></a>
                            </div>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>

<div class="recent-con">
                <table class="table" width="100%" border="0">
                    <tbody id="grouplist"><?php if(is_array($groups)) foreach($groups as $v) { ?>                    <tr  class="member_tr Icoblock" onclick="openGroup(this)" data-gid="<?php echo $v['orgid'];?>">
                        
                        <td class="member_item_td" valign="middle" width="50%" style="position: relative;">
                            <div class="member_item_td_div member_item_td_name">
                                <a href="javascript:;" style="float: left;"><?php echo avatar_group($v[orgid]);?>                                </a>
                                <a href="javascript:;" id="" class="member_text member_item_name_text IcoText_folder"> <?php echo $v['orgname'];?></a>
                            </div>
                        </td>
                        <td class="member_item_td" valign="middle" width="20%">
                            <span><?php echo $v['creater'];?></span>
                        </td>
                    	<td class="member_item_td" valign="middle" width="10%">
                            <span><?php echo $v['usernum'];?></span>
                        </td>
                        <td class="member_item_td" valign="middle" width="20%">
                            <span><?php echo dgmdate($v['dateline'],'Y-m-d');?></span>
                        </td>
                    </tr>
<?php } if($groupsnumber == 0) { ?>
<tr class="emptyshare">
<td colspan="6" style="border: none;"><div class="emptyPage">
    <img src="<?php echo MOD_PATH;?>/img/noFilePage-group.png">
    <p class="emptyPage-text">还没有群组</p>
</div></td>
</tr>
<?php } ?>
                    </tbody>
                </table>
</div>
        </div>
        <!--文件夹列表结束-->
    </div>
</div>
<div id="template_nofile_notice" style="display:none">
<tr class="emptyshare">
<td colspan="6" style="border: none;"><div class="emptyPage">
    <img src="<?php echo MOD_PATH;?>/img/noFilePage-group.png">
    <p class="emptyPage-text">还没有群组</p>
</div></td>
</tr>
</div>
<script type="text/javascript">
jQuery(document).ready(function (e) {
    $('.input-black').each(function() {			
InputAnimate.init($(this));			
});
});
 $("#mygroupDate,#mygroupDate1").datepicker({ //添加日期选择功能
    numberOfMonths: 1, //显示几个月
    showButtonPanel: false, //是否显示按钮面板
    dateFormat: 'yy-mm-dd', //日期格式
    clearText: "清除", //清除日期的按钮名称
    closeText: "关闭", //关闭选择框的按钮名称
    yearSuffix: '年', //年的后缀
    showMonthAfterYear: true, //是否把月放在年的后面
    constrainInput: true,
    maxDate: new Date(),
    setDate: 'date',

});
var groupsearch = {'disp':0,'asc':0,'page':0,'search':0,'after':0,'before':0};
$('#mygroupDate').change(function () {
groupsearch.after = $(this).val();
searchGroupdata();
})
$('#mygroupDate1').change(function () {
groupsearch.before = $(this).val();
searchGroupdata();
})
//点击上下图标
$(document).off('click.mygroupclick').on('click.mygroupclick', '.group-filemember-header .member_header', function () {
        $(this).closest('.group-filemember-header tr').children('.group-filemember-header .member_header').find('.detail_header_icon').hide();
        $(this).find('.detail_header_icon').css('display', 'inline-block');
        if ($(this).find('.dzz').hasClass('dzz-expand-more')) {
            $(this).find('.dzz').removeClass('dzz-expand-more').addClass('dzz-expand-less');
groupsearch.asc = 1;
        } else {
            $(this).find('.dzz').addClass('dzz-expand-more').removeClass('dzz-expand-less');
groupsearch.asc = 0;
        }

var dispval = $(this).find('.detail_header_icon').data('val');
groupsearch.disp = dispval;
searchGroupdata();
    })
function openGroup(obj){
var gid = $(obj).data('gid');
var hash = "#group&gid="+gid;
location.hash = hash;
}
function searchGroupdata(){
$.post(MOD_URL+'&op=mygroup&do=filelist',groupsearch,function(data){
$('#grouplist').html(data);
})
}
$(document).off('click.mygroups').on('click.mygroups','#mygroupsearch li',function(){
var searchval = $('a',this).data('val');
var text = $('a',this).text();
$('#mygrouptext span').html(text);
if(searchval == 'all'){
groupsearch.search = 0;
}else{
groupsearch.search = searchval;
}
searchGroupdata();
})
    //鼠标滑入效果
    $(document).on('mouseenter', '.group-filemember tr', function () {
        $(this).addClass('hover');
    })
    //鼠标滑出效果
    $(document).on('mouseleave', '.group-filemember tr', function () {
        $(this).removeClass('hover');
    })
</script>