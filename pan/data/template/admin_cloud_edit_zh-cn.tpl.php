<?php if(!defined('IN_DZZ')) exit('Access Denied'); /*a:10:{s:62:"C:\wwwroot\lvyongjian.xyz\pan\/./admin/cloud/template/edit.htm";i:1613353803;s:85:"C:\wwwroot\lvyongjian.xyz\pan\/./core/template/default/common/header_simple_start.htm";i:1613353801;s:83:"C:\wwwroot\lvyongjian.xyz\pan\/./core/template/default/common/header_simple_end.htm";i:1613353801;s:79:"C:\wwwroot\lvyongjian.xyz\pan\/./core/template/default/common/commer_header.htm";i:1613353801;s:69:"C:\wwwroot\lvyongjian.xyz\pan\/./admin/cloud/template/header_left.htm";i:1613353803;s:0:"";b:0;s:78:"C:\wwwroot\lvyongjian.xyz\pan\/./core/template/default/common/header_right.htm";i:1613353801;s:62:"C:\wwwroot\lvyongjian.xyz\pan\/./admin/cloud/template/left.htm";i:1613353803;s:70:"C:\wwwroot\lvyongjian.xyz\pan\/./admin/cloud/template/right_header.htm";i:1613353803;s:79:"C:\wwwroot\lvyongjian.xyz\pan\/./core/template/default/common/footer_simple.htm";i:1613353801;}*/?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title><?php if(!empty($navtitle)) { ?><?php echo $navtitle;?> - <?php } ?><?php echo $_G['setting']['sitename'];?> </title>
<meta name="keywords" content="<?php if(!empty($_G['setting']['metakeywords'])) { echo htmlspecialchars($_G['setting']['metakeywords']); } ?>" />
<meta name="description" content="<?php if(!empty($_G['setting']['metadescription'])) { echo htmlspecialchars($_G['setting']['metadescription']); ?> <?php } ?>" />
<meta name="generator" content="DzzOffice" />
<meta name="author" content="DzzOffice" />
<meta name="copyright" content="2012-<?php echo dgmdate(TIMESTAMP,'Y-m-d');?> www.dzzoffice.com" />
<meta name="MSSmartTagsPreventParsing" content="True" />
<meta http-equiv="MSThemeCompatible" content="Yes" />
<meta name="renderer" content="webkit">
<base href="<?php echo $_G['siteurl'];?>" />
<link rel="stylesheet" type="text/css" href="static/bootstrap/css/bootstrap.min.css?<?php echo VERHASH;?>">
<link rel="stylesheet" type="text/css" href="static/css/app_manage.css?<?php echo VERHASH;?>">
<link rel="stylesheet" type="text/css" href="static/dzzicon/icon.css?<?php echo VERHASH;?>"/>
<link rel="stylesheet" href="static/popbox/popbox.css">
<script type="text/javascript" src="static/jquery/jquery.min.js?<?php echo VERHASH;?>" ></script>
<script type="text/javascript" src="static/jquery/jquery.json-2.4.min.js?<?php echo VERHASH;?>" ></script>
<script type="text/javascript">var DZZSCRIPT='<?php echo DZZSCRIPT;?>',LANG='<?php echo $_G['language'];?>', STATICURL = 'static/', IMGDIR = '<?php echo $_G['setting']['imgdir'];?>', VERHASH = '<?php echo VERHASH;?>', charset = '<?php echo CHARSET;?>', dzz_uid = '<?php echo $_G['uid'];?>', cookiepre = '<?php echo $_G['config']['cookie']['cookiepre'];?>', cookiedomain = '<?php echo $_G['config']['cookie']['cookiedomain'];?>', cookiepath = '<?php echo $_G['config']['cookie']['cookiepath'];?>',attackevasive = '<?php echo $_G['config']['security']['attackevasive'];?>', disallowfloat = '<?php echo $_G['setting']['disallowfloat'];?>',  REPORTURL = '<?php echo $_G['currenturl_encode'];?>', SITEURL = '<?php echo $_G['siteurl'];?>', JSPATH = '<?php echo $_G['setting']['jspath'];?>',MOD_PATH='<?php echo MOD_PATH;?>',APP_URL='<?php echo MOD_URL;?>',MOD_URL='<?php echo MOD_URL;?>';</script>
<script type="text/javascript" src="./data/template/admin_cloud_edit_header_zh-cn.js" ></script><script type="text/javascript" src="static/js/header.js?<?php echo VERHASH;?>" ></script>
<script type="text/javascript" src="static/popbox/jquery.popbox.js?<?php echo VERHASH;?>" ></script>
<!--[if lt IE 9]>
  <script src="static/bootstrap/js/html5shiv.min.js" ></script>
  <script src="static/bootstrap/js/respond.min.js" ></script>
