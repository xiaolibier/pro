<?php
namespace Home\Controller;

use Think\Controller;

class MongoController extends Controller
{
	
	
	 //调用MONGO数据库
    protected function Mg( $table_name = ''){
        $db_config = C('DB_MONGO');
        $db_prefix = C('DB_PREFIX');     
        if( $table_name == '' ){ return false; }
        return M('\Think\Model\MongoModel:' . $table_name , $db_prefix , $db_config);
    }
	//文件上传
	public function getGridFS(){
		return $this->Mg('testdb')->getGridFS();
	}
	//上传作品
	private function object2array($object) {
	  if (is_object($object)) {
		foreach ($object as $key => $value) {
		  $array[$key] = $value;
		}
	  }
	  else {
		$array = $object;
	  }
	  return $array;
	}
	public function uploadArticle(){
		//$object = $this->getGridFS()->findOne(array('_id' => new \MongoId('58d47116cc6b24502800002b')));
		//$obj = $this->object2array($object);
		//$obj['file']['filename'] = '114.jpg';
		//$saveRes = $this->getGridFS()->update(array('_id' => new \MongoId('58d47116cc6b24502800002b')),$obj['file']);//修改mongo文件名字
		//var_dump($saveRes);die;
		$xml = I('xml');
		$article_id = I('article_id');
		$file = "./upload/xml/".$xml.".xml";
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$id = $this->Mg('testdb')->getGridFS()->storeFile($file);
		if(!$id) {// 上传错误提示错误信息
			$data['message'] = $id;
			$this->ajaxReturn($data);
		}else{// 上传成功 获取上传文件信息
			$m = D('Article');
			$xmlId = strval($id);//上传后xml
			$nameFor = "./upload/xml/".$xmlId.".xml";
			$re = rename($file,$nameFor);//修改本地xml文件名
			if(!$re){$data['message'] = '上传失败！';$this->ajaxReturn($data);}
			//修改mongo xml文件名
			$object = $this->getGridFS()->findOne(array('_id' => new \MongoId($xmlId)));
			$obj = $this->object2array($object);
			$obj['file']['filename'] = $xmlId.".xml";
			$saveRes = $this->getGridFS()->update(array('_id' => new \MongoId($xmlId)),$obj['file']);//修改mongo文件名字
			//
			$fileDir = 'articleimages';//上传后xml 和图片存放目录
			$result = $m->uploadArticle($xmlId,$fileDir,$article_id);
			$this->ajaxReturn($result);
		}
	}
	//上传音乐 图片 
	public function uploadFile(){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$id = $this->Mg('testdb')->getGridFS()->storeUpload('file');
		if(!$id) {// 上传错误提示错误信息
			$data['message'] = $id;
		}else{// 上传成功 获取上传文件信息
			$xmlId = strval($id);//上传后xml
			//修改mongo xml文件名
			$object = $this->getGridFS()->findOne(array('_id' => new \MongoId($xmlId)));
			$obj = $this->object2array($object);
			$obj['file']['filename'] = $xmlId.".jpg";
			$saveRes = $this->getGridFS()->update(array('_id' => new \MongoId($xmlId)),$obj['file']);//修改mongo文件名字
			//
			$data['success'] = 1;
			$data['data'] = $xmlId;
		}
		$this->ajaxReturn($data);
	}

	//从MongoDB读取文件
	public function readImage(){
		$id = I('id');
		$object = $this->getGridFS()->findOne(array('_id' => new \MongoId($id)));
		header('Content-type:image/jpg');
		echo $object->getBytes();
	}
	public function readAudio(){
		$id = I('id');
		$object = $this->getGridFS()->findOne(array('_id' => new \MongoId($id)));
		header('Content-type:audio/mp3');
		echo $object->getBytes();
	}
	public function testImage(){
		//$id = I('id');
		$id = '58da2043cc6b24f03c000031';
		$object = $this->getGridFS()->findOne(array('_id' => new \MongoId($id)));
		header('Content-type:image/jpg');
		echo $object->getBytes();
	}
	
