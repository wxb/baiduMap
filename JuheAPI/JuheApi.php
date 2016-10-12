<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 16-10-12
 * Time: 上午11:37
 */

namespace Common\Lib;

/**
 * 聚合数据 API 请求类
 * HELP: https://www.juhe.cn/docs
 * Class JuheApi
 * @package Common\Lib
 */
class JuheApi
{
    const TIME_OUT   = 10;   // 请求超时时间
    const MOBLIE_API = 'http://apis.juhe.cn/mobile/get';   // 手机号码归属地 API 请求地址
    private $apikey;

    public function __construct($key=null)
    {
        if(empty($key)){
            throw new \Exception('未指定APIKEY！ [聚合数据API]');
        }else{
            $this->apikey = $key;
        }
    }

    /**
     * 根据手机号码或手机号码的前7位，查询手机号码归属地信息，包括省份 、城市、区号、邮编、运营商和卡类型
     * Help info - https://www.juhe.cn/docs/api/id/11
     * @param $phone
     * @return string
     * @throws \Exception
     * @author wangxb
     * @date   2016-10-12
     */
    public function getPhonePosition($phone)
    {
        $params = array(  // 请求参数
            'phone' => $phone,
            'key'   => $this->apikey
        );
        $url = sprintf('%s?%s', self::MOBLIE_API, http_build_query($params));
        $response = \Requests::get($url, '', array('timeout'=>self::TIME_OUT));
        if(!$response->success) throw new \Exception('获取手机归属地HTTP请求失败 [聚合数据API]');
        $data = json_decode($response->body, true);
        // 系统级错误 抛出异常
        if(1 == substr($data['error_code'],0,1)) throw new \Exception($data['reason'].'[聚合数据API]');
        return $data['result'];
    }
}
