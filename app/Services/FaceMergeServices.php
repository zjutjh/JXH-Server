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

    const MERGERATE = 50;


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
    private function getMergeFace()
    {

    }


    /**
     * 获取融合毕业证
     */
    public function getByz()
    {

    }


    /**
     * post data to face++
     */
    private function postFace()
    {


        $res = $this->client->post(self::FACEMERGEURL, [
            'multipart' => [
                'api_key' => $this->appKey,
                'api_secret' => $this->appSecret,
                'template_file' => 'abc',
                'template_rectangle' => 'abc',
                'merge_file' => 'abc',
                'merge_rate' => self::MERGERATE,
            ]]);

    }


}