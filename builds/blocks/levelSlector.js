define(["require","api","backbone","zTreeExcheck"],function(e,t,n){var r,i={check:{enable:!0,chkStyle:"radio",radioType:"all"},data:{key:{name:"title"},simpleData:{enable:!0,pIdKey:"pid"}}},s,o={name:"nav",data:null,tree:null,navPlugin:null,initialize:function(e){var t=this;t.option={},$.extend(t.option,e||{}),t.listenTo(n.Events,"tree:get",function(e){t.data=e.list,t.filterData(),t.render()})},filterData:function(){var e=this;e.data&&$.each(e.data,function(t,n){e.option.value&&e.option.value==n.id&&(n.checked=!0)})},getCheckedData:function(){var e=this.tree.getCheckedNodes(),t={};return $.each(e,function(e,n){t[n.id]={id:n.id,pId:n.pId,name:n.name,level:n.level}}),t},getValue:function(){return this.tree.getCheckedNodes()[0]&&this.tree.getCheckedNodes()[0].id},setValue:function(e){if(e){var t=this.tree.getNodesByParam("id",e);this.tree.checkNode(t,!0,!0)}},destroy:function(){this.stopListening()},render:function(){var e=this,t;return $.fn.zTree.init($("#treesWrap"),i,e.data),e.tree=$.fn.zTree.getZTreeObj("treesWrap"),e.tree.expandAll(!0),t=$("#treesWrap [treenode_switch]:not(.center_docu,.bottom_docu)"),t.length>1&&t.siblings("[treenode_check]").remove(),this}};return{init:function(e){return s=new(n.View.extend(o))(e),t.getTreeInfo(),s}}});