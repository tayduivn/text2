<?php if(!defined('IN_DZZ')) exit('Access Denied'); /*a:3:{s:64:"C:\wwwroot\lvyongjian.xyz\pan\/./dzz/system/template/orgtree.htm";i:1613353802;s:85:"C:\wwwroot\lvyongjian.xyz\pan\/./core/template/default/common/header_simple_start.htm";i:1613353801;s:83:"C:\wwwroot\lvyongjian.xyz\pan\/./core/template/default/common/header_simple_end.htm";i:1613353801;}*/?>
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
<script type="text/javascript" src="./data/template/dzz_system_orgtree_header_zh-cn.js" ></script><script type="text/javascript" src="static/js/header.js?<?php echo VERHASH;?>" ></script>
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
<link href="static/jstree/themes/default/style.min.css?<?php echo VERHASH;?>" rel="stylesheet" media="all">
<style>
body{
height:100%;
width:100%;
margin:0;
overflow:auto;
/*padding-bottom:34px;*/
}
.jstree-default .jstree-icon:empty{
font-size: 20px;
}
.orgtree-search{
position:fixed;
width:100%;
left:0;
top:0;
background-color:#FFF;

}
.orgtree-search .form-control{
padding:5px 35px 5px 25px;
border:0;
border-bottom:1px solid #CCC;
box-shadow:none;
background-color: #FFFFFF;
}
.orgtree-search .form-control:focus{
box-shadow:none;
}
.orgtree-search .search{
position:absolute;
left:0px;
top:0px;
width:24px;
height:32px;
padding:8px 5px;
font-size: 18px;
color: rgba(78,85,99,0.65);
}
.orgtree-search .delete {
position: absolute;
right: 1px;
top: 0px;
width:24px;
height:32px;
padding:8px 5px;
font-size: 18px;
color: rgba(78,85,99,0.65);
}
.orgtree-search .delete:hover,.orgtree-search .search:hover{
color: rgba(78,85,99,1);
text-decoration: none;
}
.orgtree-search a:hover{
background:#F7F7F7;
}
.jstree-default-responsive .jstree-anchor>.jstree-themeicon{
background-size:auto;
}
.Topcarousel{
width: 24px;
height: 24px;
border-radius: 50%;
display: inline-block;
line-height: 24px;
    text-align: center;
    margin-right: 2px;
    color: #FFFFFF;
font-size:14px;
}
.iconFirstWord{
width: 24px;
height: 24px;
border-radius: 50%;
display: inline-block;
line-height: 24px;
    text-align: center;
    margin-right: 2px;
    color: #FFFFFF;
font-size:14px;
}
.jstree-default .jstree-icon:empty{
border-radius: 50%;
}
.jstree-default .jstree-node{
line-height: 2.4rem;
}

