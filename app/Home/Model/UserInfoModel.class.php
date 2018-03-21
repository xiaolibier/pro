<?php
namespace Home\Model;

use Think\Model;

class UserInfoModel extends Model
{
	
	//修改个人信息 绑定邮箱
	public function bindEmail($token,$email){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('user_name' => $token);
		$save = array('user_email' => $email);
		$user_info = M('user_info');
		$res1 = $user_info->where($where1)->find();
		if(is_array($res1) && count($res1)>0){
			$res2 = $user_info->where($where1)->save($save);
			if($res2){
				$data['success'] = 1;
				$data['data'] = $res2;
				$data['message'] = '绑定成功！';
			}else{
				$data['message'] = '绑定失败！';
				$data['data'] = $res2;
			}
		}else{
			$data['message'] = '用户不存在！';
		}
		return $data;
	}
	//找回密码
	public function findOldPass($user,$email){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('user_name' => $user);
		$where2 = array('user_name' => $user,'user_email' => $email);
		$user_info = M('user_info');
		$res1 = $user_info->where($where1)->find();
		if(is_array($res1) && count($res1)>0){
			$res2 = $user_info->where($where2)->find();
			if(is_array($res2) && count($res2)>0){
				$data['success'] = 1;
				$data['data'] = $res2;
			}else{
				$data['message'] = '邮箱或用户名输入错误！';
			}
		}else{
			$data['message'] = '用户不存在！';
		}
		return $data;
	}
	//注册快速登录账号
	public function getQuickNum($pad){
		$str = substr(time()*rand(1,9), 0, 6);
		$usr_num = $pad.$str;//获取随机用户名
		$usr_pass = rand(100000,1000000);
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('user_name' => $usr_num);
		$add = array('user_name' => $usr_num,'user_password'=>$usr_pass,'user_status'=>1);
		$user_info = M('user_info');
		$res1 = $user_info->where($where1)->select();
		if(is_array($res1) && count($res1)>0){
			//$data['message'] = '用户名已存在！请改用其他名字';
			$this->getQuickNum($pad);
		}else{
			$res2 = $user_info->add($add);
			if($res2){
				$where2 = array('user_id' => $res2);
				$res3 = $user_info->where($where2)->find();
				$data['success'] = 1;
				$data['data'] = $res3;
				$data['message'] = '添加用户成功！';
			}else{
				$data['message'] = '添加用户失败！';
			}
		}
		return $data;
	}
	//获取用户信息
	public function getUserInfo($token){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('user_name' => $token);
		$user_info = M('user_info');
		$res1 = $user_info->where($where1)->find();
		if(is_array($res1) && count($res1)>0){
			$data['success'] = 1;
			$data['data'] = $res1;
		}else{
			$data['message'] = '查询用户信息失败！';
		}
		return $data;
	}
	//用户登录
	public function userLogin($usr_num,$usr_pass){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('user_name' => $usr_num);
		$where2 = array('user_name' => $usr_num,'user_password'=>$usr_pass);
		
		$user_info = M('user_info');
		$res1 = $user_info->where($where1)->select();
		$res2 = $user_info->where($where2)->find();
		if(is_array($res1) && count($res1)>0){
			if(is_array($res2) && count($res2)>0){
				$data['success'] = 1;
				$data['data'] = $res2;
			}else{
				$data['message'] = '密码错误';
			}
		}else{
			$data['message'] = '用户不存在';
		}
		
		return $data;
	}
	//用户注册
	public function userReg($usr_num,$usr_pass,$usr_email){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('user_name' => $usr_num);
		$add = array('user_name' => $usr_num,'user_password'=>$usr_pass,'user_email'=>$usr_email,'user_status'=>1);
		$user_info = M('user_info');
		$res1 = $user_info->where($where1)->select();
		if(is_array($res1) && count($res1)>0){
			$data['message'] = '用户名已存在！请改用其他名字';
		}else{
			$res2 = $user_info->add($add);
			if($res2){
				$where2 = array('user_id' => $res2);
				$res3 = $user_info->where($where2)->find();
				$data['success'] = 1;
				$data['data'] = $res3;
				$data['message'] = '添加用户成功！';
			}else{
				$data['message'] = '添加用户失败！';
			}
		}
		return $data;
	}
	//修改用户信息
	public function setUserInfo($token,$usr_num,$sex,$birthDate,$address,$address2,$oldpass,$newpass,$newpass2){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('user_name' => $token);//查询用户是否存在
		$where2 = array('user_name' => $token,'user_password' => $oldpass);//查询原始密码对不对
		$save = array('user_name' => $usr_num,
				'user_sex'=>$sex,
				'user_birth_day'=>$birthDate,
				'user_province'=>$address,
				'user_city'=>$address2,
				'user_password'=>$newpass
				);
		if($oldpass == ''){array_splice($save,5,1);}//密码为空 不保存密码
		$user_info = M('user_info');
		$res1 = $user_info->where($where1)->select();
		$res2 = $user_info->where($where2)->select();
		if(is_array($res1) && count($res1)>0){//校验用户是否存在
			if(is_array($res2) && count($res2)>0 && $oldpass != '' || $oldpass == ''){//校验原始密码
				$res3 = $user_info->where($where1)->save($save);
				if($res3){
					$where3 = array('user_name' => $usr_num);
					$res4 = $user_info->where($where3)->find();
					$data['success'] = 1;
					$data['data'] = $res4;
					$data['message'] = '修改个人信息成功！';
				}else{
					$data['message'] = '修改个人信息失败！';
					$data['data'] = $res3;
				}
			}else{
				$data['message'] = '原始密码错误！';
			}
		}else{
			$data['message'] = '此用户不存在！无法修改个人信息';
		}
		return $data;
	}
	
	
}