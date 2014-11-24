/* 
 * Filters
 * author: Marcin Dziewulski
 * web: http://www.jscraft.net
 * email: info@jscraft.net
 * license: http://www.jscraft.net/licensing.html
 */

(function($){
    $.fn.filters = function(options) {
		var D = {
			filter: {
				name: 'filter',
				element: 'a',
				active: 'active'
			},
			container: {
				name: 'container',
				element: 'li'
			},
			css3: {
				init: true,
				children: 'a',
				property: 'all 1s ease',
				transform: {
					scale: '0'
				}
			},
			move: {
				init: true,
				easing: 'linear',
				duration: 500
			},
			fade: {
				duration: [500, 500],
				opacity: [0, 1]
			},
			fixed: false,
			onclick: function(filter, element){}
		} // default settings
		
		var S = $.extend(true, D, options); 
		
        return this.each(function(){
			var M = $(this),
				F = M.find('.'+S.filter.name)
				C = M.find('.'+S.container.name),
				P = {
					init: function(){
						this._globals.init();
						this._height.update(L);
						if (S.css3.init) this._css3.init();
						this._elements.init();
						this._collection.init();
						this.events.init();
					},
					_globals: {
						init: function(){
							D = {
								w: M.width(),
								h: M.height()
							},
							A = F.find(S.filter.element),
							E = C.find(S.container.element),
							ED = {
								w: E.outerWidth(true),
								h: E.outerHeight(true)
							},
							L = E.length,
							CSS3 = S.css3, MOVE = S.move, 
							FIXED = S.fixed, FADE = S.fade,
							POS = [], COL = [],
							COLUMN = Math.floor(D.w/ED.w);
							if ($.browser.msie && Math.ceil($.browser.version) < 10){
								CSS3.init = false; 							
							}
						}
					},
					_height: {
						update: function(len, type){
							var row = Math.ceil(len/COLUMN),
								css = {
									height: ED.h*row
								}
							C.css(css);
						}
					},
					_css3: {
						init: function(){
							var property = S.css3.property,
								css = {
								'-webkit-transition': property,
								'-moz-transition': property,
								'-o-transition': property,
								'transition': property
							}
							E.children().css(css);
						},
						transform: function(css){
							var arr = [],	
								o = $.extend({}, this.css, css);
							for (i in o){
								var p = o[i];
								arr.push(i+'('+p+')');
							}
							var css3d = arr.join(' ');
							return {
								'-webkit-transform': css3d,
								'-moz-transform': css3d,
								'-o-transform': css3d,
								'transform': css3d
							}
						},
						reset: function(){
							var css = this.transform(this.css);
							return css;
						},
						css: {
							scale: '1',
							rotate: '0',
							translate: '0, 0',
							skew: '0'
						}
					},
					_elements: {
						init: function(){
							this.position();
							this.css();
						},
						css: function(){
							var css = {
								position: 'absolute',
								overflow: 'hidden',
								display: 'block'
							}
							E.css(css);
						},
						position: function(){
							E.each(function(){
								var t = $(this),
									position = t.position(),
									x = position.left,
									y = position.top,
									css = {
										top: y,
										left: x,
										zIndex: 1
									}
								POS.push(css);
								t.css(css);
							});
						}
					},
					_collection: {
						init: function(){
							this.add();
						},
						add: function(){
							A.each(function(i){
								var t = $(this), rel = t.attr('rel');
								if (typeof rel != 'undefined'){
									var element = E.filter('.'+rel);
									COL.push(element);
								}
							});
						},
						position: function(){
							for (i in COL){
								var r = -1, c = -1;
								COL[i].each(function(j){
									var t = $(this);
										c++;
									if (j % COLUMN == 0){
										r++;
										c = 0;
									}
									var	y = ED.h*r,
										x = x = ED.w*c,
										css = {
											top: y,
											left: x
										}
									t.animate(css, MOVE.duration, MOVE.easing);
								});
							}
						}
					},
					_transition: {
						element: function(element, not){
							if (CSS3.init){
								var css = CSS3.transform,
									animate = P._css3.transform(css),
									reset = P._css3.reset();
								not.children(CSS3.children).css(animate);
								element.children(CSS3.children).css(reset);	
							} else {
								not.stop(true, true).fadeTo(FADE.duration[0], FADE.opacity[0]);
								element.stop(true, true).fadeTo(FADE.duration[1], FADE.opacity[1]);
							}
							if (MOVE.init){
								P._collection.position();
							}
						},
						all: function(){
							if (CSS3.init) {
								var	animate = P._css3.reset();
									E.children(CSS3.children).css(animate);
							} else {
								E.fadeTo(FADE.duration[1], FADE.opacity[1]);
							}
						}
					},
					events: {
						init: function(){
							this.click();
						},
						click: function(){
							A.click(function(){
								var t = $(this), rel = t.attr('rel'), active = S.filter.active;
								if (rel == 'all'){
									if (MOVE.init) {
										E.each(function(i){
											var t = $(this), css = POS[i];
											t.animate(css, MOVE.duration, MOVE.easing, P._transition.all);
										});
									} else {
										P._transition.all();
									}
									if (!FIXED && MOVE.init){
										P._height.update(L);
									}
								} else {
									var element = E.filter('.'+rel), not = E.not(element),
										l = element.length, css = { zIndex: 10 }
									element.css(css);
									css.zIndex = 1;
									not.css(css);
									if (!FIXED && MOVE.init){
										P._height.update(l);
									}
									P._transition.element(element, not);
								}
								A.removeClass(active)
								t.addClass(active);
								S.onclick.call(this, t, element || E);
								return false;
							});
						}
					}
				}
			P.init();
        });
    };
}(jQuery));