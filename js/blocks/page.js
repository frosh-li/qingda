define(['require','api','stationsinfoDialog','context','ui','table'],function(require,API,stationInfoDialog,context,ui){
    var View = {
        events:{
            "click .next-page":_onNext,
            "click .prev-page":_onPrev
        },
        initialize:function(config){
            var _this = this;

            if(config.onNext){
                _this.onNext = config.onNext;
            }
            if(config.onPrev){
                _this.onPrev = config.onPrev;
            }
            if(config.el){
                _this.el = config.el;
            }
        },
        set:function(page,total){
            var _this = this;
            _this.el.show();
            if(typeof page != 'undefined' && !(typeof page== 'object' && !page)){
                _this.page = page;
                $(".current-num",_this.el).html(page);
            }
            if(typeof page != 'undefined' && !(typeof page== 'object' && !page)){
                _this.total = total;
                $(".total-num",_this.el).html(total);
            }
        },
        _onNext:function(){
            if(typeof this.page != 'undefined'){
                if(this.page == this.total){
                    alert('已是最后一页');
                }else{
                    this.page +=1;
                    this.onchange();
                }
            }
        },
        _onPrev:function(){
            if(typeof this.page != 'undefined'){
                if( this.page>1){
                    this.page -=1;
                }else{
                    alert('已是第一页');
                    this.onchange();
                }
            }
        },
        destory:function(){
            this.el.hide();
        },
        onchange:function(){}
    }
    return View;
})