define(["require","api","backbone","context","ui","main","login","map"],function(require,API,Backbone,context,ui){
    var defaultManagePage = "manage/station",main;
    var routeMap = {
        index:function(){
            API.getSyestemConfig();
            var $this = this;
            this.listenTo(Backbone.Events,"systemConfig:update",function(data){
                $this.login();
                return;
                if(data){
                    if(data.show_map && data.show_map =="1"){
                        if(data.is_WAN == "1"){
                            route.navigate('map',{trigger:true});
                        }else{
                            route.navigate(defaultManagePage,{trigger:true});
                        }
                    }else{
                        route.navigate(defaultManagePage,{trigger:true});
                    }
                }else{

                }
            })
        },
        login:function(){
            $.get("templates/login.html?_r="+Math.random(),function(ret){
                require(["login"],function(login){
                    $("body").html(ret);
                    login.init();
                })
            })
        },
        manage:function(sys,pageType,sub,ids){
            if (!pageType) {
                route.navigate(defaultManagePage,{trigger:true});
                return;
            }

            if(!$("#wrap").length){
                $.get("templates/system.html?_r="+Math.random(),function(ret){
                    $("body").html(ret);
                    load();
                })
            }else{
                load();
            }

            function load(){
                if(!$("#main").html().length || $("#map").length){
                    $.get("templates/manage.html?_r="+Math.random(),function(ret){
                        $("#main").html(ret);
                        require(["main"],function(_main){
                            main = _main;
                            ui.switchListAlertBox(pageType)
                                .switchBtnGroups(sys,pageType,sub);
                            main.init(sys,pageType,sub,ids);
                        })
                    })
                }else{
                    ui.switchListAlertBox(pageType)
                        .switchBtnGroups(sys,pageType,sub);
                    main.refresh(sys,pageType,sub,ids);
                }
            }

        },
        map:function(sys,pageType,sub){
            if(!$("#wrap").length){
                $.get("templates/system.html?_r="+Math.random(),function(ret){
                    $("body").html(ret);
                    load();
                })
            }else{
                load();
            }

            function load(){
                $.get("templates/map.html?_r="+Math.random(),function(ret){
                    $("#main").html(ret);
                    try{
                        delete navTree;
                        navTree = null;
                    }catch(e){
                        console.log(e);
                    }
                    
                    require(["map","ui"],function(map,ui){
                        ui.resize();
                        map.init();
                    })
                })
            }
        },
        manageID:function(pageType,sid){
            this.manage('manage',pageType,false,{sid:sid});
        }
    };
    routeMap.routes = {
        "":"index",
        ":manage/:listType":"manage",
        "manage/:listType/:id":"manageID",
        ":settings/:listType":"manage",
        ":settings/:listType/:subListType":"manage",
        ":report/:listType":"manage",
        ":qurey/:listType":"manage",
        "map":"map",
        "login":"login"
    }

    $("#wrap").show();
    var route = Backbone.Router.extend(routeMap);
    route = new route();

    return {
        to:function(url){
            route.navigate(url,{trigger:true});
        },
        start: function () {
            Backbone.history.start();
        }
    }
})