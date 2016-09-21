﻿
function addCookie(objName,objValue,objHours){ 
 var str = objName + "=" + escape(objValue);
 if(objHours !=0)
       {
        var date = new Date();
        var ms = objHours*3600*1000;
        date.setTime(date.getTime() + ms);
        str += "; expires=" + date.toGMTString();
        str += "; path=/";
       }
      document.cookie = str;
}
function delCookie(cname)//为了删除指定名称的cookie，可以将其过期时间设定为一个过去的时间
   {
    var date = new Date();
    date.setTime(date.getTime() - 10000);
    document.cookie = cname + "=a; expires=" + date.toGMTString();
   }
function getCookie(c_name){
	if (document.cookie.length>0)
	  {
	  c_start=document.cookie.indexOf(c_name + "=")
	  if (c_start!=-1)
	    { 
	    c_start=c_start + c_name.length+1 
	    c_end=document.cookie.indexOf(";",c_start)
	    if (c_end==-1) c_end=document.cookie.length
	    return unescape(document.cookie.substring(c_start,c_end))
	    }
	 return false; 
	  }
	return false;
}


/*-----------------------------------------------------------*/
$(function(){
     function QueryString(paramName) {
            var args = new Object();
            var query = location.search.substring(1);
            var pairs = query.split("&");

            for (var i = 0; i < pairs.length; ++i) {
                var pos = pairs[i].indexOf('=');
                if (!pos) continue;
                var paraNm2 = pairs[i].substring(0, pos);
                var val = pairs[i].substring(pos + 1);
                val = decodeURIComponent(val);
                args[paraNm2] = val;
            }
            return args[paramName];
        }

	tel_str=window.location.href;
	if(tel_str.indexOf("?tel=") > 0 ){   //地址栏有电话
        var phone=QueryString("tel");
       
        if(phone.indexOf("/")>0)
        {
            phone=phone.replace("/","");
        }
         
        $.ajax({
            type:"post",
            url:"/Mobile/MAjax.ashx?action=CheckSalesmanPhone&t=" + Math.random(),
            data:"Phone=" + phone,
            beforeSend:function() {},
            success:function(msg) {
              var result = gav(msg, "result");
              if(result==1)
              {
                 addCookie("Phone_num",phone,8640);
                 var Phone_num=phone;
                 $('a').each(function(obj,i){
		         var aa_href = $(this).attr("href");
		         if(aa_href!=null && aa_href.indexOf("tel:") == 0){
                    var arr=aa_href.split(":");
			        $(this).attr("href","tel:"+ Phone_num);
			        if($(this).hasClass("tel") !== true){
                        if($(this).html()=="")
                        {
                            $(this).html(Phone_num);
                        }else
                        {
                            $(this).html(this.innerHTML.replace(arr[1],Phone_num));
                        }
			        }
		        }else if(aa_href!=null && aa_href.indexOf("sms://") == 0)
                {
			        $(this).attr("href","sms://"+ Phone_num);
                }
                })
              }else
              {
                    var Phone_num = getCookie("Phone_num");
                    $('a').each(function(obj,i){
		            var aa_href = $(this).attr("href");
		            if(aa_href!=null && aa_href.indexOf("tel:") == 0){
                        var arr=aa_href.split(":");
                        if(Phone_num!=false){
			                $(this).attr("href","tel:"+ Phone_num);
			                if($(this).hasClass("tel") !== true){
                                 if($(this).html()=="")
                                 {
                                    $(this).html(Phone_num);
                                 }else  
                                 {
                                    $(this).html(this.innerHTML.replace(arr[1],Phone_num));
                                 }
			                }
                        }else{
                            if($(this).html()=="")
                            {
                                $(this).html(arr[1]);
                            }
                        }
		            }else if(aa_href!=null && aa_href.indexOf("sms://") == 0)
                    {
                         if(Phone_num!=false){
                             $(this).attr("href","sms://"+ Phone_num);
                         }
                    }
                 })
              }
            },
            complete:function() {
                
            },
            error:function() {}
        });
	}else   //地址栏没有电话   
    {
            var Phone_num = getCookie("Phone_num");
            $('a').each(function(obj,i){
		    var aa_href = $(this).attr("href");
		    if(aa_href!=null && aa_href.indexOf("tel:") == 0){
                var arr=aa_href.split(":");
                if(Phone_num!=false){
			        $(this).attr("href","tel:"+ Phone_num);
			        if($(this).hasClass("tel") !== true){
                         if($(this).html()=="")
                         {
                            $(this).html(Phone_num);
                         }else
                         {
                            $(this).html(this.innerHTML.replace(arr[1],Phone_num));
                         }
			        }
                }else{
                    if($(this).html()=="")
                    {
                        $(this).html(arr[1]);
                    }
                }
		    }else if(aa_href!=null && aa_href.indexOf("sms://") == 0)
            {
                if(Phone_num!=false){
                     $(this).attr("href","sms://"+ Phone_num);
                }
            }
        })
    }
});

