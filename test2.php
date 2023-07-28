<?php
require_once('vendor/autoload.php'); // 只针对使用 composer 安装
// require_once '/path/to/php-sdk/vendor/autoload.php'; // 针对压缩包安装

use Upyun\Config;
use Upyun\Upyun;

$serviceConfig = new Config('web100x', 'web100x', 'NpkX1ChtNw3fc3Aanyuhf2JK38v0aCvl');
$client = new Upyun($serviceConfig);

$file = fopen('https://p3-sign.toutiaoimg.com/tos-cn-i-qvj2lq49k0/2dfd0a5363684a47bbbb27128ebbbf22~noop.image?_iz=58558&from=article.pc_detail&x-expires=1688976148&x-signature=Ox%2BfzJyor%2Frdzqd5Vf880KQb750%3D', 'r');

dd($client->write('/test/upload/test.jpg', $file));

$start = null;
$total = 0;
do {
    $list = $client->read('/test', null, array(
        'X-List-Limit' => 100,
        'X-List-Iter' => $start,
    ));
    if (is_array($list['files'])) {
        foreach ($list['files'] as $file) {
            $total++;
            if ($file['type'] === 'N') {
                echo '文件名: ';
            } else {
                echo '目录名: ';
            }
            echo $file['name'];
            echo ' 大小:' . $file['size'];
            echo ' 修改时间:' . date('Y-m-d H:i:s', $file['time']);
            echo "\n";
        }
    }
    $start = $list['iter'];
} while (!$list['is_end']);

echo '总共存有文件 ' . $total . ' 个';