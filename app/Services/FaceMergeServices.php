<?php
/**
 * Created by PhpStorm.
 * User: zhutianyu
 * Date: 2019-05-28
 * Time: 17:07
 */

namespace App\Services;


use GuzzleHttp\Client;

class FaceMergeServices
{
    // face merge url
    const FACEMERGEURL = 'https://api-cn.faceplusplus.com/imagepp/v1/mergeface';

    const MERGERATE = 90;


    // app key
    private $appKey = '';

    // app secret
    private $appSecret = '';


    // http client
    private $client;


    public function __construct(string $appKey, string $appSecret)
    {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
        $this->client = new Client();
    }

    /**
     * 获取融合人脸头像
     */
    private function getMergeFace($merge_url, $template_url)
    {
        return $this->postFace($merge_url, $template_url);

    }


    /**
     * 获取融合毕业证
     */
    public function getByz($img, $sex)
    {
        if ($sex === '男') {
            $url = 'https://static.zjutjh.com/jxh/man.jpg';
        } else {
            $url = 'https://static.zjutjh.com/jxh/female.jpg';
        }

        return $this->getMergeFace($img, $url);
    }


    /**
     * post data to face++
     */
    private function postFace($merge_url, $template_url)
    {


        $res = $this->client->post(self::FACEMERGEURL, [
            'multipart' => [
                [
                    'name' => 'api_key',
                    'contents' => $this->appKey
                ],
                [
                    'name' => 'api_secret',
                    'contents' => $this->appSecret
                ],
                [
                    'name' => 'template_url',
                    'contents' => $template_url
                ],
                [
                    'name' => 'merge_url',
                    'contents' => $merge_url
                ],
                [
                    'name' => 'merge_rate',
                    'contents' => self::MERGERATE
                ],
//                [
//                    'api_key' => $this->appKey,
//                    'api_secret' => $this->appSecret,
//                    'template_url' => $template_url,
////                'template_rectangle' => 'abc',
//                    'merge_url' => $merge_url,
//                    'merge_rate' => self::MERGERATE,
//                ]
            ]]);

        $value = json_decode($res->getBody(), true);
        return $value['result'];

    }


}