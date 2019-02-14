function $(str){return document.getElementById(str);}
//Í·²¿µ¼º½
function MM_NavImage(objid,submenu,num,cur){
	setTimeout(function(){
		var MM_sr="";
		for (var i=1;i<=num;i++){
			MM_sr=$(objid+i).src;
			MM_sr=MM_sr.replace("yellow","blue");
			$(objid+i).src=MM_sr; 
			$(submenu+i).style.display="none";
		}
		MM_sr=$(objid+cur).src;
		MM_sr=MM_sr.replace("blue","yellow");
		$(objid+cur).src=MM_sr; 
		$(submenu+cur).style.display="";
	},300)
}

function loadnav(){
	var M_sr,Mref=location.href;
	$("submenu1").style.display="none";
	if (Mref.indexOf("/topseller/")>0){
		M_sr=$("vImage11").src; 
		M_sr=M_sr.replace("blue","yellow");
		$("vImage11").src=M_sr;
		$("submenu11").style.display="";
	}else if(Mref.indexOf("/store/")>0){
		M_sr=$("vImage10").src; 
		M_sr=M_sr.replace("blue","yellow");
		$("vImage10").src=M_sr;
		$("submenu10").style.display="";
	}else if(Mref.indexOf("/oriental/")>0){
		M_sr=$("vImage9").src; 
		M_sr=M_sr.replace("blue","yellow");
		$("vImage9").src=M_sr;
		$("submenu9").style.display="";
	}else if(Mref.indexOf("/netSpeed/")>0 || Mref.indexOf("/netspeed/")>0){
		M_sr=$("vImage8").src; 
		M_sr=M_sr.replace("blue","yellow");
		$("vImage8").src=M_sr;
		$("submenu8").style.display="";
	}else if(Mref.indexOf("/vps/")>0){
		M_sr=$("vImage7").src; 
		M_sr=M_sr.replace("blue","yellow");
		$("vImage7").src=M_sr;
		$("submenu7").style.display="";
	}else if(Mref.indexOf("/cabinet")>0){
		M_sr=$("vImage6").src; 
		M_sr=M_sr.replace("blue","yellow");
		$("vImage6").src=M_sr;
		$("submenu6").style.display="";
	}else if(Mref.indexOf("/idc/")>0){
		M_sr=$("vImage5").src; 
		M_sr=M_sr.replace("blue","yellow");
		$("vImage5").src=M_sr;
		$("submenu5").style.display="";
	}else if(Mref.indexOf("/server/")>0){
		M_sr=$("vImage4").src; 
		M_sr=M_sr.replace("blue","yellow");
		$("vImage4").src=M_sr;
		$("submenu4").style.display="";
	}else if(Mref.indexOf("/webhost/")>0){
		M_sr=$("vImage3").src; 
		M_sr=M_sr.replace("blue","yellow");
		$("vImage3").src=M_sr;
		$("submenu3").style.display="";
	}else if(Mref.indexOf("/domain/")>0){
		M_sr=$("vImage2").src; 
		M_sr=M_sr.replace("blue","yellow");
		$("vImage2").src=M_sr;
		$("submenu2").style.display="";
	}else if(Mref.substring(parseInt(Mref.length-4),Mref.length)==".cn/"){
		M_sr=$("vImage1").src; 
		M_sr=M_sr.replace("blue","yellow");
		$("vImage1").src=M_sr;
		$("submenu1").style.display="";
	}else{
		M_sr=$("vImage1").src; 
		M_sr=M_sr.replace("blue","yellow");
		$("vImage1").src=M_sr;
		$("submenu1").style.display="";
	} 
}