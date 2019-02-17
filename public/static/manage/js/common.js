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