<![endif]--><?php Hook::listen('header_tpl') ?> <script type="text/javascript">
 if(!!window.ActiveXObject || "ActiveXObject" in window){
 try{$.ajaxSetup({ cache: false });}catch(e){}
 window.MSIE=1;
 } 
</script>
<link href="static/css/common.css?<?php echo VERHASH;?>" rel="stylesheet" media="all">
<script type="text/javascript" src="static/js/jquery.leftDrager.js?<?php echo VERHASH;?>" ></script><script type="text/javascript" src="./data/template/admin_cloud_edit_common_zh-cn.js" ></script><script type="text/javascript" src="static/js/common.js?<?php echo VERHASH;?>" ></script>
</head>
<body id="nv_<?php echo $_G['basescript'];?>" class="<?php echo $bodyClass;?>" >
<div id="append_parent" style="z-index:99999;"></div>
<div id="ajaxwaitid" style="z-index:99999;"></div>
<nav class="navbar navbar-inverse resNav bs-top-container" >
<div class="resNav-item resNav-left">     
    <ul class="nav navbar-nav navbar-nav-left" style="min-width:168px"> 
    <li>
       <a class="leftTopmenu" href="index.php?mod=appmanagement" style="padding:8px"><div class="gb_fc"><span class="dzz dzz-chevron-left" style="display:block"></span></div></a>
    </li>
    <li>
        <a href="<?php echo MOD_URL;?>">??????????????????</a>
    </li> 
</ul>    </div>
    <div class="resNav-item resNav-center">    </div>
     <div class="resNav-item resNav-right">
     <ul class="nav navbar-nav">
<li>
<a href="javascript:;">
<span class="navbar-borderleft"></span>
</a>
</li>
<li class="app_popup-parent">

<a href="javascript:;" id="desktop_app" data-href="index.php?mod=system&amp;op=app_ajax&amp;operation=app" class="app_popup_icon js-popbox" data-placement="bottom" data-trigger="focus" data-auto-adapt="true" data-toggle="popover"><span class="dzz dzz-apps basil"></span></a>
</li>
<li>
<a href="javascript:;" id="dzz_notification" data-href="index.php?mod=system&amp;op=notification&amp;filter=new" class="navbar-notice js-popbox" data-placement="bottom" data-trigger="focus" data-auto-adapt="true" data-toggle="popover">
<span class="dzz dzz-notifications"></span>
<span class="badge hide">&nbsp;</span>
</a>
</li>
<li>
<a href="javascript:;" class="imgHeight js-popbox" data-href="user.php?mod=space&amp;op=navmenu&amp;modname=<?php echo MOD_NAME;?>" data-placement="bottom" data-trigger="focus" data-auto-adapt="true" data-toggle="popover"><?php echo avatar_block($_G[uid]);?></a>
</li>
</ul></div>
</nav>


<script type="text/javascript">
jQuery(document).ready(function(e) {
    _header.init('<?php echo FORMHASH;?>');//?????????????????????
    //_header.Topcolor();
//_notice.init();
jQuery(".resNav .js-popbox").each(function(){
jQuery(this).popbox();
});
_notice.getNotificationCount();
});
_notice={};
_notice.flashStep=1;
_notice.checkurl='index.php?mod=system&op=notification&filter=checknew';
_notice.normalTitle= document.title;
_notice.getNotificationCount=function(){
jQuery.getJSON(_notice.checkurl,function(json){
var sum=parseInt(json.sum);
_notice.showTips(sum);
if(json.timeout>0) window.setTimeout(_notice.getNotificationCount,json.timeout);
});
}
_notice.showTips=function(sum){
if(sum>0){
jQuery('#dzz_notification>span.badge').html(sum).removeClass('hide');
jQuery('#dzz_notification>span.dzz').hide();
//_notice.flashTitle();
}else{
jQuery('#dzz_notification>span.badge').addClass('hide');
jQuery('#dzz_notification>span.dzz').show();
//_notice.flashTitle(1);
}
}
_notice.flashTitle=function(flag){
//??????????????????????????????title????????????????????????????????????title????????????
if(flag ||???CurrentActive){//??????????????????
document.title=_notice.normalTitle;
_notice.flashTitleRun = false;
return;//????????????
}
_notice.flashTitleRun = true;
_notice.flashStep++;
if (_notice.flashStep==3) {_notice.flashStep=1;}
if (_notice.flashStep==1) {document.title="????????????????????????";}
if (_notice.flashStep==2) {document.title="????????????????????????";}
setTimeout(function(){_notice.flashTitle();},500);  //??????
}
</script><style type="text/css">
/*??????*/
/*.bs-main-container{
    padding: 15px 30px;
}*/

