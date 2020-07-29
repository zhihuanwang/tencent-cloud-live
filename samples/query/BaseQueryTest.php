<?php
/**
 * Created by PhpStorm.
 * User: LXZ
 * Date: 2020/7/29
 * Time: 14:09
 */
namespace Test;
//require_once __DIR__ . '/../../vendor/autoload.php';
use function Composer\Autoload\includeFile;
use Helper\Framework\JsonResult;
use Tencent\Live\Query;

require_once __DIR__ . '/../../src/Bootstrap.php';
require_once __DIR__ . '/../../src/tencent/live/config.php';


class BaseQueryTest
{
    public function demo()
    {
        $config =include __DIR__ . '/../../src/tencent/live/config.php';
        $query = new Query($config);
        $url = $query->getPushUrl('12');
        $pull_url = $query->getPlayUrl('12');
        $stream_status = $query->queryStreamStatus('stream');
        JsonResult::ReturnAjax('200', '', $stream_status);
    }

}
