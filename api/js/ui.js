var index = (function () {
    var _public = {};

    _public.topNavBind = function () {
        $(".pager_nav_h li").hover(function () {
            $(this).find("a").addClass("selected");
            $(this).find("ul").show();
        }, function () {
            var adataid = $(this).find("a").attr("data-id");
            if (adataid != "selected") {
                $(this).find("a").removeClass("selected");
            }
            $(this).find("ul").hide();
        });
    }

    _public.topsearch = function () {
        $(".pager_search_b input").bind("click", function () {
            var thisValue = $.trim($(this).val());
            if (thisValue == "" || thisValue == "站内搜索") {
                $(this).val("");
            }
        });

        $(".pager_search_b input").bind("blur", function () {
            var thisValue = $.trim($(this).val());
            if (thisValue == "" || thisValue == "站内搜索") {
                $(this).val("站内搜索");
            }
        });
    };

    return _public;
})();


$(document).ready(function () {
    index.topNavBind();
    index.topsearch();
});

var otherpage = (function () {

    var _public = {};

    //核算距离
    _public.pageloads = function () {
        var leftH = 0,
        rightH = 0,
        bodyH = 0;

        leftH = $(".list_left_b_noalpha").height();
        rightH = $(".list_right_b_noalpha").height();
        $(".list_left_b").height(leftH + 20);
        $(".list_right_b").height(rightH + 20);

        //判断哪个多
        bodyH = leftH > rightH ? leftH : rightH;
        $(".list_b").height(bodyH + 50);

    }

    return _public;

})();
function AddFavorite(sURL, sTitle) {
                try {
                    window.external.addFavorite(sURL, sTitle);
                }
                catch (e) {
                    try {
                        window.sidebar.addPanel(sTitle, sURL, "");
                    }
                    catch (e) {
                        alert("加入收藏失败，请使用Ctrl+D进行添加");
                    }
                }
            }