/* 夜间模式  */
var _num=1;    //0代表夜间模式
$(function(){
    $("body").append('<div class="nightMode"></div>');
    $('.nightMode').attr('id','akb_night');
    GetNightMode();
    $("#nightMode").bind("click",SetNightMode);
});

function GetNightMode()
{
     var Night_Mode = getCookie("Night_Mode");
     if(Night_Mode)
     {
        _num=Night_Mode;
     }
     SetNightModeHtml();
}

function SetNightMode()   
{
    var Night_Mode = getCookie("Night_Mode");
    if(Night_Mode)
    {
        _num=Night_Mode==1?0:1;
    }else
    {
        _num=_num==1?0:1;
    }
    addCookie("Night_Mode",_num,1);
    SetNightModeHtml();
}

function SetNightModeHtml()
{
    if( _num ==0 ){
        jelle('akb_night').animate({opacity:'0.4'},1000);
        $("#nightMode").text("日间模式");
        $('.nightMode').show();
    }else {
        $("#nightMode").text("夜间模式");
        jelle('akb_night').animate({opacity:'0'},1000,function(){$('#akb_night').hide()});
    } 
}


$(function() {
    $(".j-slide-not,.j-slide-auto,.j-slide-np").each(function(){
        var clone_text = $(this).find('.m-box').clone();
        $("<div class='sclwrap_box' style='position:relative;'></div>").insertAfter($(this).find('.m-box').get(0));
        $(this).find('.m-box').remove();
        $(this).find('.sclwrap_box').append(clone_text);
    });
    DetailsAutoImgbox();
    footerKefu();
    clearWordHandle();
	productListHandle();
	scrollBar();
	scrollBarAuto();
    Changebox();
});
function DetailsAutoImgbox(){
    if($("body").attr("id") == "Details_Page"){
        var HasSlide_1 = $('.j-slide-np').hasClass("pro_gd");
        var HasSlide_2 = $('.m-rec').hasClass("j-slide-np");
        var HasSlide_3 = $('.m-pp').hasClass("j-slide-np");
        if(HasSlide_1 == true || HasSlide_3 == true){
            var Auto_Imgbox = $("#Details_Page .m-slicon .j-slide-np,#Details_Page .m-pp.j-slide-np");
            var ImgHeight = $(Auto_Imgbox).find("img").css("height");
            ImgHeight = parseInt(ImgHeight)+20;
            $(Auto_Imgbox).find(".sclwrap_box").css("height",ImgHeight+"px");
            
        }
        if(HasSlide_2 == true){
            var Auto_Imgbox2 = $("#Details_Page .m-rec.j-slide-np");
            var ImgHeight2 = $(Auto_Imgbox2).find("img").css("height");
            ImgHeight2 = parseInt(ImgHeight2)+40;
            $(Auto_Imgbox2).find(".sclwrap_box").css("height",ImgHeight2+"px");
        }
    }
};


//输入框获取焦点清除文字
function clearWordHandle() {
    $('.clear_word').each(function () {
        this.onfocus = function () {
            $(this).css('color', '#666666');
            if ($(this).val() == this.defaultValue) {
                $(this).val("");
            }
        }
        this.onblur = function () {
            $(this).css('color', '#D0D0D0');
            if ($(this).val() == '') {
                $(this).val(this.defaultValue);
            }
        }
    });
}

// 商品列表收缩操作
function productListHandle() {
    /*商品列表页面二级收缩*/
    $('.prolist li a').bind("click",function(e){
       e.stopPropagation();
    });
    var list_1 = $('.prolist>li>.down');
    list_1.bind('click', function () {
        var dis = $(this).parent('li').find('ul,div').css('display');
        if (dis == 'none' || dis == '') {
            list_1.parent('li').find('ul,div').hide();
            list_1.removeClass('on');
            list_1.find('a').removeClass('hover');
            $(this).parent('li').find('ul,div').show();
            $(this).addClass('on');
            $(this).find('a').addClass('hover');
            $('.prolist li ul ul,.prolist li div ul').hide();
        } else {
            $(this).parent('li').find('ul,div').hide();
            $(this).removeClass('on');
            $(this).find('a').removeClass('hover');
        }
    });
    /*商品列表页面三级收缩*/
    $('.prolist .list1 span .a_tit').bind('touchstart', function () {
        var obj = this.parentNode.nextElementSibling;
        var dis = obj.style.display;
        if (dis == 'none' || dis == '') {
            $('.prolist .list1 ul').hide();
            obj.style.cssText = "display:block";
        } else {
            obj.style.cssText = "display:none";
        }
    });
    /*商品列表页带图标收缩*/
    $('.prolist_img li .tt_box').bind('touchstart', function () {
        var dis = $(this).parent().find('ul').css('display');
        if (dis == 'none' || dis == '') {
            $('.prolist_img li ul').hide();
            $(this).parent().find('ul').show();
        } else {
            $(this).parent().find('ul').hide();
        }
    });
}

