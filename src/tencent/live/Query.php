<?php
/**
 * Created by PhpStorm.
 * User: LXZ
 * Date: 2020/7/29
 * Time: 10:53
 */

namespace Tencent\Live;


use Helper\Framework\JsonResult;
use TencentCloud\Live\V20180801\Models\DescribeLiveStreamStateRequest;

class Query extends Base
{
    protected $tencentLive;

    public function __construct($config)
    {
//        parent::__construct();
        $this->tencentLive = new Base($config);
        $this->client = $this->tencentLive->getClient();
    }
    /**
     * 获取直播推流地址
     * @param $channel_id
     * @param null $expire
     * @return string
     */
    public function getPushUrl($channel_id, $expire = null)
    {
        if ($expire == null) {
            $time = date('Y-m-d H:i:s', time() + $this->expire);
        }else {
            $time = date('Y-m-d H:i:s', time() + $expire);
        }
        $tx_time = strtoupper(base_convert(strtotime($time), 10, 16));
        $live_code = $this->bizid . '_' . $channel_id;
        $tx_secret = md5($this->push_key . $live_code. $tx_time);
        $query = '?' . http_build_query([
                'bizid' => $this->bizid,
                'txSecret' => $tx_secret,
                'txTime' => $tx_time
            ]);
        return 'rtmp://'. $this->bizid . '.livepush.myqcloud.com/live/' . $live_code . $query;
    }

    /**
     * 获取播放地址
     * @param $channel_id
     * @return array
     */
    public function getPlayUrl($channel_id)
    {
        $livecode = $this->bizid . '_' . $channel_id; //直播码
        return [
            'rtmp' => "rtmp://pull.live.zdzxwx.com/live/".$livecode,
            'flv' => "http://pull.live.zdzxwx.com/live/".$livecode.".flv",
            'm3u8' => "http://pull.live.zdzxwx.com/live/".$livecode.".m3u8"
        ];
    }

    public function queryStreamStatus($streamName)
    {
        $req = new DescribeLiveStreamStateRequest();
        $temp = (object)[
            'AppName' => '77850',
            'DomainName' => '77850.livepush.myqcloud.com',
            'StreamName' => $streamName
        ];
        $params = json_encode($temp, JSON_UNESCAPED_UNICODE);
        $req->fromJsonString($params);
        $resp = $this->client->DescribeLiveStreamState($req);
        return $resp;
    }
}
