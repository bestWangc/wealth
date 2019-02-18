//根据左侧树刷新右侧tab
function refreshIframe() {
    var activeTabData = $('a.active.J_menuTab').data('id');

    $('li.active a.J_menuItem').each(function () {
        if($(this).attr('href') == activeTabData){
            var activeItemIndex = $(this).data('index')+1;
            console.log(activeItemIndex)
            $('iframe[name="iframe'+activeItemIndex+'"]').attr('src', $('iframe[name="iframe'+activeItemIndex+'"]').attr('src'));
        }
    });
}

/**
 * 获取钱包历史记录类别
 * @param type $id
 */
function getMainLastStatus(id) {
    let msg = '';
    switch (id) {
        case 1:
            msg = '利息';
            break;
        case 7:
            msg = '购买';
            break;
        case 2:
            msg = '提现';
            break;
        case 3:
            msg = '充值';
            break;
        case 4:
            msg = '奖励';
            break;
        case 5:
            msg = '签到';
            break;
        case 6:
            msg = '返还';
            break;
        default:
            msg = '其他';
    }
    return msg;
}