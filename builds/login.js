define(["require","backbone","api","router","common"],function(e,t,n,r,i){var s,o=t.View.extend({el:"#login",options:{},events:{"click #submitBtn":"onSbumit"},initialize:function(e){var n=this;$("input[key=password]").val(""),$("input[key=username]").val(localStorage.getItem("username")||""),n.listenTo(t.Events,"login",function(e){console.log(e),localStorage.setItem("userinfo",JSON.stringify(e)),localStorage.setItem("collecting","true"),localStorage.setItem("username",e.username),r.to("manage/station")}),n.listenTo(t.Events,"login:fail",function(e){localStorage.clear(),alert("登录失败，请重试")})},onSbumit:function(){var e=i.getFormValue(this.el);n.login(e)}});return{init:function(e){return s=new o,this}}});