$(function(){
    $('#admin_pwd').focus();
    $('#pop').hide();

    $('#tijiao').click(function(){
        restart();
        var admin_id=$('#admin_id').val();
        var admin_pwd=$('#admin_pwd').val();
        var admin_pwd1=$('#admin_pwd1').val();

        if(check(admin_pwd,admin_pwd1)){
            $.post(app_url+"/admin/update",{
                "admin_id":admin_id,
                "admin_pwd":admin_pwd
            },function(data){
                chuli_save(data);
            },'json');
        }
    });
            
    function chuli_save(data){
        switch(data.data){
            case 0:
                $('#pop div').html('服务器处理错误，请刷新后重新操作');
                $('#pop').show();
                break;
            case 1:
                $('#pop div').html('用户登录密码修改成功，请牢记您的新密码');
                $('#pop').show();
                $('#admin_pwd').val('');
                $('#admin_pwd1').val('');
                $('#admin_pwd').focus();
                restart();
                break;
        }
    }
        
        
    function restart(){
        $('#admin_pwd_pop').removeClass('error');
        $('#admin_pwd_pop').removeClass('success');
        $('#admin_pwd_pop').html('');
        $('#admin_pwd1_pop').removeClass('error');
        $('#admin_pwd1_pop').removeClass('success');
        $('#admin_pwd1_pop').html('');
    }


    function check(admin_pwd,admin_pwd1){
                
        if(admin_pwd==''){
            $('#admin_pwd_pop').addClass('error');
            $('#admin_pwd_pop').html('请填写登录密码');
            $('#admin_pwd').focus();
            return false;
        }else{
            $('#admin_pwd_pop').addClass('success');
            $('#admin_pwd_pop').removeClass('error');
            $('#admin_pwd_pop').html('');
        }
        if(admin_pwd!=admin_pwd1){
            $('#admin_pwd_pop').addClass('error');
            $('#admin_pwd_pop').html('两次输入的密码不一致，请重新输入！');
            $('#admin_pwd').val('');
            $('#admin_pwd1').val('');
            $('#admin_pwd').focus();
            return false;
        }else{
            $('#admin_pwd1_pop').addClass('success');
            $('#admin_pwd1_pop').removeClass('error');
            $('#admin_pwd1_pop').html('');
        }
        return true;
    }



    $('#pop').ajaxStart(function(){
        $('#pop div').html('数据处理中...请稍候');
        $('#pop').show();
    });
            
            
});