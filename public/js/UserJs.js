function bank_us(name) {
    var bank = document.getElementById('bank_id');
    if (name == '财付通') {
        bank.innerHTML = '财付通帐号'
    }else if(name == '支付宝'){
		bank.innerHTML = '支付宝帐号'
	} else {
        bank.innerHTML = '银行卡号'
    }
}

function Dsy() {
    this.Items = {}
}
Dsy.prototype.add = function(id, iArray) {
    this.Items[id] = iArray
}
Dsy.prototype.Exists = function(id) {
    if (typeof(this.Items[id]) == "undefined") return false;
    return true
}
function change(v) {
    var str = "0";
    for (i = 0; i < v; i++) {
        str += ("_" + (document.getElementById(s[i]).selectedIndex - 1))
    };
    var ss = document.getElementById(s[v]);
    with(ss) {
        length = 0;
        options[0] = new Option(opt0[v], opt0[v]);
        if (v && document.getElementById(s[v - 1]).selectedIndex > 0 || !v) {
            if (dsy.Exists(str)) {
                ar = dsy.Items[str];
                for (i = 0; i < ar.length; i++) options[length] = new Option(ar[i], ar[i]);
                if (v) options[1].selected = true
            }
        }
        if (++v < s.length) {
            change(v)
        }
    }
}
function change1(v) {
    var str = "0";
    for (i = 0; i < v; i++) {
        str += ("_" + (document.getElementById(d[i]).selectedIndex - 1))
    };
    var ss = document.getElementById(d[v]);
    with(ss) {
        length = 0;
        options[0] = new Option(opt0[v], opt0[v]);
        if (v && document.getElementById(d[v - 1]).selectedIndex > 0 || !v) {
            if (dsy.Exists(str)) {
                ar = dsy.Items[str];
                for (i = 0; i < ar.length; i++) options[length] = new Option(ar[i], ar[i]);
                if (v) options[0].selected = true
            }
        }
        if (++v < d.length) {
            change1(v)
        }
    }
}
var dsy = new Dsy();
dsy.add("0", ["北京市", "天津市", "河北省", "山西省", "内蒙古", "辽宁省", "吉林省", "黑龙江省", "上海市", "江苏省", "浙江省", "安徽省", "福建省", "江西省", "山东省", "河南省", "湖北省", "湖南省", "广东省", "广西自治区", "海南省", "重庆市", "四川省", "贵州省", "云南省", "西藏自治区", "陕西省", "甘肃省", "青海省", "宁夏回族自治区", "新疆维吾尔自治区", "香港特别行政区", "澳门特别行政区", "台湾省", "其它"]);
dsy.add("0_0", ["北京", "东城区", "西城区", "崇文区", "宣武区", "朝阳区", "丰台区", "石景山区", " 海淀区（中关村）", "门头沟区", "房山区", "通州区", "顺义区", "昌平区", "大兴区", "怀柔区", "平谷区", "密云县", "延庆县", "其他"]);
dsy.add("0_1", ["和平区", "河东区", "河西区", "南开区", "红桥区", "塘沽区", "汉沽区", "大港区", "西青区", "津南区", "武清区", "蓟县", "宁河县", "静海县", "其他"]);
dsy.add("0_2", ["石家庄市", "张家口市", "承德市", "秦皇岛市", "唐山市", "廊坊市", "衡水市", "沧州市", "邢台市", "邯郸市", "保定市", "其他"]);
dsy.add("0_3", ["太原市", "朔州市", "大同市", "长治市", "晋城市", "忻州市", "晋中市", "临汾市", "吕梁市", "运城市", "其他"]);
dsy.add("0_4", ["呼和浩特市", "包头市", "赤峰市", "呼伦贝尔市", "鄂尔多斯市", "乌兰察布市", "巴彦淖尔市", "兴安盟", "阿拉善盟", "锡林郭勒盟", "其他"]);
dsy.add("0_5", ["沈阳市", "朝阳市", "阜新市", "铁岭市", "抚顺市", "丹东市", "本溪市", "辽阳市", "鞍山市", "大连市", "营口市", "盘锦市", "锦州市", "葫芦岛市", "其他"]);
dsy.add("0_6", ["长春市", "白城市", "吉林市", "四平市", "辽源市", "通化市", "白山市", "延边朝鲜族自治州", "其他"]);
dsy.add("0_7", ["哈尔滨市", "七台河市", "黑河市", "大庆市", "齐齐哈尔市", "伊春市", "佳木斯市", "双鸭山市", "鸡西市", "大兴安岭地区(加格达奇)", "牡丹江", "鹤岗市", "绥化市　", "其他"]);
dsy.add("0_8", ["黄浦区", "卢湾区", "徐汇区", "长宁区", "静安区", "普陀区", "闸北区", "虹口区", "杨浦区", "闵行区", "宝山区", "嘉定区", "浦东新区", "金山区", "松江区", "青浦区", "南汇区", "奉贤区", "崇明县", "其他"]);
dsy.add("0_9", ["南京市", "徐州市", "连云港市", "宿迁市", "淮安市", "盐城市", "扬州市", "泰州市", "南通市", "镇江市", "常州市", "无锡市", "苏州市", "其他"]);
dsy.add("0_10", ["杭州市", "湖州市", "嘉兴市", "舟山市", "宁波市", "绍兴市", "衢州市", "金华市", "台州市", "温州市", "丽水市", "其他"]);
dsy.add("0_11", ["合肥市", "宿州市", "淮北市", "亳州市", "阜阳市", "蚌埠市", "淮南市", "滁州市", "马鞍山市", "芜湖市", "铜陵市", "安庆市", "黄山市", "六安市", "巢湖市", "池州市", "宣城市", "其他"]);
dsy.add("0_12", ["福州市", "南平市", "莆田市", "三明市", "泉州市", "厦门市", "漳州市", "龙岩市", "宁德市", "其他"]);
dsy.add("0_13", ["南昌市", "九江市", "景德镇市", "鹰潭市", "新余市", "萍乡市", "赣州市", "上饶市", "抚州市", "宜春市", "吉安市", "其他"]);
dsy.add("0_14", ["济南市", "聊城市", "德州市", "东营市", "淄博市", "潍坊市", "烟台市", "威海市", "青岛市", "日照市", "临沂市", "枣庄市", "济宁市", "泰安市", "莱芜市", "滨州市", "菏泽市", "其他"]);
dsy.add("0_15", ["郑州市", "三门峡市", "洛阳市", "焦作市", "新乡市", "鹤壁市", "安阳市", "濮阳市", "开封市", "商丘市", "许昌市", "漯河市", "平顶山市", "南阳市", "信阳市", "周口市", "驻马店市", "其他"]);
dsy.add("0_16", ["武汉市", "十堰市", "襄樊市", "荆门市", "孝感市", "黄冈市", "鄂州市", "黄石市", "咸宁市", "荆州市", "宜昌市", "随州市", "恩施土家族苗族自治州", "仙桃市", "天门市", "潜江市", "神农架林区", "其他"]);
dsy.add("0_17", ["长沙市", "张家界市", "常德市", "益阳市", "岳阳市", "株洲市", "湘潭市", "衡阳市", "郴州市", "永州市", "邵阳市", "怀化市", "娄底市", "湘西土家族苗族自治州", "其他"]);
dsy.add("0_18", ["广州市", "清远市市", "韶关市", "河源市", "梅州市", "潮州市", "汕头市", "揭阳市", "汕尾市", "惠州市", "东莞市", "深圳市", "珠海市", "中山市", "江门市", "佛山市", "肇庆市", "云浮市", "阳江市", "茂名市", "湛江市", "其他"]);
dsy.add("0_19", ["南宁市", "桂林市", "柳州市", "梧州市", "贵港市", "玉林市", "钦州市", "北海市", "防城港市", "崇左市", "百色市", "河池市", "来宾市", "贺州市", "其他"]);
dsy.add("0_20", ["海口市", "三亚市", "其他"]);
dsy.add("0_21", ["渝中区", "大渡口区", "江北区", "沙坪坝区", "九龙坡区", "南岸区", "北碚区", "万盛区", "双桥区", "渝北区", "巴南区", "万州区", "涪陵区", "黔江区", "长寿区", "合川市", "永川市", "江津市", "南川市", "綦江县", "潼南县", "铜梁县", "大足县", "璧山县", "垫江县", "武隆县", "丰都县", "城口县", "开县", "巫溪县", "巫山县", "奉节县", "云阳县", "忠县", "石柱土家族自治县", "彭水苗族土家族自治县", "酉阳土家族苗族自治县", "秀山土家族苗族自治县", "其他"]);
dsy.add("0_22", ["成都市", "广元市", "绵阳市", "德阳市", "南充市", "广安市", "遂宁市", "内江市", "乐山市", "自贡市", "泸州市", "宜宾市", "攀枝花市", "巴中市", "资阳市", "眉山市", "雅安", "阿坝藏族羌族自治州", "甘孜藏族自治州", "凉山彝族自治州县", "其他"]);
dsy.add("0_23", ["贵阳市", "六盘水市", "遵义市", "安顺市", "毕节地区", "铜仁地区", "黔东南苗族侗族自治州", "黔南布依族苗族自治州", "黔西南布依族苗族自治州", "其他"]);
dsy.add("0_24", ["昆明市", "曲靖市", "玉溪市", "保山市", "昭通市", "丽江市", "普洱市", "临沧市", "宁德市", "德宏傣族景颇族自治州", "怒江傈僳族自治州", "楚雄彝族自治州", "红河哈尼族彝族自治州", "文山壮族苗族自治州", "大理白族自治州", "迪庆藏族自治州", "西双版纳傣族自治州", "其他"]);
dsy.add("0_25", ["拉萨市", "那曲地区", "昌都地区", "林芝地区", "山南地区", "日喀则地区", "阿里地区", "其他"]);
dsy.add("0_26", ["西安市", "延安市", "铜川市", "渭南市", "咸阳市", "宝鸡市", "汉中市", "安康市", "商洛市", "其他"]);
dsy.add("0_27", ["兰州市 ", "嘉峪关市", "金昌市", "白银市", "天水市", "武威市", "酒泉市", "张掖市", "庆阳市", "平凉市", "定西市", "陇南市", "临夏回族自治州", "甘南藏族自治州", "其他"]);
dsy.add("0_28", ["西宁市", "海东地区", "海北藏族自治州", "黄南藏族自治州", "玉树藏族自治州", "海南藏族自治州", "果洛藏族自治州", "海西蒙古族藏族自治州", "其他"]);
dsy.add("0_29", ["银川市", "石嘴山市", "吴忠市", "固原市", "中卫市", "其他"]);
dsy.add("0_30", ["乌鲁木齐市", "克拉玛依市", "喀什地区", "阿克苏地区", "和田地区", "吐鲁番地区", "哈密地区", "塔城地区", "阿勒泰地区", "克孜勒苏柯尔克孜自治州", "博尔塔拉蒙古自治州", "昌吉回族自治州 伊犁哈萨克自治州", "巴音郭楞蒙古自治州", "河子市", "阿拉尔市", "五家渠市", "图木舒克市", "其他"]);
dsy.add("0_31", ["香港", "其他"]);
dsy.add("0_32", ["澳门", "其他"]);
dsy.add("0_33", ["台湾", "其他"]);
dsy.add("0_34", ["其他"]);


