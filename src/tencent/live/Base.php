<?php
/**
 * Created by PhpStorm.
 * User: LXZ
 * Date: 2020/7/29
 * Time: 10:45
 */
namespace Tencent\Live;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Live\V20180801\LiveClient;
class Base
{
    # AppID
    protected $appid = '1251504224';
    # API鉴权key
    protected $api_key = '46f9560a25622c8b88f9aabf5e52e09a';
    # 推流防盗链key
    protected $push_key = '46f9560a25622c8b88f9aabf5e52e09a';
    # 腾讯云分配 bizid
    public $bizid = '77850';
    # 接口url
    protected $url = 'https://live.tencentcloudapi.com';
    # 统计URL
    protected $stat_url = '';
    # 视频直播过期时间
    public $expire = 2 * 3600;
    protected $time = 0;

    protected $secretId;
    protected $secretKey;
    protected $region;
    protected $client;
    /**
     * 构造方法
     * Base constructor.
     * @param $config
     */
//    public function __construct($appid, $api_key, $push_key, $bizid, $url = '', $stat_url = '')
    public function __construct($config)
    {
        $this->_initialize();
        $this->appid = $config['app_id'];
        $this->api_key = $config['api_key'];
        $this->push_key = $config['push_key'];
        $this->bizid = $config['bizid'];
        $this->url = $config['url'] == '' ? 'http://fcgi.video.qcloud.com/common_access' : $config['url'];
        $this->stat_url = $config['stat_url'] == '' ? 'http://statcgi.video.qcloud.com/common_access' : $config['stat_url'];
        $this->secretId = $config['secretId'];
        $this->secretKey = $config['secretKey'];
        $this->region = $config['region'];
        $cred = new Credential($this->secretId, $this->secretKey);
        $httpProfile = new HttpProfile();
        $httpProfile->setEndpoint("live.tencentcloudapi.com");

        $clientProfile = new ClientProfile();
        $clientProfile->setHttpProfile($httpProfile);
        $this->client = new LiveClient($cred, $this->region, $clientProfile);
        return $this;
    }

    /**
     * 获取腾讯云直播SDK client对象
     * @return LiveClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * 初始化方法
     */
    protected function _initialize()
    {

    }
    /**
     *  获取签名
     */
    public function getSign()
    {
        $this->time = time();
        return md5($this->api_key.$this->time);
    }

}
