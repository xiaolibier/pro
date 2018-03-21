<?php
namespace Home\Model;

use Think\Model;
use Think\Model\UserInfoModel;

class ListModel extends Model
{
	
	//获取元素列表 或 单个
	public function getOne($id){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('id' => $id);
		if($id == '' || !$id){$where1 = '';}
		$list = M('list');
		$res1 = $list->where($where1)->select();
		if(is_array($res1) && count($res1)>0){
			$data['message'] = '获取成功！';
			$data['success'] = 1;
			$data['data'] = $res1;
		}else{
			$data['message'] = '元素为空！';
		}
		return $data;
	}
	
	
	//修改元素信息
	public function changeOne($id,$name,$width,$height,$long,$translatex,$translatey,$translatez,$rotatex,$rotatey,$rotatez,$topsrc,$bottomsrc,$leftsrc,$rightsrc,$beforesrc,$aftersrc){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('id' => $id);
		$save = array(
			'name' => $name,
			'width' => $width,
			'height' => $height,
			'long' => $long,
			'translatex' => $translatex,
			'translatey' => $translatey,
			'translatez' => $translatez,
			'rotatex' => $rotatex,
			'rotatey' => $rotatey,
			'rotatez' => $rotatez,
			'topsrc' => $topsrc,
			'bottomsrc' => $bottomsrc,
			'leftsrc' => $leftsrc,
			'rightsrc' => $rightsrc,
			'beforesrc' => $beforesrc,
			'aftersrc' => $aftersrc
		);
		$list = M('list');
		$res1 = $list->where($where1)->select();
		if(is_array($res1) && count($res1)>0){
			$res2 = $list->where($where1)->save($save);
			if($res2){
				$res3 = $list->where($where1)->find();
				$data['success'] = 1;
				$data['data'] = $res3;
				$data['message'] = '修改元素成功！';
			}else{
				$data['message'] = '修改元素失败！';
				$data['data'] = $res2;
			}
		}else{
			$data['message'] = '元素不存在！';
		}
		return $data;
	}
	//新建元素
	public function createOne($name,$width,$height,$long,$translatex,$translatey,$translatez,$rotatex,$rotatey,$rotatez,$topsrc,$bottomsrc,$leftsrc,$rightsrc,$beforesrc,$aftersrc){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('name' => $name);
		$add = array(
			'name' => $name,
			'width' => $width,
			'height' => $height,
			'long' => $long,
			'translatex' => $translatex,
			'translatey' => $translatey,
			'translatez' => $translatez,
			'rotatex' => $rotatex,
			'rotatey' => $rotatey,
			'rotatez' => $rotatez,
			'topsrc' => $topsrc,
			'bottomsrc' => $bottomsrc,
			'leftsrc' => $leftsrc,
			'rightsrc' => $rightsrc,
			'beforesrc' => $beforesrc,
			'aftersrc' => $aftersrc
		);
		$list = M('list');
		$res1 = $list->where($where1)->select();
		if(is_array($res1) && count($res1)>0){
			$data['message'] = '名称已存在！请改用其他名称';
		}else{
			$res2 = $list->add($add);
			if($res2){
				$where2 = array('name' => $name);
				$res3 = $list->where($where2)->find();
				$data['success'] = 1;
				$data['data'] = $res3;
				$data['message'] = '创建成功！';
			}else{
				$data['message'] = '创建失败！';
			}
		}
		return $data;
	}
	
	//删除元素
	public function deleteOne($id){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('id' => $id);
		$list = M('list');
		$res = $list->where($where1)->find();
		$res1 = $list->where($where1)->delete();
		if($res1){
			$data['message'] = '删除成功！';
			$data['success'] = 1;
			$data['data'] = $res1;
		}else{
			$data['message'] = '删除失败！';
		}
		return $data;
	}
	

	
	
	
}