//===================取字符的长度================ 
function   getLen( str) {
   var totallength=0;   for (var i=0;i<str.length;i++)
   {
    var intCode=str.charCodeAt(i);    if (intCode>=0&&intCode<=128) {
     totallength=totallength+1; //非中文单个字符长度加 1
    }
    else {
     totallength=totallength+2; //中文字符长度则加 2
    }
   } //end for 
return totallength;
}

function yhPass(Pass)
{
	var E_Name=document.getElementById(Pass);
	if(E_Name)
	{
		var DvFul= document.getElementById(Pass+'1');
		str = E_Name.value.replace(/^\s+|\s+$/g,"");
		if( getLen(str) >= 1 )
		{
			if(DvFul){DvFul.innerHTML = "<div class='msg_ok'></div>";}
			if(DvFul){E_Name.value = str;}
			return true;
		}
		if(DvFul){DvFul.innerHTML = "<div class='msg_error'>输入的内容必须大于1个字符!</div>";}
		return false;
	}
	return false;
}

//=================判断输入大于6位====================  
function yhrePass(Pass,rePass)
{
	var E_Name=document.getElementById(Pass);
	var re_Name=document.getElementById(rePass);
	if(E_Name && re_Name)
	{
		var DvFul= document.getElementById(Pass+'1');
		str = E_Name.value.replace(/^\s+|\s+$/g,"");
		//if( getLen(str) >= 6 )
		//{
			if(E_Name.value == re_Name.value){
				if(DvFul){DvFul.innerHTML = "<div class='msg_ok'></div>";}
				return true;
			}else{
				if(DvFul){DvFul.innerHTML = "<div class='msg_error'>输入与确认输入不一致!</div>";}
				return false;
			}
		//}
		//if(DvFul){DvFul.innerHTML = "<div class='msg_error'>输入的内容必须大于6个字符!</div>";}
		return false;
	}
	return false;
}

