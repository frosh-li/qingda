define(["require","backbone","api","stationsinfoDialog","common"],function(require,Backbone,API,stationInfoDialog,common){
    var MARK = {
            w:27,
            h:38,
            icon:{
                "0":"images/1.png",
                "1":"images/2.png",
                "2":"images/3.png",
                "3":"images/4.png"
            },
            label:{
                normal:{
                    color:"#000000",
                    fontWeight:"bold",
                    fontSize:"14px",
                    backgroundColor:"#fff5cd",
                    border:"1px solid #ffcc69"
                },
                caution:{
                    color:"#000000",
                    fontWeight:"bold",
                    fontSize:"14px",
                    backgroundColor:"#ffd9d9",
                    border:"1px solid #be1616"
                },
                notice:{
                    color:"#000000",
                    fontWeight:"bold",
                    fontSize:"14px",
                    backgroundColor:"#ffd9d9",
                    border:"1px solid #be1616"
                }
            }
        },
        DEFAULT_CENTER_POINT = {x:104.603639,y:35.140578,zoom:5};
    var widgetMap =  Backbone.View.extend({
        options:{},
        initialize: function(options) {
            var _this = this;

            $.extend(_this.options,options||{});

            _this.listenTo(Backbone.Events,"realtime:mapdata",function(data){
                _this.data = data.list;
                _this.addMarks();
                _this.buildCityList();
            })
            _this.listenTo(Backbone.Events,"mapdata:update",function(data){
                _this.data = data.list;
                _this.addMarks();
                _this.buildCityList();
                _this.centerAndZoom({
                    x: DEFAULT_CENTER_POINT.x,
                    y: DEFAULT_CENTER_POINT.y,
                    zoom:DEFAULT_CENTER_POINT.zoom
                })
            })
            _this.listenTo(Backbone.Events,"mapdata:update:fail",function(data){
                _this.centerAndZoom({
                    x: DEFAULT_CENTER_POINT.x,
                    y: DEFAULT_CENTER_POINT.y,
                    zoom:DEFAULT_CENTER_POINT.zoom
                })
                $(".mapbtn").html('<select><option>全国</option></select>');
            })

            _this.listenTo(Backbone.Events,"linknum:get",function(data){
                $("#linkingNum").html(data.online)
                $("#unlinkNum").html(data.offline)
            });

            _this._initBDMapCallBack();
            _this._loadBDMap();
        },
        render:function(){
            var _this = this,
                _options = _this.options;
            this.map = new BMap.Map("map");
            this.map.enableScrollWheelZoom(true);

            //_this.centerAndZoom(_options.center||DEFAULT_CENTER_POINT);
            //城市列表
            if(_options.cityList){
                _this.addCityList()
            }
        },
        addMarks:function(){
            var _this = this,
                _data = _this.data,
                _map = _this.map,
                _alarmNum = 0;
            if(_data && _data.length){
                $.each(_data,function(i,d){
                    var pt = new BMap.Point(d["site_longitude"], d["site_latitude"]);
                    var icon = new BMap.Icon(MARK.icon[d.status||'0'], new BMap.Size(MARK.w,MARK.h));
                    var marker = new BMap.Marker(pt,{icon:icon});  // 创建标注
                    var namelength = d.site_name.replace(/[^\x00-\xff]/g, "aa").length.toString();
                    var label = new BMap.Label(d.site_name,{offset:new BMap.Size(-(namelength*6)/2+8,40)});//文字标注
                    label.setStyle(MARK.label[d.status||'0']);

                    if(parseInt(d.status||'0')>0){
                        _alarmNum++;
                    }

                    marker.addEventListener("click", function(_d){
                        return function(){
                            stationInfoDialog.show(_d.serial_number);
                        }
                    }(d));

                    marker.setLabel(label);
                    _this.map.addOverlay(marker);

                    _map[d.id] = $.extend(true,d,{
                        icon:icon,
                        marker:marker
                    })
                })
            }
            $(".baojing .bg").html(_alarmNum);
        },
        centerAndZoom:function(center){
            var point = new BMap.Point(center.x, center.y);  // 创建点坐标
            this.map.centerAndZoom(point, center.zoom);
        },
        buildCityList:function(){
            var _this = this,
                data = this.data,
                myGeo = new BMap.Geocoder(),
                adds = [],
                selectHtml = '<select><option>全国</option>',
                count = 0,
                dataLen = data.length;
            $.each(data,function(i,d){
                var pt = new BMap.Point(d["site_longitude"],d["site_latitude"]);
                myGeo.getLocation(pt, function(rs){
                    var addComp = rs.addressComponents;
                    if(!common.inArray(addComp.city,adds)){
                        adds.push(addComp.city);
                        selectHtml += '<option>'+addComp.city+'</option>';
                    }
                    count++;
                    if(count == dataLen){
                        selectHtml+='</select>';
                        $(".mapbtn").html(selectHtml);
                        $(".mapbtn select").off("change").on("change",function(){
                            var prov = $(".mapbtn select option:selected").html();
                            if("全国" == prov){
                                _this.centerAndZoom({
                                    x: DEFAULT_CENTER_POINT.x,
                                    y: DEFAULT_CENTER_POINT.y,
                                    zoom:DEFAULT_CENTER_POINT.zoom
                                })
                            }else{
                                myGeo.getPoint(prov, function(point){
                                    if (point) {
                                        _this.map.centerAndZoom(point, 12);
                                    }else{
                                        alert("您选择地址没有解析到结果!");
                                    }
                                });
                            }
                        })
                    }
                });
            })


        },
        to:function(point){
            this.map.panTo(new BMap.Point(point.x,point.y));
        },
        addCityList:function(){
            var size = new BMap.Size(10, 20);
            this.map.addControl(new BMap.CityListControl({
                anchor: BMAP_ANCHOR_TOP_LEFT,
                offset:size,
                //切换城市之间事件
                onChangeBefore: function(){
                    //alert('before');
                },
                //切换城市之后事件
                onChangeAfter:function(){
                    //alert('after');
                }
            }));
        },
        /**
         * @description 异步载入百度地图
         */
        _loadBDMap: function() {
            var script = document.createElement("script");
            script.type = "text/javascript";
            script.src = "http://api.map.baidu.com/api?v=2.0&ak=1b010415afbe080fcca3807108847acc&callback=MapCallback";
            document.body.appendChild(script);
        },

        /**
         * @description 设置地图回调函数
         */
        _initBDMapCallBack: function() {
            var _this = this;
            window.MapCallback = function() {
                _this.render();

                API.getMapData();
            }
        }
    });
    var map = null;


    return {
        init:function(pageType){
            map = new widgetMap();
            stationInfoDialog.init();
            API.getLinkingStationNum();
            return this;
        }
    };
})