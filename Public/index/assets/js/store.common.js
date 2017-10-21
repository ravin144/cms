
//$(function() {
//Store.widget.simpleTab('.indextop > div.nav > a', '.indextop > .CrightBox', 'hover', 'click');   
//});


var Store = Store || {};

Store.widget = (function($){
	return {
	
        // 简单标签切换
        simpleTab : function(){
            var args = arguments;
            var options = { tabs : null, panels : null, css : "actived", trigger : "click", callback : null };
            
            if(args.length == 1){
                if(typeof(args[0]) == "object") $.extend(options, args[0]);
            }else {
                if(args.length > 1){
                    options.tabs = args[0];
                    options.panels = args[1];
                }
                if(args.length > 2)
                    options.css = args[2];
                if(args.length > 3)
                    options.trigger = args[3];
            }
            
            var $tabs = $(options.tabs);
            var $panels = $(options.panels);
            
            $tabs.each(function (index, item) {
                $(item).bind(options.trigger ,function () {
                    $tabs.removeClass(options.css);
                    $(this).addClass(options.css);
                    $panels.hide().eq(index).show();
                    if(typeof(options.callback) == "function"){
                        options.callback.call(item, item, $panels.eq(index)[0]);
                    }
                });
            });
        }, // -- end simpleTab
        
        
        // 鼠标经过设置样式
        simpleHoverStyle : function(items, css){
            css = css || "selected";
            var $items = $(items);
            $items.hover(function () {
                $items.removeClass(css);
                $(this).addClass(css);
            }, function () {
                //$(this).removeClass(css);
            }).eq(0).addClass(css);
        } // end simpleHoverStyle
        
	};
})(jQuery);