.jstree-default .jstree-node, .jstree-default .jstree-icon{
background-image:url(dzz/system/images/32px.png);
}
.jstree-default .jstree-last {
    background: 0 0;
}
.jstree-default .jstree-themeicon-custom {
    background-color: transparent;
    background-image: none;
}
#orgtree{
padding:15px;overflow:auto;height:100%;	
}
<?php if($_GET['nosearch']>0) { ?>
.orgtree-search{
display:none;
}
body{
padding-bottom:0;
}
<?php } ?>
</style>
<script type="text/javascript">
var ctrlid='<?php echo $_GET['ctrlid'];?>';
var multiple=parseInt('<?php echo $_GET['multiple'];?>')>0?true:false;
var nouser=parseInt('<?php echo $_GET['nouser'];?>')>0?1:0;
var stype='<?php echo $_GET['stype'];?>'?parseInt('<?php echo $_GET['stype'];?>'):0;//0:??????????????????????????????1??????????????????????????????2??????????????????
var range='<?php echo $_GET['range'];?>'?parseInt('<?php echo $_GET['range'];?>'):0;//0:??????????????????1????????????????????????2????????????
var moderator=parseInt('<?php echo $_GET['moderator'];?>')>0?1:0;
var callback_url = '<?php echo $callback_url;?>';
var ismobile='<?php echo $ismobile;?>'?1:0;
</script><script type="text/javascript" src="./data/template/dzz_system_orgtree_common_zh-cn.js" ></script><script type="text/javascript" src="static/js/common.js?<?php echo VERHASH;?>" ></script>
</head>
<body id="nv_<?php echo $_G['basescript'];?>" class="<?php echo $bodyClass;?>" >
<div id="append_parent" style="z-index:99999;"></div>
<div id="ajaxwaitid" style="z-index:99999;"></div><div id="orgtree" class="orgtree-container" style=""></div>
<?php if(intval($_GET['nosearch'])<1) { ?>
<div  class="orgtree-search">
        <a href="javascript:;" class="search" onclick="jstree_search();return false" title="??????"><i class="dzz dzz-search"></i></a>
        <a href="javascript:;" class="delete" onclick="jstree_search('stop');return false" title="???????????????"><i class="dzz dzz-close"></i></a>
        <input id="jstree_search_input" type="text" placeholder="?????????????????????" class="form-control" onkeyup="if(event.keyCode==13){jstree_search()}"  />
    </div>
<?php } ?>
<script type="text/javascript">

