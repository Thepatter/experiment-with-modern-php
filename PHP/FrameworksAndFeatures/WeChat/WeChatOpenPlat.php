<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018/10/11
 * Time: 15:28
 */

class WeChatOpenPlat
{
    /** @var string 粉丝对比 redis chan */
    const WxAccountsFansComparedRedisChan = 'wxAccountsFansComparedChan';
    /** @var string 微信账户粉丝对比授权码缓存 key 前缀*/
    const WxAccountsFansComparedAuthorizationCacheKeyPrefix = 'WxAccountsFansComparedAuthorizationCode';
    /** @var string 用户粉丝对比结果缓存 key 前缀 */
    const WxAccountsFansComparedResultCacheKeyPrefix = 'WxAccountsFansComparedResult';
    /** @var string 用户粉丝对比结果文件缓存文件名 */
    const WxAccountsFansComparedResultFileName = 'WeChatFansComparedResult.txt';
    /** @var array 参数错误响应 */
    private static $paramsErrorResponse = [
        'code' => 400,
        'message' => '参数错误',
    ];
    /** @var array 访问权限错误 */
    private static $permissionDenied = [
        'code' => 401,
        'message' => '未授权或授权失败'
    ];
    /** @var array 微信账户非微信认证账户时响应 */
    private static $weChatAccountsNotVerify = [
        'code' => 403,
        'message' => '该账户非微信认证账户,无法进行粉丝获取,请先进行微信认证'
    ];
    /**
     * 获取微信授权url
     * @return mixed
     */
    public function authUrl()
    {
        if (is_null(VerifyUser::getUserIdEncrypt($_GET['token']))) {
            return Response::resJson(self::$permissionDenied);
        }
        return Response::resJson((new WeChat())->getAuthURI());
    }
    /**
     * 微信授权回调到前端后前端提交授权码接口，使用授权码获取公众号的接口调用凭据和授权信息
     */
    public function wxTokenAndPermission()
    {
        $userIdEncrypt = VerifyUser::getUserIdEncrypt($_GET['token']);
        if (is_null($userIdEncrypt)) {
            return Response::resJson(self::$permissionDenied);
        }
        if (empty($_GET['auth_code']) || empty($_GET['expires_in'])) {
            return Response::resJson(self::$paramsErrorResponse);
        }
        $wechat = new WeChat;
        $wxAccountsAuthorization = $wechat->getWxAuthTokenAndPermission($_GET['auth_code']);
        if (in_array(2, array_column(array_column($wxAccountsAuthorization['authorization_info']['func_info'], "funcscope_category"), "id")) === false) {
            return Response::resJson([
                'code' => 403,
                'message' => '该账户未授予用户管理权限,无法进行粉丝对比,请重新授予用户管理权限'
            ]);
        }
        $accounts_info = $wechat->getWxAuthorizationAccountsInfo($wxAccountsAuthorization['authorization_info']['authorizer_appid']);
        if ($accounts_info[0]['verify_type_info']['id'] != 0) {
            return Response::resJson(self::$weChatAccountsNotVerify);
        }
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $authInfoCacheKey = static::WxAccountsFansComparedAuthorizationCacheKeyPrefix . $userIdEncrypt . dechex(time());
        $wxAccountsAuthorization['authorization_info']['accounts_info'] = $accounts_info;
        $redis->set($authInfoCacheKey, json_encode($wxAccountsAuthorization));
        $redis->close();
        return Response::resJson(['accountInfo' => $wxAccountsAuthorization['authorization_info']['accounts_info'], 'authCacheKey' => $authInfoCacheKey]);
    }
    /**
     * 开始粉丝对比
     * @return mixed
     */
    public function beginWxAccountsFans()
    {
        $userIdEncrypt = VerifyUser::getUserIdEncrypt($_GET['token']);
        if (is_null($userIdEncrypt)) {
            return Response::resJson(self::$permissionDenied);
        }
        if (empty($_GET['comparedCacheA']) || empty($_GET['comparedCacheB'])) {
            return Response::resJson(self::$paramsErrorResponse);
        }
        $redis = new \Redis;
        $redis->connect('127.0.0.1');
        $comparedAccountsA = json_decode($redis->get($_GET['comparedCacheA']), true);
        $comparedAccountsB = json_decode($redis->get($_GET['comparedCacheB']), true);
        $comparedResultKey = static::WxAccountsFansComparedResultCacheKeyPrefix . $userIdEncrypt . dechex(time());
        $comparedResultJson = json_encode([
            'comparedAccounts' => [
                'accountsA' => [
                    'head_img' => $comparedAccountsA['authorization_info']['accounts_info'][0]['head_img'],
                    'nick_name' => $comparedAccountsA['authorization_info']['accounts_info'][0]['nick_name'],
                ],
                'accountsB' => [
                    'head_img' => $comparedAccountsB['authorization_info']['accounts_info'][0]['head_img'],
                    'nick_name' => $comparedAccountsB['authorization_info']['accounts_info'][0]['nick_name'],
                ],
            ],
            'publishTime' => date('Y-m-d H:i:s'),
            'status' => '进行中',
            'result' => [],
        ]);
        $redis->set($comparedResultKey, $comparedResultJson);
        file_put_contents(static::WxAccountsFansComparedResultFileName, $comparedResultKey . '.' . $comparedResultJson . PHP_EOL, FILE_APPEND);
        $redis->publish(self::WxAccountsFansComparedRedisChan, json_encode([
            'comparedAccountsA' => [
                'authorizer_appid' => $comparedAccountsA['authorization_info']['authorizer_appid'],
                'authorizer_access_token' => $comparedAccountsA['authorization_info']['authorizer_access_token'],
                'expires_in' => $comparedAccountsA['authorization_info']['expires_in'],
                'authorizer_refresh_token' => $comparedAccountsA['authorization_info']['authorizer_refresh_token'],
            ],
            'comparedAccountsB' => [
                'authorizer_appid' => $comparedAccountsB['authorization_info']['authorizer_appid'],
                'authorizer_access_token' => $comparedAccountsB['authorization_info']['authorizer_access_token'],
                'expires_in' => $comparedAccountsB['authorization_info']['expires_in'],
                'authorizer_refresh_token' => $comparedAccountsB['authorization_info']['authorizer_refresh_token'],
            ],
            'comparedResultKey' => $comparedResultKey,
            'authorizationCacheKey' => [$_GET['comparedCacheA'], $_GET['comparedCacheB']],
        ]));
        $redis->close();
        return Response::resJson(['code' => 200, 'data' => '提交成功，已开始进行粉丝重复对比，请留意进度查询里对比结果']);
    }
    /**
     * 获取授权公众号信息
     * @return mixed
     */
    public function getWxAccountsInfo()
    {
        $info = (new Wechat)->getWxAuthorizationAccountsInfo($_GET['authorizer_appid']);
        return Response::resJson($info);
    }
    /**
     * 粉丝对比进展
     */
    public function fansComparedSchedule()
    {
        $userIdEncrypt = VerifyUser::getUserIdEncrypt($_GET['token']);
        if (is_null($userIdEncrypt)) {
            return Response::resJson(self::$permissionDenied);
        }
        $redis = new \Redis;
        $redis->connect('127.0.0.1');
        $userAccountsFansComparedCacheList = $redis->keys(self::WxAccountsFansComparedResultCacheKeyPrefix . $userIdEncrypt . '*');
        $userAccountsFansComparedResult = [];
        if (!empty($userAccountsFansComparedCacheList)) {
            foreach ($userAccountsFansComparedCacheList as $userAccountsFansComparedCacheKey) {
                $userAccountsFansComparedResult[] = json_decode($redis->get($userAccountsFansComparedCacheKey), true);
            }
        } else {
            $fansComparedResultHandler = fopen(self::WxAccountsFansComparedResultFileName, 'r');
            $pattern = self::WxAccountsFansComparedResultCacheKeyPrefix . $userIdEncrypt;
            while ($buff = fgets($fansComparedResultHandler)) {
                if (preg_match("/^$pattern/", $buff)) {
                    $userAccountsFansComparedResult[] = json_decode(explode('.', $buff)[1], true);
                }
            }
            fclose($fansComparedResultHandler);
        }
        return Response::resJson($userAccountsFansComparedResult);
    }
    /**
     * 微信开放平台接入,接收微信推送 componentVerifyTicket
     * @return mixed
     */
    public function auth()
    {
        $wxPostXml = simplexml_load_string(file_get_contents('php://input'));
        $wxPostXmlToArray = [];
        foreach ($wxPostXml as $value) {
            $wxPostXmlToArray[$value->getName()] = trim((string) $value);
        }
        $wx = new WeChat();
        $signParams = ['timestamp' => $_GET['timestamp'], 'nonce' => $_GET['nonce'], $wxPostXmlToArray['Encrypt']];
        if ($wx->verifyWeChatMsg($signParams, $_GET['msg_signature']) === true) {
            $decryptMsg = $wx->decryptMsg($wxPostXmlToArray['Encrypt']);
            file_put_contents(WeChat::wxComponentVerifyTicketFile, $decryptMsg['ComponentVerifyTicket']);
        }
        return Response::resPlain('success');
    }
    /** 全网发布测试 */
    public function weChatTest()
    {
        $wxPostXml = simplexml_load_string(file_get_contents('php://input'));
        $wxPostXmlToArray = [];
        foreach ($wxPostXml as $value) {
            $wxPostXmlToArray[$value->getName()] = trim((string) $value);
        }
        $wx = new WeChat();
        $signParams = ['timestamp' => $_GET['timestamp'], 'nonce' => $_GET['nonce'], $wxPostXmlToArray['Encrypt']];
        if ($wx->verifyWeChatMsg($signParams, $_GET['msg_signature']) === false) {
            return Response::resPlain('request error');
        }
        $decryptMsg = $wx->decryptMsg($wxPostXmlToArray['Encrypt']);
        if (isset($wxPostXmlToArray['AppId'])) {
            /** 微信推送 event */
            if (isset($decryptMsg['ComponentVerifyTicket'])) {
                file_put_contents(WeChat::wxComponentVerifyTicketFile, $decryptMsg['ComponentVerifyTicket']);
            }
            return Response::resPlain('success');
        }
        /** 微信全网发布测试 */
        if (isset($wxPostXmlToArray['ToUserName']) && $wxPostXmlToArray['ToUserName'] === WeChat::wxTestUsername) {
            $arrayToXml = function (array $data) {
                if (!is_array($data) || count($data) <= 0) {
                    return false;
                }
                $xml = "<xml>";
                foreach ($data as $key=>$val){
                    if (is_numeric($val)){
                        $xml .= "<".$key.">".$val."</".$key.">";
                    }else{
                        $xml .= "<".$key."><![CDATA[".$val."]]></".$key.">";
                    }
                }
                $xml .= "</xml>";
                return $xml;
            };
            if (strtolower($decryptMsg['MsgType']) === 'text' && $decryptMsg['Content'] === WeChat::wxTestPushTextContent) {
                $decryptMsg['Content'] = WeChat::wxTestResponseTextContent;
                $encryptMsg = $wx->encryptMsg($arrayToXml($decryptMsg));
                $encryptMsgSignature = $wx->generateEncryptMsgSignature($encryptMsg, $_GET['nonce'], $_GET['timestamp']);
                $responseMsg = [
                    'Encrypt' => $encryptMsg,
                    'MsgSignature' => $encryptMsgSignature,
                    'TimeStamp' => $_GET['timestamp'],
                    'Nonce' => $_GET['nonce'],
                ];
                return Response::resPlain($arrayToXml($responseMsg));
            }
        }
    }
}