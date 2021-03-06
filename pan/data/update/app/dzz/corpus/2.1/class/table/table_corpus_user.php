<?php
/* @authorcode  codestrings
 * @copyright   Leyun internet Technology(Shanghai)Co.,Ltd
 * @license     http://www.dzzoffice.com/licenses/license.txt
 * @package     DzzOffice
 * @link        http://www.dzzoffice.com
 * @author      zyx(zyx@dzz.cc)
 */

if(!defined('IN_DZZ')) {
	exit('Access Denied');
}

class table_corpus_user extends dzz_table
{
	public function __construct() {

		$this->_table = 'corpus_user';
		$this->_pk    = 'id';

		parent::__construct();
	}
	public function fetch_all_by_uid($uid){
		return DB::fetch_all("select * from %t where uid=%d ",array($this->_table,$uid,$perm)) ;
	}
	public function fetch_all_by_cid($cid){
		//if(!is_array($perm)) $perm=array($perm);
		return DB::fetch_all("select * from %t where cid=%d order by perm DESC,dateline DESC",array($this->_table,$cid,$perm)) ;
	}
	public function fetch_uids_by_cid($cid){
		//if(!is_array($perm)) $perm=array($perm);
		$uids=array();
		foreach(DB::fetch_all("select uid from %t where cid=%d ",array($this->_table,$cid)) as $value){
			$uids[]=$value['uid'];
		}
		return $uids;
	}
	public function fetch_perm_by_uid($uid,$cid){
		//$user=getuserbyuid($uid);
		//if($user['adminid']==1) return 4;
		if(DB::result_first("select COUNT(*) from %t where cid=%d and uid=%d",array($this->_table,$cid,$uid))){
			return DB::result_first("select `perm` from %t where cid=%d and uid=%d",array($this->_table,$cid,$uid));
		 }else{
			 return 0;
		 }
		return DB::result_first("select perm from %t where cid=%d and uid=%d",array($this->_table,$cid,$uid));
	}
	public function insert($arr){
		if(!$arr['cid'] || !$arr['uid']) return false;
		if($id=DB::result_first("select id from %t where cid=%d and uid=%d",array($this->_table,$arr['cid'],$arr['uid']))){
			return parent::update($id,$arr);
		}else{
			if($ret=parent::insert($arr,1)){
				C::t('corpus')->increase($arr['cid'],array('members'=>1));
				return $ret;
			}
		}
		return false;
	}
	public function insert_uids_by_cid($cid,$uids,$perm=0){
		$ouids=array();
		foreach(DB::fetch_all("select * from %t where cid=%d and  uid in (%n)",array($this->_table,$cid,$uids)) as $value){
			if($value['perm']<$perm){
				parent::update($value['id'],array('perm'=>$perm));
			} 
			$ouids[]=$value['uid'];
		}
		$uids=array_diff($uids,$ouids);
		$user=C::t('user')->fetch_all($uids);
		
		$appid=C::t('app_market')->fetch_appid_by_mod('{dzzscript}?mod=corpus',1);
		$corpus=C::t('corpus')->fetch($cid);
		$permtitle=array('1'=>'????????????','2'=>'????????????','3'=>'?????????');
		$add=0;
		foreach($uids as $uid){
			$userarr=array('uid'=>$uid,
						   'username'=>$user[$uid]['username'],
						   'perm'=>$perm,
						   'dateline'=>TIMESTAMP,
						   'cid'=>$cid,
						   );
						 
		    if(parent::insert($userarr)){
				$add++;
				if($uid!=getglobal('uid')){
					//????????????
					
					$notevars=array(
									'from_id'=>$appid,
									'from_idtype'=>'app',
									'url'=>DZZSCRIPT.'?mod=corpus&op=list&cid='.$corpus['cid'],
									'author'=>getglobal('username'),
									'authorid'=>getglobal('uid'),
									'dataline'=>dgmdate(TIMESTAMP),
									'corpusname'=>getstr($corpus['name'],30),
									'permtitle'=>$permtitle[$perm],
									);
					
						$action='corpus_user_add';
						$type='corpus_user_add_'.$cid;
					
					dzz_notification::notification_add($uid, $type, $action, $notevars, 0,'dzz/corpus');
				}
			}
		}
		if($add) C::t('corpus')->increase($cid,array('members'=>$add));
		return true;
	}
	
