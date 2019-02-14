function displayleft(obj,name)
{
 LeftDiv=eval("document.all."+name)
   if (LeftDiv.style.display=="none"){
     LeftDiv.style.display="";
     obj.src="images/t_left.gif"
     obj.title="隐藏左侧信息栏"
     document.getElementById("rightbox").style.left='176px'
   }
   else{
     LeftDiv.style.display="none";
     obj.src="images/t_right.gif"
     obj.title="显示左侧信息栏"
     document.getElementById("rightbox").style.left='8px'
   }
 }
function displayup(obj,name)
{
 LeftDiv=eval("document.all."+name)
   if (LeftDiv.style.display=="none")
   {
     LeftDiv.style.display="";
     obj.src="images/arrow_up.gif"
     obj.title="隐藏顶部信息栏"
	 
      }
     else
    {
     LeftDiv.style.display="none";
     obj.src="images/arrow_down.gif"
     obj.title="显示顶部信息栏"
    }
 }
