<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
	
	
	//图片上传
	public function imgUpload(){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$config = array(
			'maxSize'    =>    3145728,// 设置附件上传大小
			'rootPath'   =>    './upload/',// 设置附件上传根目录
			'savePath'   =>    '/img/',// 设置附件上传（子）目录
			//'saveName'   =>   array('uniqid',''),
			//'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),// 设置附件上传类型
			'autoSub'    =>    true,
			'subName'    =>    array('date','Ymd'),
		);
		$upload = new \Think\Upload($config);// 实例化上传类
		// 上传文件 
		$info   =   $upload->upload();
		if(!$info) {// 上传错误提示错误信息
			$data['message'] = $upload->getError();
		}else{// 上传成功 获取上传文件信息
			$data['success'] = 1;
			foreach($info as $file){
				$data['data'] = $file['savepath'].$file['savename'];
			}
		}
		$this->ajaxReturn($data);
	}
	
	//获取元素 不传id 获取所有
	public function getOne(){
		$id = I('id');//用户名
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$m = D('list');
		$result = $m->getOne($id);
		$this->ajaxReturn($result);
	}
	//修改元素信息
	public function changeOne(){
		$id = I('id');//
		$name = I('name');//
		$width = I('width');//
		$height = I('height');//
		$long = I('long');//
		$translatex = I('translatex');//
		$translatey = I('translatey');//
		$translatez = I('translatez');//
		$rotatex = I('rotatex');//
		$rotatey = I('rotatey');//
		$rotatez = I('rotatez');//
		$topsrc = I('topsrc');//
		$bottomsrc = I('bottomsrc');//
		$leftsrc = I('leftsrc');//
		$rightsrc = I('rightsrc');//
		$beforesrc = I('beforesrc');//
		$aftersrc = I('aftersrc');//
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		if($id == ''){$data['id'] = '元素id不能为空！';$this->ajaxReturn($data);}
		$m = D('list');
		$result = $m->changeOne($id,$name,$width,$height,$long,$translatex,$translatey,$translatez,$rotatex,$rotatey,$rotatez,$topsrc,$bottomsrc,$leftsrc,$rightsrc,$beforesrc,$aftersrc);
		$this->ajaxReturn($result);
		
	}
	//新建元素
	public function createOne(){
		$name = I('name');//
		$width = I('width');//
		$height = I('height');//
		$long = I('long');//
		$translatex = I('translatex');//
		$translatey = I('translatey');//
		$translatez = I('translatez');//
		$rotatex = I('rotatex');//
		$rotatey = I('rotatey');//
		$rotatez = I('rotatez');//
		$topsrc = I('topsrc');//
		$bottomsrc = I('bottomsrc');//
		$leftsrc = I('leftsrc');//
		$rightsrc = I('rightsrc');//
		$beforesrc = I('beforesrc');//
		$aftersrc = I('aftersrc');//
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		if($name == ''){$data['message'] = '名称不能为空！';$this->ajaxReturn($data);}
		$m = D('list');
		$result = $m->createOne($name,$width,$height,$long,$translatex,$translatey,$translatez,$rotatex,$rotatey,$rotatez,$topsrc,$bottomsrc,$leftsrc,$rightsrc,$beforesrc,$aftersrc);
		$this->ajaxReturn($result);
	}
	//删除元素
	public function deleteOne(){
		$id = I('id');//用户名
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		if($id == ''){$data['message'] = 'id为空！';$this->ajaxReturn($data);}
		$m = D('list');
		$result = $m->deleteOne($id);
		$this->ajaxReturn($result);
	}



	
}