	public function readImage2(){
		
		$filename = "http://127.0.0.1/attp/index.php/home/mongo/testImage?id=58da2043cc6b24f03c000031";
		$width = 190;
		$height = 190;
		$path="http://localhost/images/"; //finish in "/"
		// Content type
		header('Content-type: image/png');
		// Get new dimensions
		list($width_orig, $height_orig) = getimagesize($filename);
		if ($width && ($width_orig < $height_orig)) {
		  $width = ($height / $height_orig) * $width_orig;
		} else {
		  $height = ($width / $width_orig) * $height_orig;
		}
		// Resample
		$image_p = imagecreatetruecolor($width, $height);
		$image = imagecreatefrompng($filename);
		//var_dump($image);die;
		imagecopyresampled($image_p,$image,0,0,0,0,$width,$height,$width_orig,$height_orig);
		// Output
		imagepng($image_p, null, 100);
		// Imagedestroy
		imagedestroy ($image_p);
	}
	
	
	//创建xml文件 创建作品xml文档
	public function creatXml($art_title,$images,$token,$usrid){
		$xml = new \DOMDocument('1.0','utf-8'); //创建XML对象
		$xml->standalone = true;
		//创建根节点
		$story = $xml->createElement('story');
		$story->setAttribute('uuid',''); //配置属性
		$story->setAttribute('readed','false'); //配置属性
		for($i=0,$len=1;$i<$len;$i++){
			$frame = $xml->createElement('frame');
			$frame->setAttribute('id',$i); 
			$frame->setAttribute('mark','false'); 
			$_str = 'fade:0;';
			$frame->setAttribute('style',$_str); 

			$image = $xml->createElement('image');
			$img_name = $xml->createTextNode('58db9c383360a670458b457d.png');
			$image->appendChild($img_name);
			
			$music = $xml->createElement('music');
			$music_text = $xml->createTextNode('');
			$music->appendChild($music_text);
			
			$seffect = $xml->createElement('seffect');
			$seffect_text = $xml->createTextNode('');
			$seffect->appendChild($seffect_text);
			
			$halfman = $xml->createElement('halfman');
			$halfman_name =  $xml->createTextNode('58db9c233360a6c84a8b4578.png');
			$halfman->appendChild($halfman_name);
			
			$role = $xml->createElement('role');
			$role->setAttribute('style','font:14;fontName:微软雅黑'); 
			//context
			$context = $xml->createElement('context');
			$context->setAttribute('style','font:14;fontName:微软雅黑'); 
			$context_text =  $xml->createTextNode('');
			$context->appendChild($context_text);
			
			$frame->appendChild($image);
			$frame->appendChild($music);
			$frame->appendChild($seffect);
			$frame->appendChild($halfman);
			$frame->appendChild($role);
			$frame->appendChild($context);
			$story->appendChild($frame);
		}
		$xml->appendChild($story);
		$xml->formatOutput = true;
		$xmlName = $usrid.time().'.xml';
		$res = $xml->save("./upload/xml/".$xmlName);
		if($res){
			return $xmlName;
		}else{
			return false;
		}
	}
	public function saveXml(){
		$nowPage = I('nowPage');//
		$usrid = I('usrid');//
		$token = I('token');//
		$halfman_v = I('halfman');//
		$image_v = I('image');//
		$music_v = I('music');//
		$seffect_v = I('seffect');//
		$fadeIn = I('fadeIn');//
		$fadeOut = I('fadeOut');//
		$shake = I('shake');//
		$fontName = I('fontName');//
		$font = I('font');//
		$role_v = I('role');//
		$context_v = I('context');//
		$xml2 = I('xml');//
		$xmlAll = I('xmlAll');//
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		if($token == ''){$data['message'] = '请登录后操作！';$this->ajaxReturn($data);}
		
		$xml = new \DOMDocument('1.0','utf-8'); //创建XML对象
		$xml->standalone = true;
		//创建根节点
		$story = $xml->createElement('story');
		$story->setAttribute('uuid',''); //配置属性
		$story->setAttribute('readed','false'); //配置属性
		if(is_array($xmlAll) && count($xmlAll)>0){
			foreach($xmlAll as $i=>$xmla){
				$a = $i + 1;
				if($nowPage == $a){//保存当前页
					$fade = 0;
					if($image_v != ''){$xmla['image'] = $image_v.'.jpg';}
					if($image_v == '1'){$xmla['image'] = '58db9c063360a670458b457a.png';}//黑色背景
					if($image_v == ''){$xmla['image'] = '58db9c383360a670458b457d.png';}//默认背景
					//if($music_v != ''){$xmla['music'] = $music_v;}
					//if($seffect_v != ''){$xmla['seffect'] = $seffect_v;}
					if($fadeIn != 0){$fade += $fadeIn;}
					if($fadeOut != 0){$fade += $fadeOut;}
					if($shake != 0){$fade += $shake;}
					$xmla['fade'] = $fade;
					if($halfman_v != ''){$xmla['halfman'] = $halfman_v.'.jpg';}
					if($halfman_v == '1'){$xmla['halfman'] = '58da51f53360a6c84a8b456f.png';}//清空
					if($halfman_v == ''){$xmla['halfman'] = '58db9c233360a6c84a8b4578.png';}//默认半身像
					//$xmla['music'] = $music_v;
					//$xmla['seffect'] = $seffect_v;
					$xmla['role'] = $role_v;
					$xmla['context'] = $context_v;
					if($music_v != ''){$xmla['music'] = $music_v.'.jpg';}
					else{$xmla['music'] = $music_v;}
					if($seffect_v != ''){$xmla['seffect'] = $seffect_v.'.jpg';}
					else{$xmla['seffect'] = $seffect_v;}
					$xmla['fontName'] = $fontName;
					$xmla['font'] = $font;
				}
				$frame = $xml->createElement('frame');
				$frame->setAttribute('id',$i); 
				$frame->setAttribute('mark','false'); 
				if($nowPage != $a){
					$fade2 = 0;
					if($xmla['fadeIn'] != 0){$fade2 += $xmla['fadeIn'];}
					if($xmla['fadeOut'] != 0){$fade2 += $xmla['fadeOut'];}
					if($xmla['shake'] != 0){$fade2 += $xmla['shake'];}
					$xmla['fade'] = $fade2;
				}
				$_str = 'fade:'.$xmla['fade'].';';
				$frame->setAttribute('style',$_str);
				if($xmla['image'] == '' || !$xmla['image']){$xmla['image'] = '58db9c383360a670458b457d.png';}
				$image = $xml->createElement('image');
				$img_name = $xml->createTextNode($xmla['image']);
				$image->appendChild($img_name);
				
				$music = $xml->createElement('music');
				$music_text = $xml->createTextNode($xmla['music']);
				$music->appendChild($music_text);
				
				$seffect = $xml->createElement('seffect');
				$seffect_text = $xml->createTextNode($xmla['seffect']);
				$seffect->appendChild($seffect_text);
				if($xmla['halfman'] == '' || !$xmla['halfman']){$xmla['halfman'] = '58db9c233360a6c84a8b4578.png';}
				$halfman = $xml->createElement('halfman');
				$halfman_name =  $xml->createTextNode($xmla['halfman']);
				$halfman->appendChild($halfman_name);
				
				$role = $xml->createElement('role');
				$role_name =  $xml->createTextNode($xmla['role']);
				$role->setAttribute('style','font:'.$xmla['font'].';fontName:'.$xmla['fontName']); 
				$role->appendChild($role_name);
				//context
				$context = $xml->createElement('context');
				$context->setAttribute('style','font:'.$xmla['font'].';fontName:'.$xmla['fontName']); 
				$context_text =  $xml->createTextNode($xmla['context']);
				$context->appendChild($context_text);

				$frame->appendChild($image);
				$frame->appendChild($halfman);
				$frame->appendChild($music);
				$frame->appendChild($seffect);
				$frame->appendChild($role);
				$frame->appendChild($context);
				$story->appendChild($frame);
			}
			$xml->appendChild($story);
			$xml->formatOutput = true;
			$xmlName = $xml2.'.xml';
			$res = $xml->save("./upload/xml/".$xmlName);
			if($res){
				$data['success'] = 1;
			}else{
				$data['message'] = '保存失败';
				$data['data'] = $res;
			}
		}else{
			$data['message'] = 'xml文件为空!';
		}	
		$this->ajaxReturn($data);
	}
	//修改作品信息
	public function mongo(){
		 //. 调用自定义的M函数
		//. 连接MySQL数据库
		//echo 'Mysql:';
		//$list = M('article')->select();
		//dump( $list );
		//. 连接MongoDB数据库
		//echo 'Mongo:';
		$data = '';
		$add = array('name'=>'理线器','address'=>'北京');
		$list = $this->Mg('articleimages.chunks')->save($add);
		if($list){
			$data = $list;
		}
		$this->ajaxReturn($data);
		
	}
	//创建xml文件
	public function setXML(){
		$dom = new \DOMDocument('1.0','utf-8'); //创建XML对象
		//创建根节点
		//$data = $dom->createElement('content');
		//$data->setAttribute('width','990'); //配置属性
		//$dom->appendChild($data); //对象中插入根节点
		//利用循环插入子节点
		//foreach($a as $vo){
		//$img = $dom->createElement('page');
		//$img->setAttribute('src', "pages/".$vo);
		//$data->appendChild($img);
		//}
		unlink("./upload/xml/3421490142707.xml");
		echo $res;
		if($res){
			$this->ajaxReturn($res);
		}
	}
	
	
	
}