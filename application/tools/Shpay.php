<?php
namespace app\service\controller;

class Shpay
{
    //生产环境地址
    const URL = 'https://showmoney.cn/scanpay/unified';
    static $mchntid = '250451053110001';//商户号
    static $inscd = '92501888';//  //机构号
    static $terminalid = 'CLCPAY';//终端号
    static $key = 'xxxxxxxxxxxxxxxxxxxxxxxx';

    static $conf = [
        'version'   =>  '2.1',
        'signType'  =>  'SHA256',
        'charset'   =>  'utf-8',
        'backUrl'   =>  'http://pay.clcbec.cn/nofity',
        'frontUrl'  =>  'http://pay.clcbec.cn/front'
    ];

    /**
     *	预下单
     *
     * @param unknown $input
     * @return bool
     */
    public function createOrder($orderNum,$needPay,$attach='') {

        $postData['attach'] = $attach;//附加数据原样返回
        $postData['orderNum'] = $orderNum;
        $postData['txamt'] = str_pad( ( $needPay*100 ), 12, "0", STR_PAD_LEFT );

        $postData['txndir'] = "Q";
        $postData['busicd'] = 'PAUT';//预下单
        $postData['chcd'] = 'ALP';//ALP：支付宝，WXP：微信

        $postData['backUrl'] = static::$conf['backUrl'];
        //$postData['frontUrl'] = static::$conf['frontUrl'];
        $postData['version'] = static::$conf['version'];
        $postData['signType'] = static::$conf['signType'];
        $postData['charset'] = static::$conf['charset'];
        $postData['terminalid'] = static::$terminalid;//终端号
        $postData['mchntid'] = static::$mchntid;//商户号
        $postData['inscd'] = static::$inscd;//机构号
        $postData['sign'] = self::sign($postData);
        $reqPar =json_encode($postData);
        $res = self::curlPost(self::URL, $reqPar);
        if (self::checkSign(json_decode($res,true))) {
            return $res;
        }else{
            return 'error';
        }
    }

    /**
     *	H5支付接口
     *
     * @param unknown $input
     * @return bool
     */
    public function createOrder_H5($orderNum,$needPay,$attach='') {

        $postData['attach']=$attach;//附加数据原样返回
        $postData['orderNum']=$orderNum;
        $postData['txamt']=str_pad( ( $needPay*100 ), 12, "0", STR_PAD_LEFT );

        //$postData['txndir']="Q";
        $postData['busicd']='WPAY';//H5支付
        $postData['chcd']='ALP';//ALP：支付宝，WXP：微信

        $postData['backUrl']=static::$conf['backUrl'];
        $postData['frontUrl']=static::$conf['frontUrl'];
        $postData['version']=static::$conf['version'];
        $postData['signType']=static::$conf['signType'];
        $postData['charset']=static::$conf['charset'];
        $postData['terminalid']=static::$terminalid;//终端号
        $postData['mchntid']=static::$mchntid;//商户号
        $postData['sign'] = self::sign($postData);
        $reqPar =json_encode($postData);
        $requestURL = self::URL. "?data=".base64_encode($reqPar);
        return $requestURL;
    }

    /**
     * 功能：订单状态查询
     *
     * @param unknown $orderId
     * @param unknown $time
     * @return bool
     */
    public function queryOrder( $orderNum) {
        $postData['origOrderNum']=$orderNum;

        $postData['txndir']="Q";
        $postData['busicd']='INQY';//查询交易填写：INQY

        $postData['version']=static::$conf['version'];
        $postData['signType']=static::$conf['signType'];
        $postData['charset']=static::$conf['charset'];
        $postData['terminalid']=static::$terminalid;//终端号
        $postData['mchntid']=static::$mchntid;//商户号
        $postData['inscd']=static::$inscd;//机构号
        $postData['sign'] = self::sign($postData);
        $reqPar =json_encode($postData);
        $res = self::curlPost(self::URL, $reqPar);
        return json_decode($res);
    }

    final static protected function crateData($method,$biz_content){
        if (is_array($biz_content)&& count($biz_content)>2) {

        }else{
            return self::setError( '原始数据错误' );
        }
        ksort($biz_content);
        $retruData = [
            'biz_content' =>json_encode($biz_content,JSON_UNESCAPED_UNICODE),
            'timestamp'=> date("Y-m-d H:i:s"),
            'method'=>$method,
            'agency_no'=>static::$agency_no,
            'version' =>'1.0',
        ];
        ksort($retruData);
        $sign = self::sign($retruData);
        $retruData['sign']=$sign;
        //print_r($retruData);
        //echo self::SinParamsToString($retruData);
        //exit;
        return $retruData;
    }

    final static protected function sign( &$data ) {
        $signPars = "";
        ksort($data);
        foreach($data as $k => $v) {
            if("" != $v && "sign" != $k) {
                $signPars .= $k . "=" . $v . "&";
            }
        }
        $signPars = substr($signPars, 0, strlen($signPars)-1);
        $signPars .= static::$key;
        $sign=hash('sha256', $signPars, true);
        $sign=bin2hex($sign);
        return $sign;
    }

    /**
     * 功能： 签名验证
     *
     * @param unknown $data
     * @return bool
     */
    final static protected function checkSign( $data ) {
        $orgSign = $data['sign'];
        //却掉签名后生成签名
        unset( $data['signature'] );
        //$data['transTime'] =  str_replace(' ', '+',$data['transTime']);
        $sign =  self::sign( $data );
        if ( $orgSign === $sign ) {
            return true;
        }
        return false;
    }

    /**
     * 功能： 模拟POST请求
     *
     * @param unknown $url
     * @param null    $data
     * @return bool|mixed
     */
    final static public function curlPost( $url, $data = null ) {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        //curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml; charset=utf-8"));
        if ( !empty( $data ) ) {
            curl_setopt( $ch, CURLOPT_POST, 1 );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
        }
        //curl_setopt($ch,CURLOPT_SSLVERSION,CURL_SSLVERSION_TLSv1);
        curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
        $outPut = curl_exec( $ch );
        $aStatus = curl_getinfo( $ch );
        curl_close( $ch );
        if ( intval( $aStatus['http_code'] )==200 ) {
            return $outPut;
        }else {
            return false;
        }
    }
}