function footerKefu() {     //底部客服
    var sc = $(".social_nav2");
    $(".social_nav2 .btn").bind('click', function () {
        sc.find(".social2").hide();
        sc.show();
        $(this).hide();
        $(".social_nav2 .btn2").css('left', '0').show();
    })
    $(".social_nav2 .btn2").bind('click', function () {
        sc.find(".social2").show();
        sc.show();
        $(this).hide();
        $(".social_nav2 .btn").show();
    });
  
    $(".social_nav3").attr('id','akb_slide');
	$('.social_nav3 .social3').bind('touchmove',function() {	
		jelle('akb_slide').animate({left:'-288px'},200,function(){$('.social_nav3 .btn').hide();$(".social_nav3 .btn2").show();});
		return false;
	});
    $('.social_nav3 .btn').bind('click touchmove',function() {	
		jelle('akb_slide').animate({left:'-288px'},200,function(){$('.social_nav3 .btn').hide();$(".social_nav3 .btn2").show();});
		return false;
	});
	$(".social_nav3 .btn2").bind('click touchmove',function() {
		jelle('akb_slide').animate({left:'0px'},200,function(){$(".social_nav3 .btn2").hide();$('.social_nav3 .btn').show();});
		return false;
	});

     $(".social_nav4").attr('id','akb_slide');
        $('.social_nav4 .social4').bind('touchmove',function() {	
        jelle('akb_slide').animate({left:'-288px'},200,function(){$('.social_nav4 .btn').hide();$(".social_nav4 .btn2").show();});
        return false;
        });
            $('.social_nav4 .btn').bind('click touchmove',function() {	
        jelle('akb_slide').animate({left:'-288px'},200,function(){$('.social_nav4 .btn').hide();$(".social_nav4 .btn2").show();});
        return false;
        });
        $(".social_nav4 .btn2").bind('click touchmove',function() {
        jelle('akb_slide').animate({left:'0px'},200,function(){$(".social_nav4 .btn2").hide();$('.social_nav4 .btn').show();});
        return false;
     });



    /*底部浮动社交栏收缩(圆)*/
    var _dis = $('.u-popup').css('display');
    $('.u-dwnav').bind('click', function () {
        var _dis = $('.u-popup').css('display');
        if (_dis == 'block' || _dis == '') {
            $('.u-popup').hide();
            $('.u-mbg').hide();
            $(this).find('.ico').removeClass('z-gray');
            $('body').removeClass('oh');
        } else {
            $('.u-popup').show();
            $('.u-mbg').show();
            $(this).find('.ico').addClass('z-gray');
            $('body').addClass('oh');
        }
    });
    $('.u-mbg').bind('click', function () {
        $(this).hide();
        $('.u-popup').hide();
        $('.z-gray').removeClass('z-gray');
        $('body').removeClass('oh');
    });
}

function scrollBarAuto() {
    var cc = [], kk = [], uu = [], ap, active = 0;

    /*有时间*/
    $(".j-slide-auto").each(function (dd, n) {
        var r = $(this),
		i = r.find(".m-box"),
		s = r.find(".m-cnt");
        i.attr("id", "slides_control_id" + dd),
		s.attr("id", "pager_id" + dd),
		cc.push({
		    slideId: "slides_control_id" + dd,
		    pageId: "pager_id" + dd,
		    index: 0
		});
    });
    $.each(cc, function (No, obj) {
        var h_body = $("#"+obj.slideId).find("img").attr('height');
        $("#"+obj.slideId).find("img").css('height', h_body + 'px');
        if (!document.getElementById(obj.pageId)) {

            new TouchSlider({
                id: obj.slideId,
                timeout: 3000,
                speed: 400,
                before: function () { },
                after: function () { },
            });
        } else {
            var ap = document.getElementById(obj.pageId).getElementsByTagName('li');
            $("#" + obj.pageId).find("li:first-child").addClass('z-on');
            for (var i = 0; i < ap.length; i++) {
                (function () {
                    var j = i;
                    ap[i].onclick = function () {
                        tt.slide(j);
                        return false;
                    }
                })();
            }
            var tt = new TouchSlider({
                id: obj.slideId,
                timeout: 3000,
                speed: 400,
                before: function (index) { ap[obj.index].className = ''; obj.index = index; ap[obj.index].className = 'z-on'; },
                after: function () { },
            });
        }
    });
}

