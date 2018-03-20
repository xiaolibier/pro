/**
 * file:page
 * author:lixianqi
 * date:2018-3-20
*/

//页面初始化
$(function(){
	var g = {};
	g.httpTip = new Utils.httpTip({});
	g.totalPage = 1;//存总页数
	g.nowPage = 0;//存当前页 0 是第一页
	g.showPages = 10;//每页显示多少行
	g.totalElements = 0;//总数
	g.loadPage = true;//判断页面刚加载一次 定义分页的地方用
	g.interval = '';//存定时器
	g.numX = 0,g.numY = 0,g.numZ = 0,g.numL = 0;//存移动步数

	
	
	loadPage();//加载页面
/*......................lodding.......................................*/
	
	
	//$('.main_btn').mousedown(function(){movePosition();});//上下左右移动
	$('.main_btn').each(function(){
		var aid = $(this).attr('aid') || '';
		$(this).click(function(e){movePosition(aid);e.preventDefault();});//上下左右移动
	});
	//$('.main_btn').mouseup(function(){
	//	clearInterval(g.interval);
	//});//停止移动
	
	
	
	
	
	
	
/*......................setting.......................................*/
	
	//加载页面
	function loadPage(){
		moveIt();//加载默认值
	}
	
	
	//上下左右移动
	function movePosition(aid){
		if(g.interval){clearInterval(g.interval);g.interval = '';return false;}
		//g.num = 0;
		g.interval = setInterval(function(){ moveIt(aid); },100);
	}
	//移动
	function moveIt(aid){
		
		switch(aid){
			case '1':g.numY+=3;break;//上
			case '2':g.numY-=3;break;//下
			case '3':g.numX+=10;break;//左
			case '4':g.numX-=10;break;//右
			case '5':compute();break;//前
			case '6':compute(1);break;//后
			case '7':g.numL+=3;break;//左转
			case '8':g.numL-=3;break;//右转
			default:break;
		}
		$('.world').css({'transform':'translateX('+g.numX+'px) translateZ('+g.numZ+'px) translateY(0px)'});
		$('.view').css({'transform':'rotateY('+g.numL+'deg) rotateX('+g.numY+'deg) translateY(0px) translateX(0px) translateZ(0px) '});
	}
	//计算运动的时候 Z轴 X轴运动多少 
	function compute(is){
		var is = is || '';
		var a = g.numL%360;//将旋转度数大于360度的转化为360度以内
		if(a < 0){ a = 360+a; }//小于零的转化为大于零的
		var ad = 10*(1-(a%90)/90);
		var ab = 10*(a%90)/90;
		if(a >= 0 && a < 90){//0-90度
			if(is != ''){g.numZ -= ad;g.numX += ab;}
			else {g.numZ += ad;g.numX -= ab;}
		}else if(a >= 90 && a < 180){//90-180度
			if(is != ''){g.numZ += ab;g.numX += ad;}
			else {g.numZ -= ab;g.numX -= ad;}
		}else if(a >= 180 && a < 270){//180-270度
			if(is != ''){g.numZ += ad;g.numX -= ab;}
			else {g.numZ -= ad;g.numX += ab;}
		}else if(a >= 270 && a < 360){//270-360度
			if(is != ''){g.numZ -= ab;g.numX -= ad;}
			else {g.numZ += ab;g.numX += ad;}
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

});

