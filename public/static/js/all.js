/*
去连接虚线
*/
window.onload=function()
{
 for(var ii=0; ii<document.links.length; ii++)
 document.links[ii].onfocus=function(){this.blur()}
}
function fHideFocus(tName){
aTag=document.getElementsByTagName(tName);
for(i=0;i<aTag.length;i++)aTag[i].hideFocus=true;
//for(i=0;i<aTag.length;i++)aTag[i].onfocus=function(){this.blur();};
}

/*========mF_tab=============*/
myFocus.extend({
	mF_tab:function(par){
		var box=this.$(par.id);
		this.$$('ul',box)[1].innerHTML='<li><ul class=swt>'+this.$$('ul',box)[1].innerHTML+'</ul></li>';
		var btnx=this.$li('btnx',box),wrap=this.$c('cont',box),swt=this.$c('swt',box),cont=this.$$_('li',swt),n=btnx.length;
		//CSS
		swt.style.width=n*par.width+'px';
		for(var i=0;i<n;i++) cont[i].style.cssText='width:'+par.width+'px;height:'+par.height+'px;float:left;';
		par.height=par.height=='auto'?swt.offsetHeight:par.height;
		wrap.style.cssText='width:'+par.width+'px;height:'+par.height+'px;';
		box.style.cssText='width:'+(par.width+2)+'px;height:'+(par.height+29)+'px;';
		if(par.type=='fade'||par.type=='none'){for(var i=0;i<n;i++) cont[i].style.display='none';} 
		//PLAY
		eval(this.switchMF(function(){
			btnx[index].className='';
			if(par.type=='fade'||par.type=='none') cont[index].style.display='none';
		},function(){
			if(par.type=='slide') myFocus.slide(swt,{left:-(next*par.width)},20,'easeInOut')
			if(par.type=='fade') myFocus.fadeIn(cont[next]);
			if(par.type=='none') cont[next].style.display='';
			btnx[next].className='current';
		}))
		eval(this.bind('btnx','par.trigger',par.delay));
	}
})
myFocus.set.params('mF_tab',{trigger:'mouseover',type:'slide'})//设置tab的默认值
////mF_tab代码结束，下面是设置
//myFocus.set({
//    id:'myFocus2',
//    pattern:'mF_tab',
//    type:'fade',
//    width:800,
//    height:100
//});
//myFocus.set({
//    id:'myFocus3',
//    pattern:'mF_tab',
//    type:'fade',
//    width:800,
//    height:100
//});

// table排序
function TableSorter(table)
{
this.Table = this.$(table);
if(this.Table.rows.length <= 1)
{
return;
}
this.Init(arguments);
}
//初始化table的信息和操作.
TableSorter.prototype.Init = function(args)
{
this.ViewState = [];
for(var x = 0; x < this.Table.rows[0].cells.length; x++)
{
this.ViewState[x] = false;
}
if(args.length > 1)
{
for(var x = 1; x < args.length; x++)
{
if(args[x] > this.Table.rows.length)
{
continue;
}
else
{
this.Table.rows[0].cells[args[x]].onclick = this.GetFunction(this,"Sort",args[x]);
this.Table.rows[0].cells[args[x]].style.cursor = "pointer";
}
}
}
else
{
for(var x = 0; x < this.Table.rows[0].cells.length; x++)
{
this.Table.rows[0].cells[x].onclick = this.GetFunction(this,"Sort",x);
this.Table.rows[0].cells[x].style.cursor = "pointer";
}
}
}
//简写document.getElementById方法.
TableSorter.prototype.$ = function(element)
{
return document.getElementById(element);
}
//取得指定对象的脱壳函数.
TableSorter.prototype.GetFunction = function(variable,method,param)
{
return function()
{
variable[method](param);
}
}
//执行排序.
TableSorter.prototype.Sort = function(col)
{
	var SortAsNumber = true;
	for(var x = 0; x < this.Table.rows[0].cells.length; x++)
	{
	}
	var Sorter = [];
	for(var x = 1; x < this.Table.rows.length; x++)
	{
		Sorter[x-1] = [this.Table.rows[x].cells[col].innerHTML, x];
			if (!isNaN(Sorter[x-1][0])){
				var yyyo=Sorter[x-1][0].split('.');
				Sorter[x-1][0] = yyyo[0];
			}
		SortAsNumber = SortAsNumber && this.IsNumeric(Sorter[x-1][0]);
	}
	if(SortAsNumber)
	{
		for(var x = 0; x < Sorter.length; x++)
		{
			for(var y = x + 1; y < Sorter.length; y++)
			{
				if(parseFloat(Sorter[y][0]) < parseFloat(Sorter[x][0]))
				{
					var tmp = Sorter[x];
					Sorter[x] = Sorter[y];
					Sorter[y] = tmp;
				}
			}
		}
	}else{
		Sorter.sort();
	}
	if(this.ViewState[col])
	{
		Sorter.reverse();
		this.ViewState[col] = false;
	}else{
		this.ViewState[col] = true;
	}
	var Rank = [];
	for(var x = 0; x < Sorter.length; x++){Rank[x] = this.GetRowHtml(this.Table.rows[Sorter[x][1]]);}
	for(var x = 1; x < this.Table.rows.length; x++)
	{
		for(var y = 0; y < this.Table.rows[x].cells.length; y++){this.Table.rows[x].cells[y].innerHTML = Rank[x-1][y];}
	}
	this.OnSorted(this.Table.rows[0].cells[col], this.ViewState[col]);
}
//取得指定行的内容.
TableSorter.prototype.GetRowHtml = function(row)
{
var result = [];
for(var x = 0; x < row.cells.length; x++)
{
result[x] = row.cells[x].innerHTML;
}
return result;
}
TableSorter.prototype.IsNumeric = function(num)
{
return /^\d+[\.\d+]?$/.test(num);
}
//可自行实现排序后的动作.
TableSorter.prototype.OnSorted = function(cell, IsAsc)
{
return;
}