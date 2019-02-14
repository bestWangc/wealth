function textChecked(pwd) {
    let num = 0;
    //为0
    if(pwd == 0){
        num++;
    }
    //小写字母
    if(pwd.match(/([a-z])+/)){
        num++;
    }
    //数字
    /*if(pwd.match(/([0-9])+/)){
        num++;
    }*/
    //大写字母
    if(pwd.match(/([A-Z])+/)){
        num++;
    }
    //特殊符号
    if(pwd.match(/[^a-zA-Z0-9]+/)){
        num++;
    }
    return num;
};

function checkAgents() {
    var userAgentInfo = navigator.userAgent;
    var Agents = new Array("Android","iPhone","SymbianOS","Windows Phone","iPad","iPod");
    var flag=true;
    for(var v=0;v<Agents.length;v++) {
        if(userAgentInfo.indexOf(Agents[v])>0) {
            flag=false;
            break;
        }
    }
    return flag;
}
