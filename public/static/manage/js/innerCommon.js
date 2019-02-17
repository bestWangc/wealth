//同意或拒绝操作
function operateFormatter() {
    return [
        '<a class="agree btn btn-info btn-xs" href="javascript:void(0)" style="margin-right:10px;" title="Agree">',
        '同意',
        '</a>',
        '<a class="refuse btn btn-danger btn-xs" href="javascript:void(0)" title="Refuse">',
        '拒绝',
        '</a>'
    ].join('');
}

window.operateEvents = {
    'click .agree': function (e, value, row) {
        console.log(row);
        commonOperate(row.id,1);
    },
    'click .refuse': function (e, value, row) {
        commonOperate(row.id,0);
    }
};

var commonOperate = function (id,operate) {
    //加载遮罩层
    layer.load(1, {
        shade: [0.5,'#fff']
    });
    $.ajax({
        url: 'operation',
        type: 'POST',
        dataType: 'json',
        data: {
            id : id,
            operate : operate
        },
        cache: false,
        success: function (res) {
            console.log(res);
            var callBack = '';
            if(res.code == 1){
                callBack = function () {
                    parent.refreshIframe();
                };
            }
            layer.closeAll('loading');
            layer.msg(res.msg,{time:1500},callBack);
        },
        error: function () {
            layer.closeAll('loading');
            console.log(res);
        }
    });
};