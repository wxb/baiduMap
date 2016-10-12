<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 16-10-12
 * Time: 上午11:36
 */

namespace Common\Lib;

/**
 * 百度APIStore 请求类
 * From: Baidu APIStore
 * Help: http://apistore.baidu.com
 * Class BaiduApi
 * @package Common\Lib
 */
class BaiduApi
{
    const TIME_OUT   = 10;   // 请求超时时间
    const BAIDU_API  = 'http://apis.baidu.com'; // 百度API请求地址
    private $apikey;

    public function __construct($key=null)
    {
        if(empty($key)){
            throw new \Exception('未指定APIKEY！ [百度APIStore]');
        }else{
            $this->apikey = $key;
        }
    }

    /**
     * 获取手机号码归属地信息
     * author: wangxb
     * date: 2016-10-12
     * From: Baidu API - 百度手机号码归属地 开发API
     * Help: http://apistore.baidu.com/apiworks/servicedetail/794.html
     * @param $phone
     * @return string
     * @throws \Exception
     */
    public function getPhonePosition($phone)
    {
        $headers = array(
            'apikey' => $this->apikey
        );
        $params = array(  // 请求参数
            'phone' => $phone,
        );
        $phone_api = '/apistore/mobilenumber/mobilenumber';   // 手机号归属地api
        $url = sprintf('%s%s?%s', self::BAIDU_API, $phone_api, http_build_query($params));
        $response = \Requests::get($url, $headers, array('timeout'=>self::TIME_OUT));
        if(!$response->success) throw new \Exception('获取手机归属地HTTP请求失败 [百度APIStore]');
        $data = json_decode($response->body, true);
        if((0 != $data['errNum'])) throw new \Exception($data['errMsg'].'[百度APIStore]');
        return $data['retData'];
    }

}
