define(function(require){
    var common = {
        inArray:function(item,ar){
            var inAr = false;
            if(ar && ar.length){
                $.each(ar,function(i,_item){
                    if(_item === item){
                        inAr = true;
                    }
                })
            }
            return inAr;
        },
        getFormValue:function($wrap,_isContainHiddenTypeInput,_isContainHiddenElement,_customKey){
            var $wrap = $wrap && $wrap.length?$wrap:$(document.body),
                keyName = _customKey || "key",
                _ret = {},
                _arg = arguments;

            if($("[sub-blocks]",$wrap).length){
                var $subBlock = $("[sub-blocks]",$wrap),
                    subKey = $subBlock.attr('sub-blocks');
                if($subBlock.length >1){
                    _ret[subKey] = [];
                    $.each($subBlock,function(i,block){
                        _ret[subKey].push(_arg.callee($(block),_isContainHiddenTypeInput,_isContainHiddenElement,subKey+"_key"));
                    })
                }else{
                    _ret[subKey] = _arg.callee($subBlock,_isContainHiddenTypeInput,_isContainHiddenElement,subKey+"_key");
                }
            }

            if(typeof _isContainHiddenElement != "undefined" && _isContainHiddenElement){
                var $raido = $("input[type=radio]["+keyName+"]:checked",$wrap),
                    $checkbox = $("input[type=checkbox]["+keyName+"]:checked",$wrap),
                    $hiddenTypeInput = $("input[type=hidden]["+keyName+"]",$wrap),
                    $all = $("[type!=radio][type!=checkbox]["+keyName+"]",$wrap).add($raido);
            }else{
                var $raido = $("input[type=radio]["+keyName+"]:visible:checked",$wrap),
                    $checkbox = $("input[type=checkbox]["+keyName+"]:visible",$wrap),
                    $hiddenTypeInput = $("input[type=hidden]["+keyName+"]",$wrap),
                    $all = $("[type!=radio][type!=checkbox]["+keyName+"]:visible",$wrap).add($raido);
            }

            //标签内容做值的元素
            var $innerHtmls = $("[content_"+keyName+"]",$wrap);

            if(typeof _isContainHiddenTypeInput != "undefined" && _isContainHiddenTypeInput){$all = $all.add($hiddenTypeInput)}

            if($all.length){
                $all.each(function(i,el){
                    var $el = $(el),
                        _keyValue = $el.attr(keyName),
                        _key = _keyValue.split("|")[0],
                        _filter = _keyValue.split("|")[1];//过滤规则
                    if(typeof _ret[_key] == "undefined"){
                        var _value = $el.val();
                        if(_filter){
                            _value = Common.filter(_value,{method:_filter.join(","),type:"get"});
                        }
                        _ret[$el.attr(keyName)] = _value;
                    }
                })
            }

            if($checkbox && $checkbox.length){
                $checkbox.each(function(j,_el){
                    var $el = $(_el),
                        _key = $el.attr(keyName),
                        _specifyValue = $el.attr('specify-value');

                    if(_specifyValue){
                        if($el.is(":checked")){
                            _ret[_key] = _specifyValue.split("|")[0];
                        }else{
                            _ret[_key] = _specifyValue.split("|")[1];
                        }
                    }else{
                        if($el.is(":checked")){
                            if(typeof _ret[_key] == "undefined"){
                                _ret[$el.attr(keyName)] = $el.val();
                            }else{
                                _ret[$el.attr(keyName)] = _ret[$el.attr(keyName)]+','+$el.val();
                            }
                        }
                    }

                })
            }
            // 加入select的值
            if($("select", $wrap) && $("select", $wrap).length){
                $("select", $wrap).each(function(i, el){
                    var $el = $(el);
                    _ret[$el.attr('key')] = $el.val();
                });
            }
            if($innerHtmls && $innerHtmls.length){
                $innerHtmls.each(function(j,_el){
                    var $el = $(_el),
                        _key = $el.attr("content_"+keyName);
                    _ret[_key] = $el.html();
                })
            }
            return _ret;
        },
        setFormValue:function($wrap,data,_customKey) {
            var keyName = _customKey || "key";
            $.each(data, function (k, v) {
                var _key = k.split("|")[0],
                    _filter = k.split("|")[1],//过滤规则
                    $els = $("[" + keyName + "=" + _key + "]", $wrap),
                    $innerHtmls = $("[content_" + keyName + "=" + _key + "]", $wrap),
                    _elLen = $els.length;

                if ($innerHtmls.length) {
                    $innerHtmls.html(_filter ? Common.filter(v, {method: _filter, type: "set"}) : v);
                }
                if (_elLen == 1 && /^checkbox|radio$/.test($els.attr("type"))) {
                    var specifyValue = $els.attr("specify-value");
                    if (v == $els.val()) {
                        $els.filter(":visible").attr("checked", "checked");
                        if ($els.attr("switch-evt")) {
                            $els.attr("checked", "checked").trigger('click').attr("checked", "checked")
                        }
                    }
                    if (specifyValue) {
                        if (v == specifyValue.split('|')[0]) {
                            $els.attr("checked", "checked").trigger('click').attr("checked", "checked");
                        } else {
                            $els.removeAttr("checked")
                        }
                    }
                } else if (_elLen == 1) {
                    if (_filter) {
                        v = Common.filter(v, {method: _filter, type: "set"});
                    }
                    try {
                        $els.val(v).trigger("change", true);
                    } catch (e) {
                    }
                } else if (_elLen > 1) {//radio和checkbox
                    v = v.split(',');
                    $els.removeAttr("checked");
                    $.each(v, function (i, r) {
                        $els.filter("[value=" + r + "]:visible").attr("checked", "checked");
                    })
                }
                // 如果当前元素是select，做特殊处理
                var tagName = $els[0] && $els[0].tagName;
                if(tagName && tagName.toUpperCase() == "SELECT"){
                    console.log(v);
                    var options = $els.find("option");
                    options.each(function(i, el){
                        console.log(el, $(el).attr("value"));
                        if($(el).attr("value") == v){
                            $(el).attr("selected","selected");
                        }else{
                            $(el).removeAttr("selected");
                        }
                    })
                    $els.trigger("change", true);
                    fix_select($els);
                }
                // console.log($els[0]&& $els[0].tagName);
            })
            function fix_select(selector) {
                var i=$(selector).parent().find('div,ul').remove().css('zIndex');
                $(selector).unwrap().removeClass('jqTransformHidden').jqTransSelect();
                $(selector).parent().css('zIndex', i);
            }
            fix_select('select#my_updated_select_box');
        },
        //过滤器
        filter:function(data,option){
            var _regs,_o,_m,_methods;
            if(option && option.method){
                _methods = option.method.split(";");
                $.each(_methods,function(i,method){
                    _regs = /([\s\S]+)\(([^\)]+)\)/.exec(method);
                    if(!_regs){
                        _m = method;
                        _o = null;
                    }else{
                        _m = _regs[1];
                        _o = _regs[2].split(" ");
                    }
                    switch(_m){
                        case 'encode':
                            if(option.type && "set" == option.type){
                                data = decodeURIComponent(data);
                            }else{
                                data = encodeURIComponent(data);
                            }
                            break;
                        case 'escape':
                            data = data.replace(/"/g,'&quot;');
                            data = data.replace(/</g,'&lt;');
                            data = data.replace(/>/g,"&gt;");
                            break;
                        case 'addslashes':
                            data = data.replace(/"/g,'\\"');
                            data = data.replace(/'/g,"\\'");
                            break;
                        case 'artTypeName':
                            data = g_GetConfig.getArtTypeName(data);
                            break;
                        case 'byteToMB':
                            data = data?Math.ceil((data*10)/(1024*1024))/10:0;
                            break;
                        case 'cut':
                            if(_o && _o[0]){
                                _o = parseInt(_o[0]);
                                data = data.substr(0,_o);
                            }
                            break;
                    }
                })
            }
            return data;
        },
        loadTips:{
            time:2,
            dialogEl:null,
            show:function(message){
                var tpl = '<div id="loadding">'+
                                '<div><img src="images/loading.gif" alt=""/></div>'+
                                '<div class="message"><img src="" alt=""/><%= message %></div>'+
                            '</div>',
                    html = _.template(tpl)({message:message||''});
                this.close();
                if($("#loadding").length){
                    $("#loadding").parents('.ui-dialog').remove();    
                }
                this.dialogEl = $(html).dialog({
                    modal:true,
                    dialogClass:'tips',
                    width:300,
                    height:70
                })
            },
            success:function(message){

            },
            error:function(message){

            },
            close:function(){
                if(this.dialogEl){
                    try{this.dialogEl.dialog('close');}catch(e){}
                }
                return this;
            }
        },
        tips:function(txt,time){

        },
        cookie:{
            setCookie:function(name,value,day){
                var Days = day||1500;
                var exp = new Date();
                exp.setTime(exp.getTime() + Days*24*60*60*1000);
                document.cookie = name + "="+ encodeURIComponent (value) + ";expires=" + exp.toGMTString();
            },
            getCookie:function(name){
                var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
                if(arr=document.cookie.match(reg))
                    return decodeURIComponent(arr[2]);
                else
                    return null;
            }
        },
        filterArray:function(ar,filterAr,keyName){
            var retAr = [];
            $.each(ar,function(i,d){
                if(common.inArray(d[keyName],filterAr)){
                    retAr.push(d);
                }
            })
            return retAr;
        }
    }

    return common;
})
