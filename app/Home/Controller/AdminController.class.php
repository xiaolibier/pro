<?php
namespace Home\Controller;

use Think\Controller;

class AdminController extends Controller
{
	
	
	
	//上传资源
	public function uploadSourse(){
		$f_id = I('f_id');//
		$user_id = I('user_id');//
		$menutype = I('menutype');//
		$sourseType = I('sourseType');//
		$name = I('name');//
		$src = I('src');//
		$createTime = I('createTime');//
		$preused = I('preused');//
		$static = I('static');//
		$used_user_id = I('used_user_id');//
		
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$m = D('Sourse');
		$result = $m->uploadSourse($f_id,$user_id,$menutype,$sourseType,$name,$src,$createTime,$preused,$static,$used_user_id);
		$this->ajaxReturn($result);
	}
	//删除一个资源
	public function deleteThis(){
		$id = I('id');//
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$m = D('Sourse');
		$result = $m->deleteThis($id);
		$this->ajaxReturn($result);
	}
	//获取资源列表 或者单个数据
	public function getSourseList(){
		$sourseId = I('sourseId');//
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$m = D('Sourse');
		$result = $m->getSourseList($sourseId);
		$this->ajaxReturn($result);
	}
	//校验用户名密码是否可以登录使用
	public function testLogin(){
		$usr_num = I('usr_num');//用户名
		$usr_pass = I('usr_pass');//密码
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		//if($usr_num == ''){$data['message'] = '用户名不能为空！';$this->ajaxReturn($data);}
		//if($usr_pass == ''){$data['message'] = '密码不能为空！';$this->ajaxReturn($data);}
		$m = D('UserInfo');
		$result = $m->userLogin($usr_num,$usr_pass);
		$this->ajaxReturn($result);
	}
	
	
	
	
}