//===========判断输入是否为空=======================
function Null_Full(Put_Name)
{ 
	var Over_Name=document.getElementById(Put_Name);
	if (Over_Name)
	{
		var DvName=document.getElementById(Put_Name+'1');
		str = Over_Name.value.replace(/^\s+|\s+$/g,"");
		if(getLen(str) > 0)
		{
			if (DvName){DvName.innerHTML= "<div class='msg_ok'></div>";}
			return true;
		}
		if (DvName){DvName.innerHTML= "<div class='msg_error'>内容不能为空!</div>";}
		return false;
	}
	return false;
}

//==============判断是不是数字===============
function Null_Int(Null_N)
{
	var Null_Name=document.getElementById(Null_N);
	if (Null_Name)
	{
		var IntDvName=document.getElementById(Null_N+'1');
		str = Null_Name.value.replace(/^\s+|\s+$/g,"");
		if (!str == "")//判断是否为空
		{
			if (!isNaN(Null_Name.value))
			{
				if(IntDvName){IntDvName.innerHTML="<div class='msg_ok'></div>";}
				return true;
			}
			if(IntDvName){IntDvName.innerHTML="<div class='msg_error'>对不起，请填写数字!</div>";}
			return false;
		}
		if (IntDvName){IntDvName.innerHTML= "<div class='msg_error'>内容不能为空!</div>";}
		return false;
	}
	return false;
}


