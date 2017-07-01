/**
 * Created by Administrator on 15-12-26.
 */
$(function(){
    var wH=$(window).height(),
        topH=$("div.top").outerHeight(),
        bottomH=$("div.bottom").outerHeight();

    //布局--设置容器高度
    $("div.wrap").height(wH-topH-bottomH);
    var wrapH=$("div.wrap").height();
    //布局--设置右侧底部的容器高度
    $("div.down").height(wrapH-wrapH*0.65-10);

    var statusH=$("div.status").outerHeight(),
        dlH=$("dl.set-list").outerHeight();
    //布局--设置左侧导航树容器高度
        $("div.nav-tree").height(wrapH-statusH-dlH-95);
        $(window).resize(function(){
            $("div.nav-tree").height(wrapH-statusH-dlH-95);
        });
    //布局--设置右侧上部分显示数据容器高度
    setH();

});

//布局--设置右侧上部分显示数据容器高度
function setH(){
    var upH=$("div.up").height();
    $("div.data-item").height(upH-100);
}

//左侧数隐藏
function leftHide(){
        $("div.left").hide();
        $("div.right").css("margin-left",0);
        $("b.yx-show").show();
}

//左侧显示
function leftShow(){
    $("div.left").show();
    $("div.right").css("margin-left",304);
    $("b.yx-show").hide();
    if($("span.zoom").hasClass("big")){
        $("span.zoom").removeClass("big");
    }
}

//右侧底部隐藏
function downHide(){
    $("div.down").hide();
    $("div.up").css("height","100%");
    $("b.bottom-show").show();
    setH();
}

//右侧底部显示
function downShow(){
    $("div.down").show();
    $("div.up").css("height","65%");
    $("b.bottom-show").hide();
    if($("span.zoom").hasClass("big")){
        $("span.zoom").removeClass("big");
    }
    setH();
}

//右侧上部最大化、缩小
function upShow(ele){
    if($(ele).attr("class")=="zoom"){
        $(ele).addClass("big");
        leftHide();
        downHide();
    }else{
        $(ele).removeClass("big");
        leftShow();
        downShow();
    }
}

