<?php
namespace Home\Model;

use Think\Model;
use Think\Model\UserInfoModel;

class ArticleModel extends Model
{
	
	//上传作品 修改作品xml地址 修改作品存放位置
	public function uploadArticle($xmlId,$fileDir,$article_id){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('article_id' => $article_id);
		$save = array('article_root_xml' => $xmlId,'article_fs_name'=>$fileDir,'article_status'=>'0');
		$article = M('article');
		$res1 = $article->where($where1)->select();
		if(is_array($res1) && count($res1)>0){
			$res2 = $article->where($where1)->save($save);
			if($res2){
				$res3 = $article->where($where1)->find();
				$data['success'] = 1;
				$data['data'] = $res3;
				$data['message'] = '作品上传成功！';
			}else{
				$data['message'] = '作品上传失败！';
				$data['data'] = $res2;
			}
		}else{
			$data['message'] = '作品不存在！';
		}
		return $data;
	}
	//修改作品信息
	public function changeArticle($art_title,$article_id,$images){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('article_id' => $article_id);
		$save = array('article_title' => $art_title,'article_image'=>$images,'article_image_min'=>$images);
		$article = M('article');
		$res1 = $article->where($where1)->select();
		if(is_array($res1) && count($res1)>0){
			$res2 = $article->where($where1)->save($save);
			if($res2){
				$res3 = $article->where($where1)->find();
				$data['success'] = 1;
				$data['data'] = $res3;
				$data['message'] = '修改作品成功！';
			}else{
				$data['message'] = '修改作品失败！';
				$data['data'] = $res2;
			}
		}else{
			$data['message'] = '作品不存在！';
		}
		return $data;
	}
	//创建作品
	public function creatArticle($art_title,$images,$token,$usrid,$xmlName){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('article_title' => $art_title);
		$xmlName = substr($xmlName,0,strlen($xmlName)-4);//截取.xml
		$createDate = time();
		$add = array('article_title' => $art_title,'article_author_name'=>$token,
		'article_author_id'=>$usrid,'article_image'=>$images,'article_image_min'=>$images,
		'article_root_xml'=>$xmlName,'article_create_date'=>$createDate,'article_status'=>'-1');
		$article = M('article');
		$res1 = $article->where($where1)->select();
		if(is_array($res1) && count($res1)>0){
			$data['message'] = '作品名称已存在！请改用其他名称';
		}else{
			$res2 = $article->add($add);
			if($res2){
				$where2 = array('article_title' => $art_title);
				$res3 = $article->where($where2)->find();
				$data['success'] = 1;
				$data['data'] = $res3;
				$data['message'] = '创建临时作品成功！';
			}else{
				$data['message'] = '创建临时作品失败！';
			}
		}
		return $data;
	}
	//删除用户作品
	public function deleteArticle($article_id){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('article_id' => $article_id);
		$article = M('article');
		$res = $article->where($where1)->find();
		$xml = $res['article_root_xml'];
		$res1 = $article->where($where1)->delete();
		if($res1){
			$data['message'] = '删除成功！';
			$data['success'] = 1;
			$data['data'] = $res1;
			unlink("./upload/xml/".$xml.".xml");//删除xml文件
		}else{
			$data['message'] = '删除失败！';
		}
		return $data;
	}
	//获取用户作品
	public function getMyArticle($token){
		$data = array('success' => 0,'data'=>array(),'message'=>'');
		$where1 = array('user_name' => $token);
		$article = M('article');
		$res1 = $article->table('article a,user_info b')->where('a.article_author_id = b.user_id')->where($where1)->select();
		if(is_array($res1) && count($res1)>0){
			foreach ($res1 as $r => $e){
				$date = $res1[$r]['article_create_date'];
				$res1[$r]['article_create_date'] = date('Y-m-d',$date);
			}
			$data['success'] = 1;
			$data['data'] = $res1;
		}else{
			$data['message'] = '作品为空！';
		}
		return $data;
	}
	
	
	
}