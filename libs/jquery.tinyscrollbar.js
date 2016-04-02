; (function(factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory(require("jquery"));
    } else {
        factory(jQuery);
    }
} (function($) {
    "use strict";

    var pluginName = "tinyscrollbar",
        defaults = {
            axis: 'y',
            wheel: true,
            wheelSpeed: 40,
            wheelLock: true,
            touchLock: true,
            trackSize: false,
            thumbSize: false,
            thumbSizeMin: 20
        };

    function Plugin($container, options) {
        /**
         * The options of the carousel extend with the defaults.
         *
         * @property options
         * @type Object
         */
        this.options = $.extend({},
            defaults, options);

        /**
         * @property _defaults
         * @type Object
         * @private
         * @default defaults
         */
        this._defaults = defaults;

        /**
         * @property _name
         * @type String
         * @private
         * @final
         * @default 'tinyscrollbar'
         */
        this._name = pluginName;

        var self = this,
            $viewport = options.$viewPort || $container.find(".viewport"),
            $overview = options.$overView || $container.find(".overview"),

            hasTouchEvents = ("ontouchstart" in document.documentElement),
            wheelEvent = "onwheel" in document.createElement("div") ? "wheel": // Modern browsers support "wheel"
                document.onmousewheel !== undefined ? "mousewheel": // Webkit and IE support at least "mousewheel"
                    "DOMMouseScroll" // let's assume that remaining browsers are older Firefox
            ,
            isHorizontal = this.options.axis === 'x'

            ,
            mousePosition = 0;

        this.bars = [];
        var barOption = {
            contentPosition:0,
            viewportSize:0,
            contentSize:0,
            contentRatio:0,
            trackSize:0,
            trackRatio:0,
            thumbSize:0,
            thumbPosition:0,
            hasContentToSroll:false
        }
        if(options.$xBarEl){
            this.bars.push($.extend(true,{},barOption,{
                type:'x',
                $scrollbar : options.$xBarEl,
                $track : options.$xBarEl.find(".track"),
                $thumb : options.$xBarEl.find(".thumb"),
                sizeLabel : "width",
                contentPosition:0,
                posiLabel : "left"
            }))
        }
        if(options.$yBarEl){
            this.bars.push($.extend(true,{},barOption,{
                type:'y',
                $scrollbar : options.$yBarEl,
                $track : options.$yBarEl.find(".track"),
                $thumb : options.$yBarEl.find(".thumb"),
                sizeLabel : "height",
                posiLabel : "top"
            }))
        }

        /**
         * The position of the content relative to the viewport.
         *
         * @property contentPosition
         * @type Number
         */
        //this.contentPosition = 0;

        /**
         * The height or width of the viewport.
         *
         * @property viewportSize
         * @type Number
         */
        //this.viewportSize = 0;

        /**
         * The height or width of the content.
         *
         * @property contentSize
         * @type Number
         */
        //this.contentSize = 0;

        /**
         * The ratio of the content size relative to the viewport size.
         *
         * @property contentRatio
         * @type Number
         */
        //this.contentRatio = 0;

        /**
         * The height or width of the content.
         *
         * @property trackSize
         * @type Number
         */
        //this.trackSize = 0;

        /**
         * The size of the track relative to the size of the content.
         *
         * @property trackRatio
         * @type Number
         */
        //this.trackRatio = 0;

        /**
         * The height or width of the thumb.
         *
         * @property thumbSize
         * @type Number
         */
        //this.thumbSize = 0;

        /**
         * The position of the thumb relative to the track.
         *
         * @property thumbPosition
         * @type Number
         */
        //this.thumbPosition = 0;

        /**
         * Will be true if there is content to scroll.
         *
         * @property hasContentToSroll
         * @type Boolean
         */
        //this.hasContentToSroll = false;

        /**
         * @method _initialize
         * @private
         */
        function _initialize() {

            $.each(self.bars,function(i,bar){
                self.update(false,bar);
                _setEvents(bar);
            })

            return self;
        }

        /**
         * You can use the update method to adjust the scrollbar to new content or to move the scrollbar to a certain point.
         *
         * @method update
         * @chainable
         * @param {Number|String} [scrollTo] Number in pixels or the values "relative" or "bottom". If you dont specify a parameter it will default to top
         */
        this.update = function(scrollTo,bar) {
            if(bar){
                self.updateSingle(scrollTo,bar);
            }else{
                $.each(self.bars,function(i,bar){
                    self.updateSingle(scrollTo,bar);
                })
            }

            return self;
        };
        this.updateSingle = function(scrollTo,bar) {
            var sizeLabelCap = bar.sizeLabel.charAt(0).toUpperCase() + bar.sizeLabel.slice(1).toLowerCase();
            bar.viewportSize = $viewport[0]['offset' + sizeLabelCap];
            bar.contentSize = $overview[0]['scroll' + sizeLabelCap];
            bar.contentRatio = bar.viewportSize / bar.contentSize;
            bar.trackSize = this.options.trackSize || bar.viewportSize;
            bar.thumbSize = Math.min(bar.trackSize, Math.max(this.options.thumbSizeMin, (this.options.thumbSize || (bar.trackSize * bar.contentRatio))));
            bar.trackRatio = (bar.contentSize - bar.viewportSize) / (bar.trackSize - bar.thumbSize);
            bar.hasContentToSroll = bar.contentRatio < 1;

            bar.$scrollbar.toggleClass("disable", !bar.hasContentToSroll);

            switch (scrollTo) {
                case "bottom":
                    bar.contentPosition = Math.max(bar.contentSize - bar.viewportSize, 0);
                    break;

                case "relative":
                    bar.contentPosition = Math.min(Math.max(bar.contentSize - bar.viewportSize, 0), Math.max(0, bar.contentPosition));
                    break;

                default:
                    bar.contentPosition = parseInt(scrollTo, 10) || 0;
            }

            bar.thumbPosition = bar.contentPosition / bar.trackRatio;

            _setCss(bar);


            return self;
        };

        /**
         * @method _setCss
         * @private
         */
        function _setCss(bar) {
            bar.$thumb.css(bar.posiLabel, bar.thumbPosition);
            $overview.css(bar.posiLabel, -bar.contentPosition);
            bar.$scrollbar.css(bar.sizeLabel, bar.trackSize);
            bar.$track.css(bar.sizeLabel, bar.trackSize);
            bar.$thumb.css(bar.sizeLabel, bar.thumbSize);
        }

        /**
         * @method _setEvents
         * @private
         */
        function _setEvents(bar) {
            if (hasTouchEvents) {
                $viewport[0].ontouchstart = function(event) {
                    if (1 === event.touches.length) {
                        event.stopPropagation();

                        _start(event.touches[0]);
                    }
                };
            } else {
                bar.$thumb.bind("mousedown",
                    function(event) {
                        event.stopPropagation();
                        _start(event);
                    });
                bar.$track.bind("mousedown",
                    function(event) {
                        _start(event, true);
                    });
            }


            $(window).resize(function() {
                self.update("relative");
            });

            if (self.options.wheel && window.addEventListener) {
                $container[0].addEventListener(wheelEvent, _wheel, false);
            } else if (self.options.wheel) {
                $container[0].onmousewheel = _wheel;
            }
        }

        /**
         * @method _isAtBegin
         * @private
         */
        function _isAtBegin(bar) {
            return bar.contentPosition > 0;
        }

        /**
         * @method _isAtEnd
         * @private
         */
        function _isAtEnd(bar) {
            return bar.contentPosition <= (bar.contentSize - bar.viewportSize) - 5;
        }

        /**
         * @method _start
         * @private
         */
        function _start(event, gotoMouse, bar) {
            if(typeof bar == 'undefined'){
                $.each(self.bars,function(i,_bar){
                    _startSingle(event,gotoMouse,_bar);
                })
            }else{
                _startSingle(event,gotoMouse,bar);
            }

        }
        function _startSingle(event, gotoMouse, bar) {
            if(typeof bar == 'undefined'){
                $.each(self.bars,function(i,_bar){
                    if(_bar.$thumb == $(event.target)){
                        bar = _bar;
                    }
                })
            }
            if (bar && bar.hasContentToSroll) {
                $("body").addClass("noSelect");

                mousePosition = gotoMouse ? bar.$thumb.offset()[bar.posiLabel] : (bar.type=="x" ? event.pageX: event.pageY);

                if (bar.hasTouchEvents) {
                    document.ontouchmove = function(event) {
                        if (self.options.touchLock || _isAtBegin(bar) && _isAtEnd(bar)) {
                            event.preventDefault();
                        }
                        _drag(event.touches[0],bar);
                    };
                    document.ontouchend = _end;
                } else {
                    $(document).bind("mousemove", _drag);
                    $(document).bind("mouseup", function(){_end(bar)});
                    bar.$thumb.bind("mouseup",  function(){_end(bar)});
                    bar.$track.bind("mouseup",  function(){_end(bar)});
                }

                _drag(event,bar);
            }

        }

        /**
         * @method _wheel
         * @private
         */
        function _wheel(event) {
            if (self.hasContentToSroll) {
                // Trying to make sense of all the different wheel event implementations..
                //
                var evntObj = event || window.event,
                    wheelDelta = -(evntObj.deltaY || evntObj.detail || ( - 1 / 3 * evntObj.wheelDelta)) / 40,
                    multiply = (evntObj.deltaMode === 1) ? self.options.wheelSpeed: 1;

                self.contentPosition -= wheelDelta * multiply * self.options.wheelSpeed;
                self.contentPosition = Math.min((self.contentSize - self.viewportSize), Math.max(0, self.contentPosition));
                self.thumbPosition = self.contentPosition / self.trackRatio;

                /**
                 * The move event will trigger when the carousel slides to a new slide.
                 *
                 * @event move
                 */
                $container.trigger("move");

                $thumb.css(posiLabel, self.thumbPosition);
                //$overview.css(posiLabel, -self.contentPosition);
                $viewport['scroll'+posiLabel.replace(/^([a-zA-z])/,function(m,a){return a.toUpperCase()})](self.contentPosition);

                if (self.options.wheelLock || _isAtBegin() && _isAtEnd()) {
                    evntObj = $.event.fix(evntObj);
                    evntObj.preventDefault();
                }
            }
            event.stopPropagation();
        }

        /**
         * @method _drag
         * @private
         */
        function _drag(event,bar) {
            if(typeof bar == 'undefined'){
                $.each(self.bars,function(i,_bar){
                    _dragSingle(event,_bar);
                })
            }else{
                _dragSingle(event,bar);
            }
        }
        function _dragSingle(bar){
            if (bar.hasContentToSroll) {
                var mousePositionNew = bar.type=="x" ? event.pageX: event.pageY,
                    thumbPositionDelta = bar.hasTouchEvents ? (mousePosition - mousePositionNew) : (mousePositionNew - mousePosition),
                    thumbPositionNew = Math.min((bar.trackSize - bar.thumbSize), Math.max(0, bar.thumbPosition + thumbPositionDelta));

                bar.contentPosition = thumbPositionNew * bar.trackRatio;

                $container.trigger("move");

                bar.$thumb.css(bar.posiLabel, thumbPositionNew);

                $viewport['scroll'+bar.posiLabel.replace(/^([a-zA-z])/,function(m,a){return a.toUpperCase()})](bar.contentPosition);
                //$overview.css(posiLabel, -self.contentPosition);
            }
        }
        /**
         * @method _end
         * @private
         */
        function _end(bar) {
            bar.thumbPosition = parseInt(bar.$thumb.css(bar.posiLabel), 10) || 0;

            $("body").removeClass("noSelect");
            $(document).unbind("mousemove", function(evt){_drag(evt,bar)});
            $(document).unbind("mouseup", function(){_end(bar)});
            bar.$thumb.unbind("mouseup", function(){_end(bar)});
            bar.$track.unbind("mouseup", function(){_end(bar)});
            document.ontouchmove = document.ontouchend = null;
        }

        return _initialize();
    }

    /**
     * @class tinyscrollbar
     * @constructor
     * @param {Object} options
     @param {String} [options.axis='y'] Vertical or horizontal scroller? ( x || y ).
     @param {Boolean} [options.wheel=true] Enable or disable the mousewheel.
     @param {Boolean} [options.wheelSpeed=40] How many pixels must the mouswheel scroll at a time.
     @param {Boolean} [options.wheelLock=true] Lock default window wheel scrolling when there is no more content to scroll.
     @param {Number} [options.touchLock=true] Lock default window touch scrolling when there is no more content to scroll.
     @param {Boolean|Number} [options.trackSize=false] Set the size of the scrollbar to auto(false) or a fixed number.
     @param {Boolean|Number} [options.thumbSize=false] Set the size of the thumb to auto(false) or a fixed number
     @param {Boolean} [options.thumbSizeMin=20] Minimum thumb size.
     */
    $.fn[pluginName] = function(options) {
        return this.each(function() {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin($(this), options));
            }
        });
    };
}));