function scrollBar() {     //滚动JS
    var cc = [], kk = [], uu = [], ap, active = 0;
    $(".j-slide-not .m-cnt li").removeClass('z-on');
    /*无时间*/
    $(".j-slide-not").each(function (dd, n) {
        var r = $(this),
		i = r.find(".m-box"),
		s = r.find(".m-cnt"),
		pr = r.find(".prev"),
		ne = r.find(".next");
        i.attr("id", "slides_control_id_" + dd),
		s.attr("id", "pager_id_" + dd),
		pr.attr("id", "prev_id_" + dd),
	    ne.attr("id", "next_id_" + dd),
		kk.push({
		    slideId: "slides_control_id_" + dd,
		    pageId: "pager_id_" + dd,
		    prevId: "prev_id_" + dd,
		    nextId: "next_id_" + dd,
		    index: 0
		});
    });
    $.each(kk, function (No, obj) {
        if(document.getElementById(obj.pageId))
        {
        var ap = document.getElementById(obj.pageId).getElementsByTagName('li');
        $("#" + obj.pageId).find("li:first-child").addClass('z-on');
        for (var i = 0; i < ap.length; i++) {
            (function () {
                var j = i;
                $("#" + obj.prevId).bind('click', function () {
                    var i = parseInt(active) - 1;
                    i = i < 0 ? i = 0 : i;
                    tt.slide(i);
                })
                $("#" + obj.nextId).bind('click', function () {
                    var i = parseInt(active) + 1;
                    tt.slide(i);
                })
                ap[i].onclick = function () {
                    tt.slide(j);
                    return false;
                }
            })();
        }

        var tt = new TouchSlider({
            id: obj.slideId,
            auto: false,
            speed: 400,
            before: function (index) { ap[obj.index].className = ''; obj.index = index; ap[obj.index].className = 'z-on'; },
            after: function () { },
        });
       }else{
            new TouchSlider({
                id: obj.slideId,
                auto: false,
                speed: 400,
            });
       } 
    });
    /*无时间 左右按钮*/
    $(".j-slide-np").each(function (dd, n) {
        var r = $(this),
		i = r.find(".m-box"),
		pr = r.find(".prev"),
		ne = r.find(".next");
        i.attr("id", "slides-control-id-" + dd),
		pr.attr("id", "prev-id-" + dd),
		ne.attr("id", "next-id-" + dd),
		uu.push({
		    slideId: "slides-control-id-" + dd,
		    prevId: "prev-id-" + dd,
		    nextId: "next-id-" + dd,
		    index: 0,

		});
    });
    $.each(uu, function (no, rr) {
        var size=0;
        if(document.getElementById(rr.slideId))
        {
            size = document.getElementById(rr.slideId).childElementCount;
        }
        if(size<2)
        {
          $('#' + rr.prevId).hide();
          $('#' + rr.nextId).hide();
        }
        $('#' + rr.prevId).bind('click', function () {
            var i = parseInt(rr.index) - 1;
            i = i < 0 ? i = 0 : i;
            ck.slide(i);
        });
        $('#' + rr.nextId).bind('click', function () {
            var i = parseInt(rr.index) + 1;
            i = i >= size - 1 ? i = size - 1 : i;
            ck.slide(i);
        });
        var ck = new TouchSlider({
            id: rr.slideId, speed: 600, timeout: 1000, auto: false,
            before: function (index) { rr.index = index; },
            after: function (index) {
                $('#' + rr.nextId).css("opacity","1");
                $('#' + rr.prevId).css("opacity","1");
                var si_ze = size - 1;
                if (rr.index == si_ze) { $('#' + rr.nextId).css("opacity","0.3"); }
                if (rr.index == 0) { $('#' + rr.prevId).css("opacity","0.3"); }
            }
        });
    });

    /*首页总导航 状态栏少于1 隐藏*/
    $('.m-box').each(function () {
        var k = this.childElementCount;
        if (k < 2) {
            $(this).parent().find('.m-cnt').hide();
            $(this).parent().find('.prev,.next').hide();
            $(this).parent().siblings('.prev,.next').hide();
        } else if (k > 1) { return false }
    })
	$('.m-cnt.m-cnt2 li:first-child').addClass('z-on');
};