window.onesize=function(){
/*var clientHeight=parent.jQuery('#'+ctrlid+'_dropdown_menu').css('height');
if(isNaN(clientHeight)) clientHeight=jQuery('body').height();
jQuery('#orgtree').css('height',clientHeight-(jQuery('.orgtree-search').length>0?jQuery('.orgtree-search').outerHeight(true):0));
console.log('resize');*/
}
jQuery(document).ready(function(e) {
/*var clientHeight=parent.jQuery('#'+ctrlid+'_dropdown_menu').css('height');
if(isNaN(clientHeight)) clientHeight=jQuery('body').height();
jQuery('#orgtree').css('height',clientHeight-(jQuery('.orgtree-search').length>0?jQuery('.orgtree-search').outerHeight(true):0));*/
//	console.log(parseInt(parent.jQuery('#'+ctrlid+'_dropdown_menu').css('height')),jQuery('.orgtree-search').outerHeight(true));
jQuery("#orgtree").jstree({ 
"core" : {
"multiple" : multiple,
"check_callback" : false,
"themes" : { "responsive":false},
'data':function(node,cb){
var self=this;
jQuery.post(DZZSCRIPT+'?mod=system&op=orgtree&do=orgtree',{'id':node.id,'nouser':nouser,'moderator':moderator,'zero':'<?php echo $zero;?>','stype':stype,'range':range},function(json){
cb.call(this,json);
},'json');
}
  },
       "types": {
            "#": {
                "max_children": -1,
                "max_depth": -1,
                "valid_children": -1
            },
            "organization": {//??????
                "icon": "dzz dzz-account-box",
                "valid_children": ['depart','folder']
            },
            "department": {
                "icon": "dzz/system/images/department.png",
                "valid_children": ['depart','folder']
            },
            "group": {//??????
                "icon": "dzz dzz-group",
                "valid_children": ['folder']
            },
            "default": {//????????????
                "icon": "dzz dzz-account-circle",
                "valid_children": ['folder']
            },
        },
 "checkbox" : {
  "keep_selected_style" : false
  <?php if($_GET['stype']==1) { ?>
   ,"three_state": false//????????????????????????
           ,"tie_selection": false
   <?php } ?>
},

   "search":{ 
 "show_only_matches":true,
                "fuzzy":false,
                "ajax":{'url' : '<?php echo DZZSCRIPT;?>?mod=system&op=orgtree&do=search&stype='+stype+'&nouser='+nouser,'dataType':'json'}
   },
  "plugins" : ['types',"checkbox","search","wholerow"]
// List of active plugins

   });


jQuery("#orgtree").on('select_node.jstree',function(e,data){
var inst=jQuery("#orgtree").jstree(true);
if(data.node.state.loaded) open_node(data.node);
else inst.load_node(data.node,function(){open_node(data.node)});
 });
 jQuery("#orgtree").on('changed.jstree',function(e,data){
//jQuery("#orgtree").jstree(true).toggle_node(data.node);
//if(data.action=='select_node' || data.action=='deselect_node'){
formatSelected(data.selected);
//}

 });
 jQuery("#orgtree").on('ready.jstree',function(e){
 var inst=jQuery("#orgtree").jstree(true);
 try{
 var orgtree=parent.openarr? parent.openarr[ctrlid]:(parent.selorg.openarr[ctrlid] || []);
 if(orgtree){
 for(var i in orgtree){
if(document.getElementById(orgtree[i][0])) open_node_dg(inst,document.getElementById(orgtree[i][0]),orgtree[i]);

 }
 }
 }catch(e){}
jstree_checked();
 });

/* jQuery("#orgtree").on('open_node.jstree',function(e,data){
 jstree_checked(data.node);
 });*/


});
var ajaxing=false;
var arr=[];
var timer=null;
function open_node(node){
ajaxing=false;
var inst=jQuery("#orgtree").jstree(true);
if(!node) return;
if(node.type=='user') return;
//inst.open_node(node);
if(node && node.children && node.children.length){
for(var i=0 ;i<node.children.length;i++){
var t=inst.get_node(node.children[i]);
if(!t) continue;
if(t.id.indexOf('uid_')!==-1){
continue;
}else if(t.state.loaded){
open_node(inst.get_node(node.children[i]));
}else{
arr.push(node.children[i]);
}
}
open_run();
}
}
function open_run(){
if(timer || ajaxing) return;
if(!ajaxing){
if(arr.length>0){
timer=window.setInterval(function(){
ajaxing=true;
if(arr.length<1) return;
var nid=arr.shift();
if(nid.indexOf('uid_')!==-1) return;
var pnode=jQuery("#orgtree").jstree(true).get_node(nid);
if(!pnode) return;
if(jQuery("#orgtree").jstree(true).is_loaded(nid)){
open_node(pnode);
}else{
 jQuery("#orgtree").jstree(true).load_node(nid,function(node,state){
 node.state.loaded=true;
open_node(pnode);
});
}
},50);
}else{
window.clearInterval(timer);
}
}
}
function open_node_dg(inst,node,arr){ //?????????????????????????????????
 inst.open_node(node,function(node){
 var i=jQuery.inArray((node.id),arr);
 if(i<arr.length && i>-1 && document.getElementById(arr[i+1])) open_node_dg(inst,document.getElementById(arr[i+1]),arr);
 else{
 jstree_checked();
 //inst.select_node(node);
 }
 });
 }
