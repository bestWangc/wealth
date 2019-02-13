function G($){if(typeof $=="string")return document.getElementById($);else return $}function on(A,B,_){var $=B.replace(/^on/,"");if(window.attachEvent)A.attachEvent("on"+$,_);else if(window.addEventListener)A.addEventListener($,_,false)}function stopPropagation($){if(window.attachEvent)$.cancelBubble=true;else if(window.addEventListener)$.stopPropagation()}function implement(_,$){for(var A in _)$[A]=_[A]}var Observable=(function(){function $($){if($&&typeof $=="object"&&$.handleMessage)this.observers.push($)}function _($){var _=this.observers;for(var A=0;A<_.length;A++)_[A].handleMessage($)}return{addObserver:$,notify:_}})();if(typeof Fe=="undefined")Fe={};Fe.Browser=(function(){var E=navigator.userAgent,C=0,F=0,B=0,_=0,$=0,A=0,D=0;if(typeof(window.opera)=="object"&&/Opera(\s|\/)(\d+(\.\d+)?)/.test(E))F=parseFloat(RegExp.$2);else if(/MSIE (\d+(\.\d+)?)/.test(E))C=parseFloat(RegExp.$1);else if(/Firefox(\s|\/)(\d+(\.\d+)?)/.test(E))_=parseFloat(RegExp.$2);else if(navigator.vendor=="Netscape"&&/Netscape(\s|\/)(\d+(\.\d+)?)/.test(E))D=parseFloat(RegExp.$2);else if(E.indexOf("Safari")>-1&&/Version\/(\d+(\.\d+)?)/.test(E))B=parseFloat(RegExp.$1);if(E.indexOf("Gecko")>-1&&E.indexOf("KHTML")==-1&&/rv\:(\d+(\.\d+)?)/.test(E))A=parseFloat(RegExp.$1);return{isIE:C,isFirefox:_,isGecko:A,isNetscape:D,isOpera:F,isSafari:B}})();Fe.format=function(_,B){if(arguments.length>1){var F=Fe.format,H=/([.*+?^=!:${}()|[\]\/\\])/g,C=(F.left_delimiter||"{").replace(H,"\\$1"),A=(F.right_delimiter||"}").replace(H,"\\$1"),E=F._r1||(F._r1=new RegExp("#"+C+"([^"+C+A+"]+)"+A,"g")),G=F._r2||(F._r2=new RegExp("#"+C+"(\\d+)"+A,"g"));if(typeof(B)=="object")return _.replace(E,function(_,A){var $=B[A];if(typeof $=="function")$=$(A);return typeof($)=="undefined"?"":$});else if(typeof(B)!="undefined"){var D=Array.prototype.slice.call(arguments,1),$=D.length;return _.replace(G,function(A,_){_=parseInt(_,10);return(_>=$)?A:D[_]})}}return _};Fe.format.delimiter=function(_,A){var $=Fe.format;$.left_delimiter=_||"{";$.right_delimiter=A||_||"}";$._r1=$._r2=null};Fe.escapeHTML=function($){return $.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;")};if("undefined"==typeof ScrollView)ScrollView={};ScrollView.View=function($){this.list=$.list;this.getItems=$.getItems;this.offset=$.offset;this.itemTpl=$.itemTpl;this.data=$.data;this.windowSize=$.windowSize;this.interval=$.interval||50;this.intervalNoSlowdown=Math.ceil(($.intervalNoSlowdown||50)/10);this.curIndex=$.curIndex||0;this.direction=$.direction||"HOR";this.observers=[];this.scrolling=false;this.imgCache=window[Math.random()]=[];this.imgPreloadIndex=[0,0];this.preloadSize=4*this.windowSize};ScrollView.View.prototype.init=function(){var _,$;if(this.isHor())this.list.style.left=-this.windowSize*this.offset+"px";else this.list.style.top=-this.windowSize*this.offset+"px";if(this.curIndex-this.windowSize>0)_=this.curIndex-this.windowSize;else _=0;if(this.curIndex+2*this.windowSize>this.data.length)$=this.data.length-1;else $=this.curIndex+2*this.windowSize;this.imgPreloadIndex[0]=_;this.imgPreloadIndex[1]=$;this.fill(3);this.notify({type:"UPDATE_PROGRESS",param:{curIndex:this.curIndex,total:this.data.length}})};ScrollView.View.prototype.handleMessage=function($){var _;if($.param.step>0&&$.param.step<=this.windowSize)_=$.param.step;else _=1;switch($.type){case"PREV":this.prev(_,$.param.slowdown);this.imgPreload();this.notify({type:"UPDATE_PROGRESS",param:{curIndex:this.curIndex,total:this.data.length}});break;case"NEXT":this.next(_,$.param.slowdown);this.imgPreload();this.notify({type:"UPDATE_PROGRESS",param:{curIndex:this.curIndex,total:this.data.length}});break}};ScrollView.View.prototype.imgPreload=function(){var G,E,$,B,A,D,_;G=this.imgPreloadIndex[0];E=this.imgPreloadIndex[1];$=this.curIndex;A=this.data;D=this.imgCache;_=this.preloadSize;if(E-$<_&&E<A.length-1&&A[E].img_url){for(var F=E+1,C=0;C<_&&F<A.length;F++,C++){B=new Image();B.src=A[F].img_url;D.push(B)}E=F-1}if($-G<_&&0<G&&A[E].img_url){for(F=G-1,C=0;C<_&&F>=0;F--,C++){B=new Image();B.src=A[F].img_url;D.push(B)}G=F+1}this.imgPreloadIndex[0]=G;this.imgPreloadIndex[1]=E};if("undefined"==typeof ScrollView)ScrollView={};ScrollView.View.prototype.isHor=function(){return"HOR"==this.direction};ScrollView.View.prototype.next=function(F,$){var E,B,A,D,C,_;E=this;C=this.data;A=this.getItems();D=this.windowSize;$=$?true:false;_=$?this.interval:this.intervalNoSlowdown;if(this.curIndex>=C.length-D)return;if(F>C.length-(this.curIndex+D))F=1;if(this.scrolling)return;else this.scrolling=true;B=window.setInterval(function(){var G,K,H,_,C,D,A,I;H=E.offset;C=H*F;G=parseInt(E.list.style.left);K=parseInt(E.list.style.top);A=E.getItems();if(E.isHor())_=-H*E.windowSize-C-G;else _=-H*E.windowSize-C-K;if(_<0){if($){D=parseInt(_/2);if(D==0)D=-1}else{D=-Math.ceil(C/9);if(D<_)D=_}if(E.isHor())E.list.style.left=G+D+"px";else E.list.style.top=K+D+"px"}else{I=A[A.length-1].nextSibling;for(var J=0;J<F;J++){A[J].innerHTML="";if(!I)E.list.appendChild(A[J]);else E.list.insertBefore(A[J],I)}if(E.isHor())E.list.style.left=-H*E.windowSize+"px";else E.list.style.top=-H*E.windowSize+"px";window.clearInterval(B);E.scrolling=false;E.curIndex+=F;E.notify({type:"UPDATE_PROGRESS",param:{curIndex:E.curIndex,total:E.data.length}});E.fill(2,F)}},_)};ScrollView.View.prototype.prev=function(E,$){var D,B,A,C,_;D=this;A=this.getItems();C=this.windowSize;$=$?true:false;_=$?this.interval:this.intervalNoSlowdown;if(this.curIndex==0)return;if(this.curIndex<E)E=1;if(this.scrolling)return;else this.scrolling=true;B=window.setInterval(function(){var G,K,I,_,C,F,A,H;I=D.offset;C=I*E;G=parseInt(D.list.style.left);K=parseInt(D.list.style.top);A=D.getItems();if(D.isHor())_=I*D.windowSize-C+G;else _=I*D.windowSize-C+K;if(_<0){if($){F=-parseInt(_/2);if(F==0)F=1}else{F=Math.ceil(C/9);if(-F<_)F=-_}if(D.isHor())D.list.style.left=G+F+"px";else D.list.style.top=K+F+"px"}else{H=A[0];for(var J=0;J<E;J++){A[A.length-1-J].innerHTML="";D.list.insertBefore(A[A.length-1-J],H)}if(D.isHor())D.list.style.left=-I*D.windowSize+"px";else D.list.style.top=-I*D.windowSize+"px";window.clearInterval(B);D.scrolling=false;D.curIndex-=E;D.notify({type:"UPDATE_PROGRESS",param:{curIndex:D.curIndex,total:D.data.length}});D.fill(1,E)}},_)};ScrollView.View.prototype.fill=function(E,F){var $,B,_,C,A;$=this.curIndex;B=this.data;if(!B)return;_=this.getItems();C=this.windowSize;A=this.itemTpl;if(B.length>C){if($<=0)this.notify({type:"POFFNON"});else if($>=B.length-C)this.notify({type:"PONNOFF"});else this.notify({type:"PONNON"})}else this.notify({type:"POFFNOFF"});if($>=B.length||$<0)return;switch(E){case 1:for(var G=$-1-(C-F),D=0;D<F&&G>=0;G--,D++)_[F-1-D].innerHTML=Fe.format(A,B[G]);break;case 2:for(G=$+C+(C-F),D=F-1;D>=0&&G<B.length;G++,D--)_[3*C-1-D].innerHTML=Fe.format(A,B[G]);break;case 3:for(G=$-1,D=C-1;G>=0&&D>=0;G--,D--)_[D].innerHTML=Fe.format(A,B[G]);for(G=$,D=C;G<B.length&&D<3*C;G++,D++)_[D].innerHTML=Fe.format(A,B[G]);break;default:break}};implement(Observable,ScrollView.View.prototype);ScrollView.Nav=function($){this.navs=$.navs;this.step=$.step;this.style=$.style;this.intervalNoSlowdown=$.intervalNoSlowdown||50;this.timeForContinuousScroll=$.timeForContinuousScroll||500;this.observers=[];this.tIDs=[0,0,0,0]};ScrollView.Nav.prototype.registerEvent=function(){var $=this;on(this.navs[0],"mousedown",function(_){_=_||window.event;$.notify({type:"PREV",param:{step:$.step,event:_,slowdown:true}});$.tIDs[0]=window.setTimeout(function(){$.tIDs[1]=window.setInterval(function(){$.notify({type:"PREV",param:{step:1,event:_,slowdown:false}})},$.intervalNoSlowdown)},$.timeForContinuousScroll)});on(this.navs[0],"mouseup",function(_){window.clearTimeout($.tIDs[0]);window.clearInterval($.tIDs[1])});on(this.navs[0],"mouseout",function(_){window.clearTimeout($.tIDs[0]);window.clearInterval($.tIDs[1])});on(this.navs[1],"mousedown",function(_){_=_||window.event;$.notify({type:"NEXT",param:{step:$.step,event:_,slowdown:true}});$.tIDs[2]=window.setTimeout(function(){$.tIDs[3]=window.setInterval(function(){$.notify({type:"NEXT",param:{step:1,event:_,slowdown:false}})},$.intervalNoSlowdown)},$.timeForContinuousScroll)});on(this.navs[1],"mouseup",function(_){window.clearTimeout($.tIDs[2]);window.clearInterval($.tIDs[3])});on(this.navs[1],"mouseout",function(_){window.clearTimeout($.tIDs[2]);window.clearInterval($.tIDs[3])})};ScrollView.Nav.prototype.init=function(){this.registerEvent()};ScrollView.Nav.prototype.handleMessage=function($){var A,_;A=this.navs;_=this.style;switch($.type){case"PONNOFF":A[0].className=_.pon;A[1].className=_.noff;break;case"PONNON":A[0].className=_.pon;A[1].className=_.non;break;case"POFFNOFF":A[0].className=_.poff;A[1].className=_.noff;break;case"POFFNON":A[0].className=_.poff;A[1].className=_.non;break}};implement(Observable,ScrollView.Nav.prototype);ScrollView.Progress=function($){this.callback=$.progressCallback||function(_,$){}};ScrollView.Progress.prototype.handleMessage=function($){switch($.type){case"UPDATE_PROGRESS":this.callback($.param.curIndex,$.param.total);break}};implement(Observable,ScrollView.Progress.prototype);ScrollView.Model=function($){this.view=new ScrollView.View($);this.nav=new ScrollView.Nav($);this.progress=new ScrollView.Progress($);this.view.addObserver(this.nav);this.nav.addObserver(this.view);this.view.addObserver(this.progress);this.nav.init();this.view.init()}