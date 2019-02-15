//====================================================================================================
// [插件名称] jQuery flashSlider
//----------------------------------------------------------------------------------------------------
// [描    述] jQuery flashSlider图片展示插件，它是基于jQuery类库，实现了不使用flash也同样可以在
//             页面上展示图片的效果，支持数字导航，纵向滑动和横向滑动，自动滚动和连续滑动
//             可设置滑动速度、是否自动、停顿间隔,自定义容器高度和宽度,支持Jquery的easing效果
//            （更多效果需要easing插件的支持，默认是swing）
//[使用方法]  http://www.cnblogs.com/dushouke/archive/2009/12/30/1636390.html
//[源码下载]  http://files.cnblogs.com/dushouke/jquery.flashSlider1.0.rar
//----------------------------------------------------------------------------------------------------
// [作者网名] MicroCoder
// [邮    箱] dushouke@qq.com
// [作者博客] http://dushouke.cnblogs.com
// [更新日期] 2009-12-30
// [版 本 号] ver1.0
//====================================================================================================
(function($) {
    $.fn.flashSlider = function(options) {
        var options = $.extend({}, $.fn.flashSlider.defaults, options);
        this.each(function() {
            var obj = $(this);
            var curr = 1; //当前索引
            var $img = obj.find("img");
            var s = $img.length;
            var w = $img.eq(0).width();
            var h = $img.eq(0).height();
            var $flashelement = $("ul", obj);
            options.height == 0 ? obj.height(h) : obj.height(options.height);
            options.width == 0 ? obj.width(w) : obj.width(options.width);
            obj.css("overflow", "hidden");
            obj.css("position", "relative");
            $flashelement.css('width', s * w);
            if (!options.vertical) {
                $("li", obj).css('float', 'left')
            }
            else {
                $img.css('display', 'block')
            };
            if (options.controlsShow) {
                var navbtnhtml = '<div id="flashnvanum">';
                for (var i = 0; i < s; i++) {
                    navbtnhtml += '<span>' + (i + 1) + '</span>';
                }
                navbtnhtml += '</div>';
                obj.append(navbtnhtml);
                obj.find("#flashnvanum span").hover(function() {
                    var num = $(this).html();
                    flash(num, true);
                }, function() {
                    timeout = setTimeout(function() {
                        flash((curr / 1 + 1), false);
                    }, options.pause / 4);
                });
            };
            function setcurrnum(index) {
                obj.find("#flashnvanum span").eq(index).addClass('on').siblings().removeClass('on');
            }
            function flash(index, clicked) {
                $flashelement.stop();
                var next = index == s ? 1 : index + 1;
                curr = index - 1;
                setcurrnum((index - 1));
                if (!options.vertical) {
                    p = ((index - 1) * w * -1);
                    $flashelement.animate(
						{ marginLeft: p },
						options.speed, options.easing
					);
                } else {
                    p = ((index - 1) * h * -1);
                    $flashelement.animate(
						{ marginTop: p },
						options.speed, options.easing
					);
                };
                if (clicked) {
                    clearTimeout(timeout);
                };
                if (options.auto && !clicked) {
                    timeout = setTimeout(function() {
                        flash(next, false);
                    }, options.speed + options.pause);
                };
            }
            var timeout;
            //初始化
            setcurrnum(0);
            if (options.auto) {
                timeout = setTimeout(function() {
                    flash(2, false);
                }, options.pause);
            };
        });
    };
    //默认值
    $.fn.flashSlider.defaults = {
        controlsShow: true, //是否显示数字导航
        vertical: false, //纵向还是横向滑动
        speed: 500, //滑动速度
        auto: true, //是否自定滑动
        pause: 4000, //两次滑动暂停时间
        easing: "swing", //easing效果，默认swing，更多效果需要easing插件支持
        height: 170, //容器高度，不设置自动获取图片高度
        width: 200//容器宽度，不设置自动获取图片宽度
    }
})(jQuery);