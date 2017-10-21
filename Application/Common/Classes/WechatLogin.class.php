<?php
namespace Common\Classes;

/**
 * 微信自动登录
 * 首先进行静默授权登录,若登录失败则进行让用户手动授权登录
 *
 * @author shixu
 *        
 */
class WechatLogin
{

    private $app_id = 'wxc8895b0ee2314534';

    private $app_secret = '4f21b189caee0086a391bb87c771fb82';

    private $code;

    /**
     *
     * @param string $type
     *               $type 主要用于区分是来自哪里步之后，比如是授权后还是第一次授权还是用户手动授权后等
     */
    public function login($type = '')
    {
        if ($type == 'snsapi_base' && $this->code = I('get.code')) { // 静默授权后传回code
            $result_data = $this->getAccessToken();
            $access_token = $result_data->access_token;
            $openid = $result_data->openid;
            if ($access_token && $openid) {
                return $this->getUserInfo($access_token, $openid);
            } else { // 如果没有获取到说明用户未关注公众号或其他原因获取失败，则进行让用户手动授权页
                $result_data = $this->snsapiUserinfo();
            }
        } elseif ($type == 'snsapi_ueserinfo' && $this->code = I('get.code')) { // 用户手动授权返回后
            $result_data = $this->getAccessToken();
            $access_token = $result_data->access_token;
            $openid = $result_data->openid;
            if ($access_token && $openid) {
                return $this->getUserInfo($access_token, $openid);
                ;
            } else { // 获取token失败
                return '获取token失败';
            }
        } else {
            // 首先进行静默授权
            $this->snsapiBase();
        }
    }

    /**
     * 静默授权(已关注情况下不需要进入用户授权页，自动授权获取)
     */
    private function snsapiBase()
    {
        $snsapi_base_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->app_id}&redirect_uri=" . urlencode(U('', array(), '', true) . '?wechat_type=snsapi_base') . "&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
        header('Location:' . $snsapi_base_url);
    }

    /**
     * 跳转到用户手动授权页
     */
    private function snsapiUserinfo()
    {
        $snsapi_ueserinfo_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->app_id}&redirect_uri=" . urlencode(U('', array(), '', true) . '?wechat_type=snsapi_ueserinfo') . "&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        header('Location:' . $snsapi_ueserinfo_url);
    }

    /**
     * 获取access_token
     */
    private function getAccessToken()
    {
        $get_access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->app_id}&secret={$this->app_secret}&code={$this->code}&grant_type=authorization_code";
        $result_data = json_decode(file_get_contents($get_access_token_url));
        return $result_data;
    }

    /**
     * 获取用户信息
     */
    private function getUserInfo($access_token, $openid)
    {
        $userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        $userinfo = json_decode(file_get_contents($userinfo_url));
        return $userinfo;
    }
}