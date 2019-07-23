<?php
/**
 * User: wangzt
 * Date: 2019/3/21
 */

namespace Sai\Swoole;


class Weather
{
    public static function getInfoLikeName($cityName)
    {
        $city = Db::getInstance()->get('city_code', 'adcode', [
            "city_name[~]" => $cityName
        ]);

        if (empty($city)) {
            return '没有查询到数据';
        }

        try {
            return self::getInfo($city);
        } catch (\Throwable $t) {
            return $t->getMessage();
        }
    }

    public static function getInfo($cityCode, $ext = 'all')
    {

        $redis = Redis::getInstance();
        if ($ret = $redis->get($cityCode)) {
            return $ret;
        }

        $url = 'https://restapi.amap.com/v3/weather/weatherInfo';
        $url = $url.'?'.http_build_query(['key' => getenv('WEATHER_KEY'), 'city' => $cityCode, 'extensions' => $ext]);
        $data = file_get_contents($url);

        if (empty($data)) {
            throw new \Exception('没有查到数据！---');
        }

        $list = \json_decode($data, true);

        $ret = "根据{$list['forecasts'][0]['reporttime']}播报：{$list['forecasts'][0]['province']}-{$list['forecasts'][0]['city']}";

        foreach ($list['forecasts'][0]['casts'] as $value) {
            $ret .= "\r\n".substr($value['date'], 5)."(".self::getWeekly($value['week']).")白天{$value['dayweather']}晚上{$value['nightweather']},气温{$value['nighttemp']}-{$value['daytemp']}摄氏度,风力{$value['daypower']}";
        }

        $redis->setex($cityCode, 7200, $ret);

        return $ret;
    }

    private static function getWeekly($week)
    {
        $arr = ["日","一","二","三","四","五","六", "日"];
        return '星期'.$arr[$week];
    }
}