/*??????*/
</style>
<div class="bs-container clearfix">
  <div class="bs-left-container  clearfix"> 
    <?php $bz=$bz?$bz:'dzz';?><?php $clouds=DB::fetch_all("select * from ".DB::table('connect')." where 1 order by disp");?><ul class="nav-stacked">
  <li <?php if($operation=='setting') { ?>class="active"<?php } ?>> <a href="<?php echo BASESCRIPT;?>?mod=cloud&operation=setting">?????????</a>
  </li>
  <?php if(is_array($clouds)) foreach($clouds as $value) { ?> 
  <li <?php if($operation!='setting' && $bz==$value['bz']) { ?>class="active"<?php } ?>> <a href="<?php echo BASESCRIPT;?>?mod=cloud&op=edit&bz=<?php echo $value['bz'];?>"><?php echo $value['name'];?></a>
  </li>
  <?php } ?>
</ul>
 
  </div>
  <div class="left-drager">
  </div>
  <div class="bs-main-container  clearfix" > 
    <?php if($cloud['type']=='local') { ?>
    <div class="main-header clearfix"> 
      <ul class="nav navbar-nav nav-pills-bottomguide">
  <li <?php if($_GET['op']=='edit') { ?>class="active"<?php } ?>><a href="<?php echo BASESCRIPT;?>?mod=cloud&op=edit&bz=<?php echo $bz;?>"> ??????</a>
  </li>
  <li <?php if($_GET['op']=='space' || $_GET['op']=='spaceadd') { ?>class="active"<?php } ?>><a href="<?php echo BASESCRIPT;?>?mod=cloud&op=space">????????????</a>
  </li>
  <li <?php if($_GET['op']=='router' || $_GET['op']=='routeredit') { ?>class="active"<?php } ?>><a href="<?php echo BASESCRIPT;?>?mod=cloud&op=router">????????????</a>
  </li>
  <li <?php if($_GET['op']=='movetool') { ?>class="active"<?php } ?>><a href="<?php echo BASESCRIPT;?>?mod=cloud&op=movetool">????????????</a>
  </li>
</ul> 
    </div>
    <div class="main-content" style="padding:20px;border-top:1px solid #FFF;">
      <form id="cpform" action="<?php echo BASESCRIPT;?>?mod=cloud&op=edit&bz=<?php echo $bz;?>" class="form-horizontal form-horizontal-left" method="post" name="cpform">
        <input type="hidden" value="<?php echo FORMHASH;?>" name="formhash">
        <input type="hidden" value="editsubmit" name="true">
        <dl>
          <dt>??????:</dt>
          <dd class="clearfix">
            <input type="text"  name="name"  class="form-control"  value="<?php echo $cloud['name'];?>">
          </dd>
        </dl>
        <dl>
          <dt>?????????:</dt>
          <dd class="clearfix">
            <input type="text"  name="" class="form-control" value="<?php echo $cloud['bz'];?>"  disabled="disabled">
          </dd>
          <dd class="clearfix">
            <ul class="help-block ">
              <li>????????????????????????????????????????????????,?????????????????????</li>
							<li>?????????api??????????????????????????????????????????</li>
							<li>?????????????????????????????????????????????api????????????</li>
            </ul>
          </dd>
        </dl>
        <input class="btn btn-primary" name="editsubmit" value="????????????" type="submit">
      </form>
    </div>
    <?php } elseif($cloud['type']=='pan') { ?>
    <div class="main-header clearfix" >
      <ul class="nav nav-pills nav-pills-bottomguide">
        <li <?php if(empty($_GET['do'])) { ?>class="active"<?php } ?>> <a href="<?php echo BASESCRIPT;?>?mod=cloud&op=edit&bz=<?php echo $bz;?>">??????</a>
        </li>
        <?php if($cloud['available']>0) { ?> 
        <li <?php if($_GET['do']=='usercloud' ) { ?>class="active"<?php } ?>> <a href="<?php echo BASESCRIPT;?>?mod=cloud&op=edit&bz=<?php echo $bz;?>&do=usercloud">????????????</a>
        </li>
        <?php } ?>
      </ul>
    </div>
    <div class="main-content clearfix" style="padding:15px;border-top:1px solid #FFF;"> 
      <?php if($_GET['do']=='usercloud') { ?>
      <form id="appform" name="appform" class="form-horizontal form-horizontal-left" style="margin:-15px -15px 0;" action="<?php echo BASESCRIPT;?>?mod=cloud&op=edit&do=usercloud" method="post" >
        <input type="hidden" name="cloudsubmit" value="true" />
        <input type="hidden" name="bz" value="<?php echo $bz;?>" />
        <input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
        <table class="table table-hover">
          <thead>
            <tr>
              <th width="30"></th>
              <th>??????</th>
              <th>?????????</th>
              <th>????????????</th>
            </tr>
          </thead>
          <?php if(is_array($list)) foreach($list as $value) { ?>          <tr>
            <td width="40"><input type="checkbox"  name="delete[]" value="<?php echo $value['id'];?>" /></td>
            <td ><img src="<?php echo $value['img'];?>" /><?php echo $value['cloudname'];?></td>
            <td><?php echo $value['username'];?></td>
            <td> <?php echo $value['dateline'];?> </td>
          </tr>
          <?php } ?>
          <thead>
          <td colspan="5"><input type="checkbox" name="chkall" id="chkall" onclick="checkAll('prefix', this.form, 'del')">
              &nbsp;?????? &nbsp;&nbsp;&nbsp;
              <input type="submit" class="btn btn-primary" value="??????" />
              <?php echo $multi;?> </td>
              </thead>
        </table>
      </form>
      <?php } else { ?>
      <form id="cpform" action="<?php echo BASESCRIPT;?>?mod=cloud&op=edit&bz=<?php echo $bz;?>" class="form-horizontal form-horizontal-left"   method="post" name="cpform">
        <input type="hidden" value="<?php echo FORMHASH;?>" name="formhash">
        <input type="hidden" value="editsubmit" name="true">
        <dl>
          <dt>??????:</dt>
          <dd class="clearfix">
            <input type="text"  name="name" class="form-control"  value="<?php echo $cloud['name'];?>">
          </dd>
        </dl>
        <dl>
          <dt>????????????????????????:</dt>
          <dd class="clearfix"> 
            <?php if($cloud['available']>0) { ?>
            <label class="radio radio-inline"><input type="radio"  name="available"  value="2" <?php if($cloud['available']>1) { ?>checked="checked"<?php } ?> />???</label>
            <label class="radio radio-inline"><input type="radio"  name="available"  value="1" <?php if($cloud['available']<2) { ?>checked="checked"<?php } ?> />???</label>
            <?php } else { ?>
            <input type="hidden"  name="available"  value="<?php echo $cloud['available'];?>" />
            <?php } ?>
            <ul class="help-block">
              <?php if($cloud['warning']) { ?>
              <li><span class=" text-danger"><?php echo $cloud['warning'];?></span></li>
              <?php } ?> 
              <?php if($cloud['available']<1) { ?>
              <li><span class=" text-danger">????????? ???<?php echo $cloud['name'];?>??? ?????????????????????</span></li>
              <?php } else { ?>
              <li>??????????????????,???????????????????????????????????????????????????????????????</li>
              <?php } ?>
            </ul>
          </dd>
        </dl>
        <dl>
          <dt>?????????:</dt>
          <dd class="clearfix">
            <input type="text" class="form-control" name="bz" value="<?php echo $cloud['bz'];?>"  disabled="disabled">
          </dd>
        </dl>
        <dl>
          <dt>?????????:</dt>
          <dd class="clearfix">
            <input type="text"  name="root" class="form-control" value="<?php echo $cloud['root'];?>" >
            <ul class="help-block ">
              <li>????????????????????????????????????????????????????????????????????????????????????????????????</li>
								<li>??????????????????????????????apps/??????????????????/????????????</li>
								<li>?????????/apps/dzzoffice,????????????/</li>
								<li>?????????????????????????????????????????????api????????????</li>
            </ul>
          </dd>
        </dl>
        <dl>
          <dt>API Key:</dt>
          <dd class="clearfix">
            <input type="text"  name="key" class="form-control"  value="<?php echo $cloud['key'];?>" >
            <ul class="help-block ">
              <li>???API????????????????????????API KEY</li>
								<li>??????????????????????????????http://open.baidu.com ??????????????????????????????????????????????????????????????????API Key???Secret Key</li>
            </ul>
          </dd>
        </dl>
        <dl>
          <dt>Secret Key</dt>
          <dd class="clearfix">
            <input type="text"  name="secret" class="form-control" value="<?php echo $cloud['secret'];?>" class="span4">
            <ul class="help-block ">
              <li>???API????????????????????????Secret Key</li>
								<li>??????????????????????????????http://open.baidu.com ??????????????????????????????????????????????????????????????????API Key???Secret Key</li>
            </ul>
          </dd>
        </dl>
        <dl>
          <dt>???????????????</dt>
          <dd class="clearfix">
            <input type="text" class="form-control" name="dname" value="<?php echo $cloud['dname'];?>" class="span4">
            <ul class="help-block ">
              <li>?????????????????????????????????????????????????????????????????????dzz_connect_pan,??????????????????connect_pan</li>
            </ul>
          </dd>
        </dl>
        <input class="btn btn-primary" name="editsubmit" value="????????????" type="submit">
      </form>
      <?php } ?> 
    </div>
    <?php } else { ?>
    <div class="main-header clearfix">
      <ul class="nav nav-pills nav-pills-bottomguide">
        <li <?php if(empty($_GET['do'])) { ?>class="active"<?php } ?>> <a href="<?php echo BASESCRIPT;?>?mod=cloud&op=edit&bz=<?php echo $bz;?>">??????</a>
        </li>
        <?php if($cloud['available']>0) { ?> 
        <li <?php if($_GET['do']=='usercloud' ) { ?>class="active"<?php } ?>> <a href="<?php echo BASESCRIPT;?>?mod=cloud&op=edit&bz=<?php echo $bz;?>&do=usercloud">????????????</a>
        </li>
        <?php } ?>
      </ul>
    </div>
    <div class="main-content" style="padding:15px;border-top:1px solid #FFF;"> 
      <?php if($_GET['do']=='usercloud') { ?>
      <form id="appform" name="appform" class="form-horizontal form-horizontal-left" style="margin:-15px -15px 0;" action="<?php echo BASESCRIPT;?>?mod=cloud&op=edit&do=usercloud" method="post" >
        <input type="hidden" name="cloudsubmit" value="true" />
        <input type="hidden" name="bz" value="<?php echo $bz;?>" />
        <input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
        <table class="table table-hover">
          <thead>
            <tr>
              <th width="30"></th>
              <th>??????</th>
              <th>?????????</th>
              <th>????????????</th>
            </tr>
          </thead>
          <?php if(is_array($list)) foreach($list as $value) { ?>          <tr>
            <td width="40"><input type="checkbox"  name="delete[]" value="<?php echo $value['id'];?>" /></td>
            <td ><img src="<?php echo $value['img'];?>" /><?php echo $value['cloudname'];?></td>
            <td><?php echo $value['username'];?></td>
            <td> <?php echo $value['dateline'];?> </td>
          </tr>
          <?php } ?>
          <thead>
          <td colspan="5"><input type="checkbox" name="chkall" id="chkall" onclick="checkAll('prefix', this.form, 'del')">
              &nbsp;??????&nbsp;&nbsp;&nbsp;
              <input type="submit" class="btn btn-primary" value="??????" />
              <?php echo $multi;?> </td>
              </thead>
        </table>
      </form>
      <?php } else { ?>
      <form id="cpform" action="<?php echo BASESCRIPT;?>?mod=cloud&op=edit&bz=<?php echo $bz;?>" class="form-horizontal form-horizontal-left" method="post" name="cpform">
        <input type="hidden" value="<?php echo FORMHASH;?>" name="formhash">
        <input type="hidden" value="editsubmit" name="true">
        <dl>
          <dt>??????:</dt>
          <dd class="clearfix">
            <input type="text" class="form-control" name="name"  value="<?php echo $cloud['name'];?>">
            <span class="help-inline text-muted">?????? </span> </dd>
        </dl>
        <dl>
          <dt>????????????????????????:</dt>
          <dd class="clearfix"> 
            <?php if($cloud['available']>0) { ?>
            <label class="radio radio-inline"> <input type="radio"  name="available"  value="2" <?php if($cloud['available']>1) { ?>checked="checked"<?php } ?> />???</label>
            <label class="radio radio-inline"> <input type="radio"  name="available"  value="1" <?php if($cloud['available']<2) { ?>checked="checked"<?php } ?> />???</label>
            <?php } else { ?>
            <input type="hidden"  name="available"  value="<?php echo $cloud['available'];?>" />
            <?php } ?>
            <ul class="help-block">
              <?php if($cloud['warning']) { ?>
              <li><span class=" text-danger"><?php echo $cloud['warning'];?></span></li>
              <?php } ?> 
              <?php if($cloud['available']<1) { ?>
              <li><span class=" text-danger">????????? ???<?php echo $cloud['name'];?>??? ?????????????????????</span></li>
              <?php } else { ?>
              <li>??????????????????,???????????????????????????????????????????????????????????????</li>
              <?php } ?>
              
            </ul>
          </dd>
        </dl>
        <dl>
          <dt>?????????:</dt>
          <dd class="clearfix">
            <input type="text"  name="bz" class="form-control" value="<?php echo $cloud['bz'];?>"  disabled="disabled">
            <ul class="help-block ">
              <li>????????????????????????????????????????????????,?????????????????????</li>
							<li>?????????api??????????????????????????????????????????</li>
							<li>?????????????????????????????????????????????api????????????</li>
            </ul>
          </dd>
        </dl>
        <input class="btn btn-primary" name="editsubmit" value="????????????" type="submit">
      </form>
      <?php } ?> 
    </div>
    <?php } ?> 
  </div>
