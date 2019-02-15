$(function(){
    $('#pop').hide();
        
    $('.del_admin').click(function(){
        var admin_id=this.id;
        $.post(app_url+"/admin/del_admin",{
            "admin_id":admin_id
        },function(data){
            chuli(data)
            },'json');
    });
        
    function chuli(data){
        switch(data.data){
            case 1:
                $('#pop div').html('删除成功');
                $('#pop').show();
                $('#admin_'+data.info).hide();
                break;
            case 2:
                $('#pop div').html('必须保留一个管理员！');
                $('#pop').show();
                break;
        }
    }

    $('#pop').ajaxStart(function(){
        $('#pop div').html('数据请求中...请稍候...');
        $('#pop').show();
    });

});