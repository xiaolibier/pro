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
	g.id = '';//存储当前选中元素id
	g.allbody = [];
	
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
	$('.close_btn').on('click',closeTools);//关闭工具窗
	$('#changeOne').on('click',function(){ changeOne(2); });//修改元素
	$('#deleteOne').on('click',deleteOne);//删除元素
	$('#createOne').on('click',function(){ changeOne(1); });//创建元素
	$('#clearInput').on('click',function(){ $('.tool').find('input').val(''); });//清空输入框
	//$('#world_html').on('click','.ele',function(){ choiseOne($(this)) });//选择元素
	$('#world_html').on('click','.suf',function(){var obj = $(this).parents('.ele'); choiseOne(obj) });//选择元素
	
	
	
	
	setGetSearchInput2('#name','.choiseDanwei1');
	
/*......................setting.......................................*/
	
	//加载页面
	function loadPage(){
		moveIt();//加载默认值
		getAll();//加载所有元素
	}
	
	//点击+号
	$('.tools_ul').find('input').change(function(){
		setITValue();//实时编辑元素
	});
	
	//改变工具值
	$('.tools_ul').on('click','.value_btn',function(){
		add($(this));//添加或减少
	});
	var inter ;//存定时器
	$('.tools_ul').on('mousedown','.value_btn',function(){
		var obj = $(this);
		inter = setInterval(function(){
			add(obj);//添加或减少
		},100);
	});
	$(document).on('mouseup',function(){
		clearInterval(inter);
	});
	//添加或减少
	function add(obj){
		var _this = obj || $(this);
		if(_this == ''){console.log('对象不存在！');return false;}
		var html = _this.html() || '';
		var input = _this.siblings('.value_input');
		var num = parseInt(input.val() || '0');
		if(html.indexOf('+') > -1){//加
			num++;
			input.val(num);
		}else if(html.indexOf('-') > -1){//减
			num--;
			input.val(num);
		}
		setITValue();//实时编辑元素
	}
	
	//实时编辑元素
	function setITValue(){
		var data = {},condi = [];
		if($('.ele.active').length <= 0){Utils.alert("请先选择元素！");return false;}
		data = setInfoCondi(data,'.tools_ul','.li');//传值
		if(data == false){return false;}
		condi.push(data);
		var html = setVals(condi,'new');//获取html
		$('.ele.active').remove();
		$('#world_html').append(html);
		$('.ele.new').removeClass('new').addClass('active');
	}
	
	//图片上传
	$(".orderMaterialFile").change(function(){
		var $me = $(this);
        var $img = $me.parent().find('img');
        var file = this.files[0];
        var formData = new FormData();
        formData.append('file', file);
        $.ajax({
            type: "POST", //必须用post
            url: Base.serverUrl + "imgUpload",
            crossDomain: true,
            jsonp: "jsoncallback",
            data: formData,
            contentType: false, //必须
            processData: false,
            //不能用success，否则不执行
            complete: function (data) {
				var success=jQuery.parseJSON(data.responseText).success;
                var src=jQuery.parseJSON(data.responseText).data;
                if(success){
					src = Base.imgUrl + src;
					src = 'background: url(\''+src+'\') no-repeat center center;background-size:cover';
					$me.siblings('.value_input').val(src);
					setITValue();//实时编辑元素
				}else {
					Utils.alert("图片上传失败");
				}
            }
        });
	});

	//选择元素
	window.choiseOne = function(_this){
		var _this = _this || '';
		_this = _this == '' ? $(this) : _this;
		$('#world_html').find('.ele').removeClass('active');
		_this.addClass('active');
		var name = _this.attr('title') || '';
		var id = _this.attr('aid') || '';
		if(id == ''){Utils.alert('当前元素id为空！');return false;}
		g.id = id;
		var data = g.allbody || [];//所有元素
		var condi = '';
		for(var i=0,len=data.length;i<len;i++){//循环找出匹配项
			var d = data[i] || {};
			var tid = d.id || '';
			if(tid == id)condi = d;
		}
		if(condi == ''){Utils.alert('当前元素id不存在！');return false;}
		setCondiVal(condi,'.tools_ul','.li');//赋值
	}
	
	//加载所有元素
	function getAll(){
		var condi = {};
		var url = Base.serverUrl + "getOne";
		//g.httpTip.show();
		$.ajax({
			url:url,
			data:condi,
			timeout: 30000, //超时时间设置，单位毫秒
			type:"POST",
			xhrFields: {
				withCredentials: true
			},
			crossDomain: true,
			dataType:'json',
			context:this,
			success: function(data){
				var status = data.success || false;
				if(status){
					var data = data.data || [];
					var ul = '';
					for(var i=0,len=data.length;i<len;i++){
						var d = data[i] || [];
						var id = d.id || '';
						var name = d.name || '';
						ul += '<li onclick="lichoise(\''+id+'\')" tip="'+id+'">'+name+'</li>';
					}
					$('.choiseDanwei1 li').remove();
					$('.choiseDanwei1').append(ul);
					g.allbody = data || [];//存储所有元素
					var html = setVals(data);//获取html
					$('#world_html').html(html);
					if(g.id != ''){//默认选中之前选的元素
						choiseOne($('.element'+g.id));
					}
				}
				else{
					var msg = data.message || "失败";
					Utils.alert(msg);
					$('#world_html').html('');
				}
				//g.httpTip.hide();
			},
			error:function(data,status){
				//g.httpTip.hide();
				if(status=='timeout'){
		　　　　　  Utils.alert("超时");
		　　　　}
				$('#world_html').html('');
			}
		});
	}
	//选择名称
	window.lichoise = function(id){
		var id = id || '';
		if(id == ''){Utils.alert('元素id为空！');return false;}
		g.id = id;
		choiseOne($('.element'+g.id));
	}
	
	//元素赋值
	function setVals(data,isnew){
		var data = data || '';
		var isnew = isnew || '';
		if(data == ''){return false;}
		var html = '';
		for(var i=0,len=data.length;i<len;i++){
			var d = data[i] || {};
			var id = d.id || '';
			var name = d.name || '';
			var height = d.height || '0';
			var width = d.width || '0';
			var _long = d.long || '';
			var left = d.left || '';
			var _top = d.top || '';
			var right = d.right || '';
			var bottom = d.bottom || '';
			var tX = d.translatex || '';
			var tY = d.translatey || '';
			var tZ = d.translatez || '';
			var rX = d.rotatex || '';
			var rY = d.rotatey || '';
			var rZ = d.rotatez || '';
			var topsrc = d.topsrc || '';
			var bottomsrc = d.bottomsrc || '';
			var leftsrc = d.leftsrc || '';
			var rightsrc = d.rightsrc || '';
			var beforesrc = d.beforesrc || '';
			var aftersrc = d.aftersrc || '';
			var transform = ' transform:';
			if(tX != '') transform+=' translateX('+tX+'px) ';
			if(tY != '') transform+=' translateY('+tY+'px) ';
			if(tZ != '') transform+=' translateZ('+tZ+'px) ';
			if(rX != '') transform+=' rotateX('+rX+'deg) ';
			if(rY != '') transform+=' rotateY('+rY+'deg) ';
			if(rZ != '') transform+=' rotateZ('+rZ+'deg) ';
			transform+=';';
			
			var style = 'width:'+width+'px;height:'+height+'px;'+transform;
			if(left != '') style+='left:'+left+'px;';
			if(_top != '') style+='top:'+_top+'px;';
			if(right != '') style+='right:'+right+'px;';
			if(bottom != '') style+='bottom:'+bottom+'px;';
			
			html+='<div title="'+name+'" aid="'+id+'" style="'+style+'" class="ele '+isnew+' element'+id+'"><div class="elec">'
					+'<div class="suf top" style="width:'+width+'px;height:'+_long+'px;'+topsrc+';"></div>'
					+'<div class="suf bottom" style="width:'+width+'px;height:'+_long+'px;'+bottomsrc+';"></div>'
					+'<div class="suf left" style="width:'+_long+'px;height:'+height+'px;'+leftsrc+';"></div>'
					+'<div class="suf right" style="width:'+_long+'px;height:'+height+'px;'+rightsrc+';"></div>'
					+'<div class="suf before" style="width:'+width+'px;height:'+height+'px;'+beforesrc+';"></div>'
					+'<div class="suf after" style="width:'+width+'px;height:'+height+'px;transform:translateZ(-'+_long+'px);'+aftersrc+';"></div>'
				+'</div></div>';
		}
		return html;
	}
	
	//删除当前元素
	function deleteOne(){
		var condi = {};
		if(g.id == ''){Utils.alert('请先选择元素，当前id为空');return false;}
		var name = $('#name').val() || '';
		if(!confirm('确认删除当前元素'+name+'吗？')){return false;}
		condi.id = g.id ;
		var url = Base.serverUrl + "deleteOne";
		//g.httpTip.show();
		$.ajax({
			url:url,
			data:condi,
			timeout: 30000, //超时时间设置，单位毫秒
			type:"POST",
			xhrFields: {
				withCredentials: true
			},
			crossDomain: true,
			dataType:'json',
			context:this,
			success: function(data){
				var status = data.success || false;
				if(status){
					Utils.alert('删除成功！');
					getAll();//加载所有元素
				}
				else{
					var msg = data.message || "失败";
					Utils.alert(msg);
				}
				//g.httpTip.hide();
			},
			error:function(data,status){
				//g.httpTip.hide();
				if(status=='timeout'){
		　　　　　  Utils.alert("超时");
		　　　　}
			}
		});
	}
	
	//修改元素
	function changeOne(is){
		var is = is || '';//is 2 修改或保存 1 新建
		var condi = {};
		if(is == 2 && g.id == ''){Utils.alert('请先选择元素，当前id为空');return false;}
		condi.id = g.id ;
		condi = setInfoCondi(condi,'.tools_ul','.li');//传值和校验
		if(condi == false){return false;}
		var url = Base.serverUrl + "changeOne";
		if(is == '1'){url = Base.serverUrl + "createOne"}
		//g.httpTip.show();
		$.ajax({
			url:url,
			data:condi,
			timeout: 30000, //超时时间设置，单位毫秒
			type:"POST",
			xhrFields: {
				withCredentials: true
			},
			crossDomain: true,
			dataType:'json',
			context:this,
			success: function(data){
				var status = data.success || false;
				if(status){
					var d = data.result || [];
					var tip = is != '1' ? '保存成功！' : '新建成功！' ;
					Utils.alert(tip);
					if(is == '1'){//新建项目
						var d = data.data || {};
						var id = data.id || '';
						if(id != ''){g.id = id;}//默认选中新建项
					}else if(is == '2'){//保存项目
						
					}
					getAll();//加载所有元素
				}
				else{
					var msg = data.message || "保存失败";
					Utils.alert(msg);
				}
				//g.httpTip.hide();
			},
			error:function(data,status){
				//g.httpTip.hide();
				if(status=='timeout'){
		　　　　　  Utils.alert("超时");
		　　　　}
			}
		});
		
	}
	
	
	
	//关闭工具窗
	function closeTools(){
		$('.tool').toggleClass('hide');
		var _left = $('.tool').hasClass('hide') ? 0 : $('.tool').width() ;
		$('.main_table').css({'left':_left+'px'});
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

