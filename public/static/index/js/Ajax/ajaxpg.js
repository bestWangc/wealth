	var http_request=false;
	function send_request(url){//初始化，指定处理函数，发送请求的函数
		http_request=false;
		//开始初始化XMLHttpRequest对象
		if(window.XMLHttpRequest){//Mozilla浏览器
			http_request=new XMLHttpRequest();
			if(http_request.overrideMimeType){//设置MIME类别
				http_request.overrideMimeType("text/xml");
			}
		}else if(window.ActiveXObject){//IE浏览器
		try{
			http_request=new ActiveXObject("Msxml2.XMLHttp");
		}catch(e){
		try{
			http_request=new ActiveXobject("Microsoft.XMLHttp");
		}catch(e){}
		}
		}
		if(!http_request){//异常，创建对象实例失败
			window.alert("创建XMLHttp对象失败！");
			return false;
		}
		http_request.onreadystatechange=processrequest;
		//确定发送请求方式，URL，及是否同步执行下段代码
		http_request.open("GET",url,true);
		http_request.send(null);
	}
  //处理返回信息的函数
	function processrequest(){
		if(http_request.readyState==4){//判断对象状态
			if(http_request.status==200){//信息已成功返回，开始处理信息
				document.getElementById(reobj).innerHTML=http_request.responseText;
			}else{//页面不正常
				alert("您所请求的页面不正常！");
			}
		}
	}
	function dopage(obj,url){
		if(confirm('确定结算奖金吗?')){
			url += "/a1/" + document.getElementById("a1").value;
			document.getElementById(obj).innerHTML="<br /><br />奖金结算 <br /><br /><img src='../../Public/Images/Clearing.gif' width='90' height='17' border='0' align='absmiddle' /><br /><br />奖金结算中，请耐心等待...<br /><br /><br />";
			send_request(url);
			reobj=obj;
		}
	}