</div>
<script type="text/javascript">  
jQuery('.left-drager').leftDrager_layout();
</script> 
<script src="static/bootstrap/js/bootstrap.min.js?<?php echo VERHASH;?>" ></script><?php output();?><?php updatesession();?><?php if(debuginfo()) { ?>
<script type="text/javascript">
try{
if(console && console.log){
console.log('Processed in <?php echo $_G['debuginfo']['time'];?> second(s), <?php echo $_G['debuginfo']['queries'];?> queries <?php if($_G['gzipcompress']) { ?>, Gzip On<?php } if(C::memory()->type) { ?>, <?php echo ucwords(C::memory()->type); ?> On<?php } ?>.');
}
}catch(e){}
</script>
<?php } ?>	
<?php if(!$_G['setting']['bbclosed']) { if(!isset($_G['cookie']['sendmail'])) { ?>
<script type="text/javascript" src="misc.php?mod=sendmail&rand=<?php echo $_G['timestamp'];?>" ></script>
<?php } ?>
     <script type="text/javascript" src="misc.php?mod=sendwx&rand=<?php echo $_G['timestamp'];?>" ></script>
<?php } if($_G['uid'] && $_G['adminid'] == 1) { if(!isset($_G['cookie']['checkupgrade'])) { ?>
<script type="text/javascript">jQuery.getScript('misc.php?mod=upgrade&action=checkupgrade&rand=<?php echo $_G['timestamp'];?>');</script>
<?php } if(!isset($_G['cookie']['checkappupgrade'])) { ?>
<script type="text/javascript">jQuery.getScript('misc.php?mod=upgrade&action=checkappupgrade&rand=<?php echo $_G['timestamp'];?>');</script>
<?php } if(!isset($_G['cookie']['upgradenotice'])) { ?>
<script type="text/javascript">
jQuery(document).ready(function(){
try{jQuery('#systemNotice').load('misc.php?mod=upgrade&action=upgradenotice');}catch(e){};	
});
</script>
<div id="systemNotice" class="systemNotice" style="position: fixed;right:10px;bottom:10px;max-width:50%;box-shadow:0px 5px 10px RGBA(0,0,0,0.3);z-index:999999"></div>
<?php } } if($_G['setting']['statcode']) { ?>
<?php echo $_G['setting']['statcode'];?>
<?php } ?> 
</body>
</html> 