<?php
namespace Home\Model;

use Think\Model;

class SourseModel extends Model
{
	
	
	
	//上传资源
	public function uploadSourse($f_id,$user_id,$menutype,$sourseType,$name,$src,$createTime,$preused,$static,$used_user_id){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$sourse = M('sourse');
		
		$save = array();
		if($user_id != ''){$save['user_id'] = $user_id;}
		if($menutype != ''){$save['menutype'] = $menutype;}
		if($sourseType != ''){$save['sourseType'] = $sourseType;}
		if($sourseType != ''){$save['sourseType'] = $sourseType;}
		if($name != ''){$save['name'] = $name;}
		if($src != ''){$save['src'] = $src;}
		if($createTime != ''){$save['createTime'] = $createTime;}
		if($preused != ''){$save['preused'] = $preused;}
		if($static != ''){$save['static'] = $static;}
		if($used_user_id != ''){$save['used_user_id'] = $used_user_id;}
		$where = array('id'=>$f_id);
		if($f_id != ''){
			$res1 = $sourse->where($where)->select();
			if(is_array($res1) && count($res1)>0){
				$res2 = $sourse->where($where)->save($save);
			}else{
				$data['message'] = '资源不存在！';
				$data['data'] = $res1;
			}
		}else{
			$res2 = $sourse->where($where)->add($save);
		}
		if($res2){
			$data['success'] = 1;
			$data['data'] = $res2;
		}else{
			$data['message'] = '上传失败！';
			$data['data'] = $res2;
		}
		return $data;
	}
	//删除一个资源
	public function deleteThis($id){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$sourse = M('sourse');
		$where = array('id'=>$id);
		$res1 = $sourse->where($where)->select();
		if(is_array($res1) && count($res1)>0){
			$res2 = $sourse->where($where)->delete();
			if($res2){
				$data['success'] = 1;
				$data['data'] = $res2;
			}else{
				$data['message'] = '删除失败！';
				$data['data'] = $res2;
			}
		}else{
			$data['message'] = '资源不存在！';
			$data['data'] = $res1;
		}
		return $data;
	}
	//获取资源列表 后台管理使用
	public function getSourseList($sourseId){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$sourse = M('sourse');
		$where = array('id'=>$sourseId);
		if($sourseId){$res1 = $sourse->where($where)->find();}
		else{$res1 = $sourse->select();}
		if(is_array($res1) && count($res1)>0){
			$data['success'] = 1;
			$data['data'] = $res1;
		}else{
			$data['message'] = '获取资源列表失败！';
			$data['data'] = $res1;
		}
		return $data;
	}
	//标记最近使用
	public function setUesdSourse($token,$usrid,$id){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('id' => $id);
		$sourse = M('sourse');
		$res1 = $sourse->where($where1)->find();
		if(is_array($res1) && count($res1)>0){
			$preused = $res1['preused'];
			if($preused != ''){
				$preused2 = explode(",", $preused);//截取字符串
				foreach ($preused2 as $pre){
					if($usrid == $pre && $usrid != ''){
						$data['message'] = '已经标记使用了！';
						return $data;
					}
				}
			}
			$res1['preused'] = $usrid.','.$preused;
			$res2 = $sourse->where($where1)->save($res1);
			if($res2){
				$data['success'] = 1;
				$data['message'] = '标记使用成功！';
			}else{
				$data['message'] = $res2;
			}
			
		}else{
			$data['message'] = '资源不存在！';
		}
		return $data;
	}
	//获取资源
	public function getUesdSourse($token,$usrid){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$sourse = M('sourse');
		$res1 = $sourse->where("sourseType='1' or sourseType='2'")->select();
		if(is_array($res1) && count($res1)>0){
			foreach ($res1 as $r => $e){
				//判断是否被使用
				$fal = false;
				$preused = $res1[$r]['preused'];
				if($preused != ''){
					$preused2 = explode(",", $preused);//截取字符串
					foreach ($preused2 as $pre){
						if($usrid == $pre){
							$fal = true;
							$e['preused'] = 1;//判断当前项是否被使用
						}
					}
				}
				if($fal){$data['data'][] = $e;}
			}
			if(count($data['data'])>0){
				$data['success'] = 1;
			}
			
		}else{
			$data['message'] = '最近使用作品为空！';
		}
		return $data;
	}
	//取消表里所有使用
	public function unuseSourse($article_id){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$sourse = M('sourse');
		$res1 = $sourse->field('id,used_user_id')->select();
		if(is_array($res1) && count($res1)>0){
			foreach ($res1 as $rk=>$res){
				$uid = $res['used_user_id'];
				$where1 = array('id'=>$res['id']);
				if($uid != ''){
					$uid2 = explode(",", $uid);//截取字符串
					$fa = false;
					foreach ($uid2 as $k=>$u){
						if($article_id == $u){
							unset($uid2[$k]);//删除项
							$fa = true;
						}
					}
					if($fa){//判断是否有相同的id
						$uid2 = array_merge($uid2);
						$res['used_user_id'] = implode(',',$uid2);
						$res2 = $sourse->where($where1)->save($res);
					}
				}
			}
			
		}
		return $data;
	}
	//使用
	public function useSourse($id,$usrid,$article_id){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('id' => $id);
		$sourse = M('sourse');
		$res1 = $sourse->where($where1)->find();
		if(is_array($res1) && count($res1)>0){
			$uid = $res1['used_user_id'];
			if($uid != ''){
				$uid2 = explode(",", $uid);//截取字符串
				foreach ($uid2 as $u){
					if($article_id == $u && $article_id != ''){
						$data['message'] = '已经使用了！';
						return $data;
					}
				}
			}
			$this->unuseSourse($article_id);
			$res1['used_user_id'] = $article_id.','.$uid;
			$res2 = $sourse->where($where1)->save($res1);
			if($res2){
				$data['success'] = 1;
				$data['message'] = '使用成功！';
			}else{
				$data['message'] = $res2;
			}
			
		}else{
			$data['message'] = '作品不存在！';
		}
		return $data;
	}

