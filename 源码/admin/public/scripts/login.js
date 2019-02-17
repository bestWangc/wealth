$(function(){
    $('#admin_name').focus();
    $('#tijiao').click(function(){
        var admin_name=$('#admin_name').val();
        var admin_pwd=$('#admin_pwd').val();
                    
        if(check(admin_name,admin_pwd)){
            $.post(app_url+"/system/check",{
                'admin_name':admin_name,
                'admin_pwd':admin_pwd
            },function(data){
                chuli(data)
                },'json');
        }
    });

    function check(admin_name,admin_pwd){
        if(admin_name==''){
            $('#pop').html('<div>用户名不能为空！请填写用户名！</div>');
            $('#admin_name').focus();
            return false;
        }
            
        if(admin_pwd==''){
            $('#pop').html('<div>用户密码不能为空！请填写用户密码！</div>');
            $('#admin_pwd').focus();
            return false;
        }
        return true;
    }
                
    function chuli(data){
        switch(data.data){
            case 1:
                $('#pop').html('<div>用户不存在！</div>');
                break;
            case 2:
                $('#pop').html('<div>登录成功</div>');
                location.href=app_url+"/main/index";
                break;
            case 3:
                $('#pop').html('<div>用户密码不正确！</div>');
                $('#admin_pwd').val('');
                $('#admin_pwd').focus();
                break;
            default:
                $('#pop').html('<div>系统未知错误，请刷新后重试！</div>');
                break;
        }
    }

    $('#pop').ajaxStart(function(){
        $('#pop').html('<div>数据请求中...请稍后...</div>');
    });



});