//图片按比例缩放
$(document).ready(function(e) {
    $("#aside .list,#aside .list1").click(function(e) {
        $(this).next().next().slideToggle();
    });
	$("#information_bg,#information a.close_click,#information input.Cancel").click(function(e) {
		$("#information,#information_bg").hide();
	});
    $("#formStyle ul li.select input,#formStyle ul li.select b").click(function(e) {
        $(this).parent().find("p").show();
		$(this).parent().find("p a").click(function(e) {
			$(this).parent().parent().find("input").val($(this).text());
			$(this).parent().hide();
        });
    });
});
var ie6=!-[1,]&&!window.XMLHttpRequest;
if(ie6==true){
	alert("抱歉，您所使用的浏览器版本过低，可能导致了您在浏览上的不便，\r\n为此您可以选择 360安全浏览器 / Google Chrome / 火狐 / 猎豹 / 搜狗 等主流浏览器，\r\n从而获得更好的浏览体验。")
	window.open('','_parent','');
	window.opener = window;
	window.close();
}