$(function(){
    $('#pop').hide();
        
    $('.delete_action').click(function(){
        var id=this.id;
        $.post(app_url+"/"+module_name+"/clean_cache",{
            "id":id
        },function(data){
            chuli(data)
            },'json');
    });
        
    function chuli(data){
        switch(data.data){
            case 1:
                $('#pop div').html('缓存清理成功！');
                $('#pop').show().fadeOut(2000);
                $('#tr_'+data.info).hide();
                break;
            default:
                $('#pop div').html('缓存清理失败！');
                $('#pop').show().fadeOut(2000);
                break;
        }
    }

    $('#pop').ajaxStart(function(){
        $('#pop div').html('数据请求中...请稍候...');
        $('#pop').show();
    });

});