	//取消收藏
	public function uncollectSourse($id,$usrid){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('id' => $id);
		$sourse = M('sourse');
		$res1 = $sourse->where($where1)->find();
		if(is_array($res1) && count($res1)>0){
			$uid = $res1['user_id'];
			if($uid != ''){
				$uid2 = explode(",", $uid);//截取字符串
				$fa = false;
				foreach ($uid2 as $k=>$u){
					if($usrid == $u){
						unset($uid2[$k]);//删除项
						$fa = true;
					}
				}
				if($fa){//判断是否有相同的id
					$uid2 = array_merge($uid2);
					$res1['user_id'] = implode(',',$uid2);
					$res2 = $sourse->where($where1)->save($res1);
					if($res2){
						$data['success'] = 1;
						$data['message'] = '取消收藏成功！';
					}else{
						$data['message'] = $res2;
					}
					return $data;
				}
			}
			$data['message'] = '该作品未被收藏过！';
		}else{
			$data['message'] = '作品不存在！';
		}
		return $data;
	}
	//收藏
	public function collectSourse($id,$usrid){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('id' => $id);
		$sourse = M('sourse');
		$res1 = $sourse->where($where1)->find();
		if(is_array($res1) && count($res1)>0){
			$uid = $res1['user_id'];
			if($uid != ''){
				$uid2 = explode(",", $uid);//截取字符串
				foreach ($uid2 as $u){
					if($usrid == $u){
						$data['message'] = '已经收藏了！';
						return $data;
					}
				}
			}
			$res1['user_id'] = $usrid.','.$uid;
			$res2 = $sourse->where($where1)->save($res1);
			if($res2){
				$data['success'] = 1;
				$data['message'] = '收藏成功！';
			}else{
				$data['message'] = $res2;
			}
			
		}else{
			$data['message'] = '作品不存在！';
		}
		return $data;
	}
	//获取资源
	public function getAllSourse($usrid,$stype,$sourseType,$menutype,$article_id){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('sourseType' => $sourseType,'menutype' => $menutype);
		$sourse = M('sourse');
		$res1 = $sourse->where($where1)->select();
		if(is_array($res1) && count($res1)>0){
			if($stype == 2){//显示我的收藏的部分
				foreach ($res1 as $r => $e){
					//判断是否被收藏
					$fal = false;
					$uid = $res1[$r]['user_id'];
					if($uid != ''){
						$uid2 = explode(",", $uid);//截取字符串
						foreach ($uid2 as $u){
							if($usrid == $u){
								$e['collect'] = 1;//判断当前项是否被收藏
								$fal = true;
							}
						}
					}
					//判断是否被使用
					$usedid = $res1[$r]['used_user_id'];
					if($usedid != ''){
						$usedid2 = explode(",", $usedid);//截取字符串
						foreach ($usedid2 as $us){
							if($article_id == $us){
								$e['used'] = 1;//判断当前项是否被使用
							}
						}
					}
					if($fal){$data['data'][] = $e;}
				}
				if(count($data['data'])>0){
					$data['success'] = 1;
				}
			}else{// 显示所有资源
				foreach ($res1 as $r => $e){
					//判断是否被收藏
					$uid = $res1[$r]['user_id'];
					if($uid != ''){
						$uid2 = explode(",", $uid);//截取字符串
						foreach ($uid2 as $u){
							if($usrid == $u){
								$res1[$r]['collect'] = 1;//判断当前项是否被收藏
							}
						}
					}
					//判断是否被使用
					$usedid = $res1[$r]['used_user_id'];
					if($usedid != ''){
						$usedid2 = explode(",", $usedid);//截取字符串
						foreach ($usedid2 as $us){
							if($article_id == $us){
								$res1[$r]['used'] = 1;//判断当前项是否被使用
							}
						}
					}
				}
				$data['success'] = 1;
				$data['data'] = $res1;
			}
		}else{
			$data['message'] = '作品为空！';
			$data['data'] = $res1;
		}
		return $data;
	}
	
	
	
}