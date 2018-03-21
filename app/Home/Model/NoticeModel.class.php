<?php
namespace Home\Model;

use Think\Model;

class NoticeModel extends Model
{
	//用户登录
	public function getNotice($usr_num,$usr_pass){
		$data = array('success'=>0,'data'=>array(),'message'=>'');
		$notice = M('notice');
		$res = $notice->order('id desc')->select();
		if(is_array($res) && count($res)>0){
			$data['success'] = 1;
			$data['data'] = $res;
		}else{
			$data['message'] = '公告为空';
		}
		return $data;
	}
}