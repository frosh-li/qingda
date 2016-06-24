define(['require','api','backbone','zTreeExcheck'],function(require,API,Backbone){
    var zTree,
        setting = {
            check: {
                enable: true,
                chkStyle:"checkbox",
                nocheckInherit:true
            },
            data: {
                key:{
                    name:'title'
                },
                simpleData: {
                    enable: true,
                    pIdKey: 'pid'
                }
            }
        },
        view;
    var extobj = {
        name:"nav",
        data:null,
        tree:null,
        navPlugin:null,
        ids:[],
        initialize:function(option){
            var _this = this;
            _this.option={};
            $.extend(_this.option,option||{});
            _this.listenTo(Backbone.Events,"tree:get",function(data){
                _this.data = data.list;
                _this.filterData();
                _this.render();
            });
        },
        filterData:function(){
            var _this = this;
            if(_this.data){
                $.each(_this.data,function(i,d){
                    if(_this.option.value && _this.option.value == d.id ){
                        d.checked = true
                    }
                })
            }
        },
        getCheckedData:function(){
            this.ids = [];
            var _this = this;
            var checkedNodes = this.tree.getCheckedNodes(),
                checkedData = {};
            $.each(checkedNodes,function(i,node){
                _this.ids.push(node.id);
                checkedData[node.id] = {
                    id:node.id,
                    pId:node.pId,
                    name:node.name,
                    level:node.level
                }
            })
            return checkedData;
        },
        getValue:function(){
            this.getCheckedData();
            return this.ids.join(",");
        },
        setValue:function(id){
            if(id){
                var node = this.tree.getNodesByParam("id",id);
                this.tree.checkNode(node,true,true);
            }
        },
        destroy:function(){
            this.stopListening();
        },
        render:function(){
            var _this = this,$radios;
            _this.ids = [];
            $.fn.zTree.init($("#treesWrap"), setting, _this.data);
            _this.tree = $.fn.zTree.getZTreeObj('treesWrap');
            _this.tree.expandAll(true);
            return this;
        }
    }

    return {
        init:function(option){
            view = new (Backbone.View.extend(extobj))(option);
            API.getTreeInfo();
            return view;
        }
    };
})