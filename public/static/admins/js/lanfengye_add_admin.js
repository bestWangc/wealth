$(function(){
    $('#admin_name').focus();
    $('#pop').hide();

    $('#tijiao').click(function(){
        restart();
        var admin_name=$('#admin_name').val();
        var admin_pwd=$('#admin_pwd').val();
        var admin_pwd1=$('#admin_pwd1').val();

        if(check(admin_name,admin_pwd,admin_pwd1)){
            $.post(app_url+"/admin/save_add_admin",{
                "admin_name":admin_name,
                "admin_pwd":admin_pwd
            },function(data){
                chuli_save(data);
            },'json');
        }
    });
            
    function chuli_save(data){
        switch(data.data){
            case 0:
                $('#pop div').html('用户名或者密码不能为空！');
                $('#pop').show();
                break;
            case 1:
                $('#pop div').html('该用户已经存在');
                $('#pop').show();
                break;
            case 2:
                $('#pop div').html('管理用户添加成功，请牢记您的密码，您可以继续添加新管理用户');
                $('#pop').show();
                $('#admin_name').val('');
                $('#admin_pwd').val('');
                $('#admin_pwd1').val('');
                $('#admin_name').focus();
                restart();
                break;
            case 3:
                $('#pop div').html('服务器处理错误，请刷新后重试');
                $('#pop').show();
                break;
        }
    }
        
        
    function restart(){
        $('#admin_name_pop').removeClass('error');
        $('#admin_name_pop').removeClass('success');
        $('#admin_name_pop').html('');
        $('#admin_pwd_pop').removeClass('error');
        $('#admin_pwd_pop').removeClass('success');
        $('#admin_pwd_pop').html('');
        $('#admin_pwd1_pop').removeClass('error');
        $('#admin_pwd1_pop').removeClass('success');
        $('#admin_pwd1_pop').html('');
    }


    function jiance_chongming(){
        var admin_name=$('#admin_name').val();
        if(admin_name!=''){
            $.post(app_url+"/admin/check_admin_name",{
                "admin_name":admin_name
            },function(data){
                chuli(data.data);
            },'json');
        }else{
            $('#admin_name_pop').addClass('error');
            $('#admin_name_pop').html('请填写用户名');
            $('#admin_name').focus();
        }
        
    }

    $('#admin_name').change(function(){
        jiance_chongming();
    });
            
    $('#admin_name').blur(function(){
        jiance_chongming();
    });
            
            


    function chuli(status)
    {
        switch(status)
        {
            case 1:   //用户已经存在
                $('#admin_name_pop').addClass('error');
                $('#admin_name_pop').html('用户名已经被使用');
                $('#admin_name').val('');
                $('#admin_name').focus();
                break;
            case 2:   //可以注册
                $('#admin_name_pop').addClass('success');
                $('#admin_name_pop').removeClass('error');
                $('#admin_name_pop').html('');
                $('#admin_pwd').focus();
                break;
        }
        $('#pop div').html('');
        $('#pop').hide();
    }


    function check(admin_name,admin_pwd,admin_pwd1){
        if(admin_name==''){
            $('#admin_name_pop').addClass('error');
            $('#admin_name_pop').html('请填写用户名');
            $('#admin_name').focus();
            return false;
        }else{
            $('#admin_name_pop').addClass('success');
            $('#admin_name_pop').removeClass('error');
            $('#admin_name_pop').html('');
        }
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