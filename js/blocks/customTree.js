define(['require','api','context','ui'],function(require,API,context,ui){
    var treeView = null,
        overFlag = false,
        View = Backbone.View.extend({
            el:"#dataItem",
            events:{
                "blur .level-title-input":"onBlur",
                "click .delete-level":"onDel",
                "click .add-level":"onAddSub",
                "click .add-same-level":"onAddSame"
            },
            initialize:function(){
                var _this = this;
                _this.listenTo(Backbone.Events,"tree:get:fail",function(res){
                    if("-1" == res.response.code){
                        _this.data = [];
                        _this.render();
                        overFlag = true;
                    }
                });
                _this.listenTo(Backbone.Events,"tree:get",function(data){
                    //_this.data = data;
                    _this.data = data;
                    _this.render();
                    overFlag = true;
                });
                _this.listenTo(Backbone.Events,"tree:create",function(data){
                    alert("添加成功");
                    API.getTreeInfo();
                    API.getNavData();
                });
                _this.listenTo(Backbone.Events,"tree:update",function(data){
                    alert("修改成功");
                    API.getTreeInfo();
                    API.getNavData();
                });
                _this.listenTo(Backbone.Events,"tree:delete",function(data){
                    alert("删除成功")
                    API.getTreeInfo();
                    API.getNavData();
                });
            },
            onAddSub:function(evt){
                var _this = this,
                    $el = $(evt.target),
                    itemData = _this.getItemData($el),
                    $subWrap = $el.parents('.tree-item-wrap').children(".tree-info-subitem-sub").eq(0),
                    $itemWrap = $el.parents('.tree-item-wrap').eq(0),
                    $siblingsItems = $itemWrap.parent(".tree-info-subitem-sub").children(".tree-item-wrap");

                var itemHtml = getSubHtml(false,false,parseInt($itemWrap.attr('level'))+1);
                $subWrap.append(itemHtml);
                $subWrap.children(".tree-info-subitem").eq(0).addClass('same-level');


                if($siblingsItems.length>1){
                    $siblingsItems.addClass('same-level');
                    $siblingsItems.last().removeClass('same-level');
                }else{
                    $siblingsItems.removeClass('same-level');
                }
            },
            onAddSame:function(evt){
                var _this = this,
                    $el = $(evt.target),
                    itemData = _this.getItemData($el),
                    $itemWrap = $el.parents('.tree-item-wrap').eq(0);

                var itemHtml = getSubHtml(false,false,itemData.level);
                $(itemHtml).insertAfter($itemWrap);
                $itemWrap.addClass('same-level');

            },
            scrollToRightBottom:function(){
                $(".tree-info-body").scrollTop($(".tree-info-body").height());
                $(".tree-info-body").scrollLeft($(".tree-info-body").width());
            },
            onDel:function(evt){
                var _this = this,
                    $el = $(evt.target),
                    title = $el.siblings('.level-name').val(),
                    $itemWrap = $el.parents('.tree-item-wrap').eq(0),
                    itemData = _this.getItemData($el);

                if(confirm("是否确认删除此节点")){
                    if(itemData && itemData.id){
                        API.deleteTree(itemData);
                    }else{
                        $itemWrap.remove();
                    }
                }
            },
            onBlur:function(evt){
                var _this = this,
                    $el = $(evt.target),
                    title = $el.val(),
                    currentTitle = $el.attr('current-title'),
                    itemData;

                if(title == currentTitle){
                    return;
                }
                itemData = _this.getItemData($el);
                if(itemData){
                    itemData.title = title;
                    if(itemData.id){
                        if(!itemData.title || !itemData.title.length){
                            alert('标题不可直接清空');
                            return;
                        }
                        API.updateTree(itemData);
                    }else{
                        API.createTree({
                            title:title,
                            pid:$el.parents(".tree-item-wrap[treeid!='']").attr("treeid")
                        });
                    }
                }
            },
            getItemData:function($el){
                if($el && $el.length){
                    var $itemWrap = $el.parents('.tree-item-wrap');
                    return {
                        id:$itemWrap.attr('treeid'),
                        pid:$itemWrap.attr('pid'),
                        level:$itemWrap.attr('level')
                    }
                }else{
                    return false;
                }
            },
            filterData:function(treeData){
                var subTreesDataMap = null,
                    rootData = null,
                    firsLevel = [];
                $.each(treeData,function(i,ld){
                    if(!ld.pid || "0" == ld.pid){
                        rootData = ld;
                    }else{
                        subTreesDataMap = subTreesDataMap || {};
                        subTreesDataMap[ld.id] = ld;
                        ld["subhtml"] = "";
                        ld["hasSameLevelClass"] = "";
                        ld["level"] = "";
                        subTreesDataMap[ld.id].html = _.template($("#treeInfoItemTpl").html())(ld);
                    }
                })


                if(subTreesDataMap){
                    $.each(subTreesDataMap,function(id,d){
                        if(d.pid) {//链接上下级关系
                            //第一层
                            if(rootData.id == d.pid){
                                firsLevel.push(id);
                            }else if(subTreesDataMap[d.pid]){
                                //上下级关系
                                if(subTreesDataMap[d.pid].sids){
                                    subTreesDataMap[d.pid].sids.push(id);
                                }else{
                                    subTreesDataMap[d.pid].sids = [id];
                                }
                            }else{
                                alert("数据解析失败");
                            }
                        }
                    })
                }

                return {
                    root:rootData,
                    sub:subTreesDataMap,
                    first:firsLevel
                }
            },
            render:function(){
                var _this = this,
                    _data = _this.data,
                    _levelNum = _data.num||5;
                //$("#dataItem").html($("#treeInfoTpl").html());

                if(_data.list && _data.list.length){
                    var treeData = _this.filterData(_data.list);
                    if(treeData.root){
                        $("#dataItem").html(_.template($("#treeInfoTpl").html())(treeData.root));
                    }else{
                        alert("缺少顶级层数据");
                    }
                    if(treeData.first.length){
                        var subTreeHtml='';
                        $.each(treeData.first,function(i,id){
                            if(treeData.first.length>1 && (i != treeData.first.length-1)){
                                treeData.sub[id].hasSameLevelClass = 'same-level';
                            }
                            subTreeHtml+= getSubHtml(id,treeData.sub,1);
                        })
                    }else{
                        subTreeHtml =  getSubHtml()
                    }

                    $(".tree-info-subwrap").html(subTreeHtml);
                }else{
                    $(".tree-info-subwrap").html($("#treeInfoItemTpl").html());
                }

                ui.setH();

            },
            destory:function(){
                console.log('detroy tree')
                this.stopListening()
            }
        });


    function getSubHtml(id,map,level){
        var _html = '',
            sid = id?map[id].sids:[],
            level = parseInt(level);

        if(sid && sid.length){
            var len = sid.length;
            $.each(sid,function(i,_id){
                if(sid.length>1 && (i != len-1)){
                    map[_id].hasSameLevelClass = 'same-level';
                }
                _html += getSubHtml(_id,map,level+1);
            })
            return _.template($("#treeInfoItemTpl").html())($.extend(map[id],{subhtml:_html,level:level}));
        }else{
            if(id && map[id]){
                return _.template($("#treeInfoItemTpl").html())($.extend({},map[id],{
                    subhtml:'',
                    level:level
                }));
            }else{
                data = id&&map?map[id]:{};
                return _.template($("#treeInfoItemTpl").html())($.extend(data,{
                    id:'',
                    pid:'',
                    title:'',
                    level:level,
                    hasSameLevelClass:'',
                    subhtml:''
                }));
            }
        }
    }

    return {
        init:function(sys,listType,sub){
            treeView = new View();
            API.getTreeInfo();
        },
        isOver:function(value){
            if(typeof value == 'undefined'){
                return !!overFlag;
            }else{
                overFlag = !!value;
            }
        },
        destory:function(){
            treeView.destory();
        }
    };
})