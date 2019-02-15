/*!
 * AlertBox
 * Copyright (c) 2010 cloudgamer
 * Blog: http://cloudgamer.cnblogs.com/
 * Date: 2010-10-10
 */

//��ʾ�����
var AlertBox = function(box, options){
	//��ʼ������
	this._initialize( box, options );
	this._initBox();
};
AlertBox.prototype = {
  _initialize: function(box, options) {
	
	this.box = $$(box);//��ʾ��
	
	this._css = null;//������ʽ
	
	this._setOptions(options);
	
	this.fixed = !!this.options.fixed;
	this.zIndex = this.options.zIndex;
	
	this.onShow = this.options.onShow;
	this.onClose = this.options.onClose;
	
	$$CE.fireEvent( this, "init" );
  },
  //����Ĭ������
  _setOptions: function(options) {
    this.options = {//Ĭ��ֵ
		fixed:		false,//�Ƿ�̶���λ
		zIndex:		1000,//���ֵ
		onShow:		$$.emptyFunction,//��ʾʱִ��
		onClose:	$$.emptyFunction//�ر�ʱִ��
	};
    return $$.extend(this.options, options || {});
  },
  //��ʼ����ʾ�����
  _initBox: function() {
	var style = this.box.style;
	this._css = { "display": style.display, "visibility": style.visibility, "position": style.position, "zIndex": style.zIndex };//������ʽ
	style.display = "none";
	style.visibility = "visible";
	document.body.insertBefore(this.box, document.body.childNodes[0]);
	$$CE.fireEvent( this, "initBox" );
  },
  //��ʾ
  show: function(isResize) {
	//��λ��ʾ
	var style = this.box.style;
	style.position = this.fixed ? "fixed" : "absolute";
	style.zIndex = this.zIndex;
	$$CE.fireEvent( this, "show", isResize );
	style.display = "block";
	this.onShow();
  },
  //�ر�
  close: function() {
	this.box.style.display = "none";
	$$CE.fireEvent( this, "close" );
	this.onClose();
  },
  //��ٳ���
  dispose: function() {
	$$CE.fireEvent( this, "dispose" );
	$$D.setStyle( this.box, this._css );//�ָ���ʽ
	//�������
	this.box = this.onShow = this.onClose = null;
  }
};



//����fixed����
var RepairFixed = function() {
	if ( !$$B.ie6 ) return;
	var layer, body, parent = "__repairfixed";//��¼���ڵ�
	function Create(){//������λ�㺯��
		body = document.body
		if (body.currentStyle.backgroundAttachment !== "fixed") {
			if (body.currentStyle.backgroundImage === "none") {
				body.runtimeStyle.backgroundRepeat = "no-repeat";
				body.runtimeStyle.backgroundImage = "url(about:blank)";
			}
			body.runtimeStyle.backgroundAttachment = "fixed";
		}
		layer = document.createElement("<div style='position:absolute;border:0;padding:0;margin:0;overflow:hidden;background:transparent;top:expression((document).documentElement.scrollTop);left:expression((document).documentElement.scrollLeft);width:expression((document).documentElement.clientWidth);height:expression((document).documentElement.clientHeight);display:block;'>");
		Create = $$.emptyFunction;
	}
	return {
		"append": function(elem){
			Create();
			elem[ parent ] = elem.parentNode;
			layer.appendChild(elem).runtimeStyle.position = "absolute";
			//����body
			if ( layer.parentNode !== body ) body.insertBefore(layer, body.childNodes[0]);
		},
		"remove": function(elem){
			if ( !layer ) return;
			//�Ƴ�Ԫ��
			if ( elem.parentNode === layer ) {
				elem.runtimeStyle.position = "";
				elem[ parent ] ? elem[parent].appendChild(elem) : layer.removeChild(elem);
				elem.removeAttribute(parent);//������delete
			}
			//û���ڲ�Ԫ�ؾ��Ƴ�
			if ( !layer.childNodes.length && layer.parentNode == body ) body.removeChild(layer);
		}
	};
}();

