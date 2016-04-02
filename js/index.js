require.config({
    baseUrl:'',
    urlArgs:'_v=1.8',
    packages:[
        {
            name: 'zrender',
            location: 'libs/chart/zrender', // zrender与echarts在同一级目录
            main: 'zrender'
        }
    ],
    waitSeconds: 0,
    paths:{
        'jquery': 'libs/jquery.min',
        'jqueryUI':'libs/jqueryui/jquery-ui.min',
        'jJson': 'libs/jquery.json',
        'jForm': 'libs/jquery.jqtransform',
        'jtimer': 'libs/jQuery.timers',
        'domReady':'libs/domReady',
        'backbone':'libs/backbone-min',
        '_':'libs/underscore-min',
        'bootstrap':'libs/bootstrap',
        "charts":"libs/echarts",
        "echarts":"libs/chart/echarts.amd",
        'zTreeCore':'libs/jquery.ztree.core-3.5',
        'zTreeExcheck':'libs/jquery.ztree.excheck-3.5',
        'scrollbar':'libs/scrollbar/min/perfect-scrollbar.jquery.min',
        'mCustomScrollbar':'libs/jquery.mCustomScrollbar.concat.min',
        'table':'libs/jquery.dataTables',
        'fixedColumn':'libs/dataTables.fixedColumns.min',
        'fixedHeader':'libs/dataTables.fixedHeader.min',
	    "respond":"js/respond",
        "router" :"js/router",
        "api" :"js/api",
        "main" :"js/main",
        "login" :"js/login",
        "ui" :"js/ui",
        "common" :"js/common",
        "map" :"js/map",
        "context" :"js/context_model",
        "blocks":"js/blocks",
        "stationsinfoDialog":"js/dialog-stationsinfo"
    },
    shim:{
        bootstrap:['jquery'],
        table:['jquery'],
        jtimer:['jquery'],
        fixedColumn:['jquery','table'],
        common:['jquery'],
        zTreeCore:['jquery'],
        zTreeExcheck:['zTreeCore'],
        jJson:['jquery'],
        jForm:['jquery'],
        jqueryUI:['jquery'],
        backbone:['_'],
	    respond:['']
    }
})

require(["jquery","router","table","jJson","jtimer","charts"],function($,router){
    /**
     * 对Date的扩展，将 Date 转化为指定格式的String
     * 月(M)、日(d)、12小时(h)、24小时(H)、分(m)、秒(s)、周(E)、季度(q) 可以用 1-2 个占位符
     * 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)
     * eg:
     * (new Date()).pattern("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
     * (new Date()).pattern("yyyy-MM-dd E HH:mm:ss") ==> 2009-03-10 二 20:09:04
     * (new Date()).pattern("yyyy-MM-dd EE hh:mm:ss") ==> 2009-03-10 周二 08:09:04
     * (new Date()).pattern("yyyy-MM-dd EEE hh:mm:ss") ==> 2009-03-10 星期二 08:09:04
     * (new Date()).pattern("yyyy-M-d h:m:s.S") ==> 2006-7-2 8:9:4.18
     */
    Date.prototype.pattern=function(fmt) {
        var o = {
            "M+" : this.getMonth()+1, //月份
            "d+" : this.getDate(), //日
            "h+" : this.getHours()%12 == 0 ? 12 : this.getHours()%12, //小时
            "H+" : this.getHours(), //小时
            "m+" : this.getMinutes(), //分
            "s+" : this.getSeconds(), //秒
            "q+" : Math.floor((this.getMonth()+3)/3), //季度
            "S" : this.getMilliseconds() //毫秒
        };
        var week = {
            "0" : "日",
            "1" : "一",
            "2" : "二",
            "3" : "三",
            "4" : "四",
            "5" : "五",
            "6" : "六"
        };
        if(/(y+)/.test(fmt)){
            fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
        }
        if(/(E+)/.test(fmt)){
            fmt=fmt.replace(RegExp.$1, ((RegExp.$1.length>1) ? (RegExp.$1.length>2 ? "星期" : "周") : "")+week[this.getDay()+""]);
        }
        for(var k in o){
            if(new RegExp("("+ k +")").test(fmt)){
                fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
            }
        }
        return fmt;
    }

    setInterval(function(){
        $("#realtime").html((new Date()).pattern("yyyy-MM-dd EEE hh:mm:ss"));
    },1000)

    require(["jJson","bootstrap","jqueryUI","ui","jForm"],function(){
        $(document).tooltip();
        router.start();
    });
})