//=================邮箱判断==============
function Email(sMsgId)
{
	var oEmail = document.getElementById(sMsgId);
	if(oEmail){
		var oMsg = document.getElementById(sMsgId+"1");
		if(oEmail.value){
			var oReg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/; 
			var bRes = oReg.test(oEmail.value);
			if(bRes){
				if(oMsg){oMsg.innerHTML = "<div class='msg_ok'></div>";}
				return true;
			}
			if(oMsg){oMsg.innerHTML = "<div class='msg_error'>邮件格式不正确!</div>";}
			return false;
		}
	}
	return false;
}


//============身份证===================

function checkIdcard(idcard){   
  var Errors=new Array("验证通过!","身份证号码位数不对","身份证号码出生日期超出范围或含有非法字符","身份证号码校验错误","身份证地区非法");   
  var area={11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏",33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"}   
  var idcard,Y,JYM;   
  var S,M;   
  var idcard_array = new Array();   
  idcard_array = idcard.split("");   
  if(area[parseInt(idcard.substr(0,2))]==null) return Errors[4];   
  switch(idcard.length){   
    case 15:   
      if ((parseInt(idcard.substr(6,2))+1900) % 4 == 0 || ((parseInt(idcard.substr(6,2))+1900) % 100 == 0 && (parseInt(idcard.substr(6,2))+1900) % 4 == 0 )){   
        ereg = /^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}$/;
      }   
      else{   
        ereg = /^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}$/;
      }   
      if(ereg.test(idcard))   
        return Errors[0];   
      else  
        return Errors[2];   
    break;   
  case 18:   
    if ( parseInt(idcard.substr(6,4)) % 4 == 0 || (parseInt(idcard.substr(6,4)) % 100 == 0 && parseInt(idcard.substr(6,4))%4 == 0 )){   
      ereg = /^[1-9][0-9]{5}19[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}[0-9Xx]$/;  
    }   
    else{   
    ereg = /^[1-9][0-9]{5}19[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}[0-9Xx]$/;
    }   
    if(ereg.test(idcard)){   
      S = (parseInt(idcard_array[0]) + parseInt(idcard_array[10])) * 7 + (parseInt(idcard_array[1]) + parseInt(idcard_array[11])) * 9 + (parseInt(idcard_array[2]) + parseInt(idcard_array[12])) * 10 + (parseInt(idcard_array[3]) + parseInt(idcard_array[13])) * 5 + (parseInt(idcard_array[4]) + parseInt(idcard_array[14])) * 8 + (parseInt(idcard_array[5]) + parseInt(idcard_array[15])) * 4 + (parseInt(idcard_array[6]) + parseInt(idcard_array[16])) * 2 + parseInt(idcard_array[7]) * 1 + parseInt(idcard_array[8]) * 6 + parseInt(idcard_array[9]) * 3 ;   
      Y = S % 11;   
      M = "F";   
      JYM = "10X98765432";   
      M = JYM.substr(Y,1);   
      if(M == idcard_array[17])   
        return Errors[0];   
      else  
        return Errors[3];   
    }   
    else  
      return Errors[2];   
    break;   
  default:   
    return Errors[1];   
    break;   
  }   
}
function ChkCode(str){
	var Code = document.getElementById(str).value;
	var Card = document.getElementById(str+'1');
	if(checkIdcard(Code) == "身份证号码位数不对"){
		Card.innerHTML = "<div class='msg_error'> 身份证号码位数不对</div>";
		return false;
	}
	if(checkIdcard(Code) == "身份证号码出生日期超出范围或含有非法字符"){
		Card.innerHTML = "<div class='msg_error'> 身份证号码出生日期超出范围或含有非法字符</div>";
		return false;
	}
	if(checkIdcard(Code) == "身份证号码校验错误"){
		Card.innerHTML = "<div class='msg_error'> 身份证号码校验错误</div>";
		return false;  
	}
	if(checkIdcard(Code) == "身份证地区非法"){
		Card.innerHTML = "<div class='msg_error'> 身份证地区非法</div>";
		return false;  
	}
	Card.innerHTML = "<div class='msg_ok'></div>";
}

function notice() {
	//alert(arguments[0]);
	document.getElementById(arguments[0]).style.display = arguments[1];
}