//����ie6)չ
if ( $$B.ie6 ) { AlertBox.prototype._initialize = (function(){
	var init = AlertBox.prototype._initialize,
		methods = {
			"init": function(){
				this._iframe = null;//�ڸ�select��iframe
				this.fixSelect = !!this.options.fixSelect;
			},
			"show": function(isResize) {
				RepairFixed[ this.fixed ? "append" : "remove" ]( this.box );
				if ( this.fixSelect ) {
					if ( !this._iframe ) {
						this._iframe = this.box.appendChild( document.createElement("<iframe style=\"position:absolute;z-index:-1;filter:alpha(opacity=0);\"></iframe>") );
						isResize = true;
					}
					if ( isResize ) {
						var size = $$D.getSize(this.box);
						$$D.setStyle( this._iframe, {
							height: size.height + "px", width: size.width + "px",
							top: -this.box.clientTop + "px", left: -this.box.clientLeft + "px"
						});
					}
				}
			},
			"close": function() {
				RepairFixed.remove( this.box );
			},
			"dispose": function() {
				RepairFixed.remove( this.box );
				if ( this._iframe ) this.box.removeChild( this._iframe );
				this._iframe = null;
			}
		};
	return function(){
		var args = [].slice.call(arguments), options = args[1] = args[1] || {};
		//)չoptions
		$$.extend( options, {
			fixSelect:	true//�Ƿ��޸�select�ڸ�����
		}, false );
		//)չ����
		$$A.forEach( methods, function( method, name ){
			$$CE.addEvent( this, name, method );
		}, this );
		init.apply( this, args );
	}
})();}


//����)չ
AlertBox.prototype._initialize = (function(){
	var init = AlertBox.prototype._initialize,
		methods = {
			"init": function(){
				this._centerCss = null;//��¼ԭʼ��ʽ
				this.center = !!this.options.center;
			},
			"show": function(isResize){
				if ( this.center ) {
					if ( !this._centerCss ) {
						var style = this.box.style;
						this._centerCss = { marginTop: style.marginTop, marginLeft: style.marginLeft, left: style.left, top: style.top };
						isResize = true;
					}
					if ( isResize ) {
						var size = $$D.getSize(this.box);
						$$D.setStyle( this.box, {
							marginTop: (this.fixed ? 0 : $$D.getScrollTop()) - size.height / 2 + "px",
							marginLeft: (this.fixed ? 0 : $$D.getScrollLeft()) - size.width / 2 + "px",
							top: "50%", left: "50%"
						});
					}
				} else {
					if ( this._centerCss ) {
						$$D.setStyle( this.box, this._centerCss ); this._centerCss = null;
					}
				}
			},
			"dispose": function(){
				if ( this._centerCss ) $$D.setStyle( this.box, this._centerCss );
				this._centerCss = null;
			}
		};
	return function(){
		var args = [].slice.call(arguments), options = args[1] = args[1] || {};
		//)չoptions
		$$.extend( options, {
			center:	false//�Ƿ����
		}, false );
		//)չ����
		$$A.forEach( methods, function( method, name ){
			$$CE.addEvent( this, name, method );
		}, this );
		init.apply( this, args );
	}
})();


//���ǲ�
var OverLay = function(){
	var overlay;
	function Create(){
		var lay = document.body.insertBefore(document.createElement("div"), document.body.childNodes[0]);
		$$D.setStyle( lay, {
			overflow: "hidden", width: "100%", height: "100%",
			border: 0, padding: 0, margin: 0, top: 0, left: 0
		});
		overlay = new AlertBox( lay, { fixed: true } );
		Create = $$.emptyFunction;
	}
	return {
		"color":	"#000000",//����ɫ
		"opacity":	.4,//͸���(0-1)
		"zIndex":	100,//���ֵ
		"show": function(){
			Create();
			$$D.setStyle( overlay.box, {
				backgroundColor: this.color, opacity: this.opacity
			});
			overlay.zIndex = this.zIndex;
			overlay.show();
		},
		"close": function(){ overlay && overlay.close(); }
	};
}()