function jstree_checked(node){

var inst=jQuery("#orgtree").jstree(true)

if(!parent.jQuery('#sel_'+ctrlid).val()) return;
    
var orgids=parent.jQuery('#sel_'+ctrlid).val().split(',');

var uids_node=[];
var oids_node=[]
for(var i in orgids){//??????btn-sorg
if((stype==0 || stype==2) && orgids[i].indexOf('uid_')===0){ //??????
var uid=orgids[i].replace('uid_','');
jQuery('#orgtree .jstree-node[uid='+uid+']').each(function(){
var node=inst.get_node(this);
if(node) uids_node.push(node);
});
}else if(stype==0 || stype==1){
   var node=inst.get_node(orgids[i]);
   if(node) oids_node.push(node);
}
}


if(oids_node.length){
inst.select_node(oids_node,true);
}
    if(uids_node.length){
inst.select_node(uids_node,true);
}
}
//?????????????????????
//
function formatSelected(sels){//????????????????????????????????????????????????????????????
 var inst=jQuery("#orgtree").jstree(true);
 var nsels=[];
 if(stype<2){//?????????????????????????????????????????????????????????????????????
for(var i in sels){
if(jQuery.inArray(inst.get_parent(sels[i]),sels)<0){
nsels.push(sels[i]);
}
}
 }else if(stype==2){
 for(var i in sels){
 if(sels[i].indexOf('uid_')!==-1){
 nsels.push(sels[i]);
 }
 }
 }
 selectorg_add(nsels);

}
function selectorg_add(sels){ //??????????????? 
    var inst=jQuery("#orgtree").jstree(true);
var vals=[];
var nsels=[];
for(var i in sels){
var node=inst.get_node(sels[i]);
if((jQuery.isNumeric(sels[i]) || sels[i]=='other')){//??????????????????
if(stype==2) continue;
var path=node.text;
if(node.parents.length>1){
for(var j=0;j<node.parents.length-1;j++){
var nodep=inst.get_node(node.parents[j]);
if(nodep.text) path=nodep.text+'-'+path;
}
}
nsels.push(sels[i]);
vals.push({'orgid':sels[i],'icon':node.icon,'text':node.text,'path':path});
}else if( sels[i].indexOf('uid_')!==-1){//?????????
if(stype==1) continue;
nsels.push(sels[i].replace(/orgid_\d+_/,''));
vals.push({'orgid':sels[i].replace(/orgid_\d+_/,''),'icon':node.icon,'text':node.text,'path':node.text});
}
}
var orgids=[];
if(parent.jQuery('#sel_'+ctrlid).val()){
orgids=parent.jQuery('#sel_'+ctrlid).val().split(',');
}

var dels=[];
for(var j in orgids){
if(jQuery.inArray(orgids[j],nsels)<0){
dels.push(orgids[j]);
}
}
try{
if(dels) parent.selorg.del(ctrlid,dels);
}catch(e){}
try{
parent.selorg.add(ctrlid,vals);

}catch(e){}

}

function checkdel_by_treeSelecteds(){ //??????????????????????????????????????????????????????????????????????????????
    var inst=jQuery("#orgtree").jstree(true);
var orgids=[];

var sels=inst.get_selected();
var nsels=[]
for(var i in sels){
if(jQuery.inArray(inst.get_parent(sels[i]),sels)>=0){

}else{
if((jQuery.isNumeric(sels[i]) || sels[i]=='other')){
nsels.push(sels[i]);
}else {
nsels.push(sels[i].replace(/orgid_\d+_/,''));
}
}
}
var vals=[];
if(parent.jQuery('#sel_'+ctrlid).val()){
orgids=parent.jQuery('#sel_'+ctrlid).val().split(',');
}
for(var i in orgids){
if(jQuery.inArray(orgids[i],nsels)>-1) continue;
vals.push(orgids[i]);
}
try{parent.selorg.del(ctrlid,vals);}catch(e){}
}
function selectorg_remove(val){
  var inst=jQuery("#orgtree").jstree(true);
  //???????????????????????????
  val+='';
   var select_nodes=inst.get_selected(true);
   for(var i in select_nodes){
   if(val.indexOf('uid_')===0 && select_nodes[i].id.indexOf(val)!==-1){
   inst.deselect_node(select_nodes[i]);
   }else if(val*1==select_nodes[i].id*1){
   inst.deselect_node(select_nodes[i]);
   }
   }
}


function jstree_search(op){
if(op=='stop'){

jQuery('#jstree_search_input').val('');
jQuery("#orgtree").jstree(true).search();
}else{
   jQuery("#orgtree").jstree(true).search(jQuery('#jstree_search_input').val());
}
}
function selectorg_search(keyword){
jQuery('#jstree_search_input').val(keyword);
jQuery("#orgtree").jstree(true).search(keyword);
}

$(function(){
jQuery('.orgtree-search').siblings('#orgtree').css('padding','40px 15px 15px 15px');
})


</script>
<script type="text/javascript" src="static/js/jstree.min.js?<?php echo VERHASH;?>" ></script> 
</body>
</html>