	public function remove_uid_by_cid($cid,$uid){
		//????????????????????????
		$data=DB::fetch_first("select * from %t where cid=%d and uid=%d",array($this->_table,$cid,$uid));
		if($data['perm']>2 && DB::result_first("select COUNT(*) from %t where cid=%d and perm>2",array($this->_table,$cid))<2){
			return array('error'=>'???????????????????????????');
		}
		$permtitle=array('1'=>'????????????','2'=>'????????????','3'=>'?????????');
			if($uid!=getglobal('uid')){
				//????????????
				$appid=C::t('app_market')->fetch_appid_by_mod('{dzzscript}?mod=corpus',1);
				$corpus=C::t('corpus')->fetch($cid);
				$notevars=array(
								'from_id'=>$appid,
								'from_idtype'=>'app',
								'url'=>DZZSCRIPT.'?mod=corpus&op=list&cid='.$corpus['cid'],
								'author'=>getglobal('username'),
								'authorid'=>getglobal('uid'),
								'dataline'=>dgmdate(TIMESTAMP),
								'corpusname'=>getstr($corpus['name'],30),
								'permtitle'=>$permtitle[$data['perm']],
								);
				
					$action='corpus_user_remove';
					$type='corpus_user_remove_'.$cid;
				
				dzz_notification::notification_add($uid, $type, $action, $notevars, 0,'dzz/corpus');
			}
		if($ret=parent::delete($data['id'])){
			C::t('corpus')->increase($cid,array('members'=>-1));
		}
		return $ret;
	}
	public function change_perm_by_uid($cid,$uid,$perm){
		//????????????????????????
		$data=DB::fetch_first("select * from %t where cid=%d and uid=%d",array($this->_table,$cid,$uid));
		if($data['perm']>2 && $perm<3 && (DB::result_first("select COUNT(*) from %t where cid=%d and perm>2",array($this->_table,$cid))<2)){
			return array('error'=>'???????????????????????????');
		}
		parent::update($data['id'],array('perm'=>$perm));
		if($data['perm']!=$perm){//????????????
			$permtitle=array('1'=>'????????????','2'=>'????????????','3'=>'?????????');
			if($uid!=getglobal('uid')){
				//????????????
				$appid=C::t('app_market')->fetch_appid_by_mod('{dzzscript}?mod=corpus',1);
				$corpus=C::t('corpus')->fetch($cid);
				$notevars=array(
								'from_id'=>$appid,
								'from_idtype'=>'app',
								'url'=>DZZSCRIPT.'?mod=corpus&op=list&cid='.$corpus['cid'],
								'author'=>getglobal('username'),
								'authorid'=>getglobal('uid'),
								'dataline'=>dgmdate(TIMESTAMP),
								'corpusname'=>getstr($corpus['name'],30),
								'permtitle'=>$permtitle[$perm],
								);
				
					$action='corpus_user_change';
					$type='corpus_user_change_'.$cid;
				
				dzz_notification::notification_add($uid, $type, $action, $notevars, 0,'dzz/corpus');
			}
		}
		return true;
	}
	public function fetch_all_by_perm($cid,$perm=array(),$limit='',$iscount=false){
		if(!is_array($perm)) $perm=array($perm);
		$limitsql='';
		if($limit){
			$limit=explode('-',$limit);
			if(count($limit)>1){
				$limitsql.=" limit ".intval($limit[0]).",".intval($limit[1]);
			}else{
				$limitsql.=" limit ".intval($limit[0]);
			}
		}
		if($iscount) return DB::result_first("select COUNT(*) from %t where cid=%d and perm in(%n)",array($this->_table,$cid,$perm));
		else return  DB::fetch_all("select * from %t where cid=%d and perm in(%n) order by perm DESC,dateline ASC $limitsql ",array($this->_table,$cid,$perm));
	}
	public function getUserPermByCid($cid,$uid=0){//???????????????????????????????????????????????????0
	     if(DB::result_first("select COUNT(*) from %t where cid=%d and uid=%d",array($this->_table,$cid,$uid))){
			return DB::result_first("select `perm` from %t where cid=%d and uid=%d",array($this->_table,$cid,$uid));
		 }else{
			 return 0;
		 }
	}
}

?>
