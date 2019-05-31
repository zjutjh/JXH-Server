<?php

namespace App\Http\Controllers;

use App\Services\FaceMergeServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic;

class GdByzController extends Controller
{

    public function __construct()
    {

    }


    public function oauth(Request $request) {
        $oauth = app('wechat')->oauth->setRequest($request)->redirect(url('jxh/byz'));
        return $oauth;
    }


    public function getZjz(Request $request) {
//        $zjz = $request->file('zjz');
        $image = new ImageManager(array('driver' => 'imagick'));
        $img = $image->make(file_get_contents('https://static.zjutjh.com/jxh/female.jpg'));


        $img->text(to_unicode("朱兴照"), 480, 965, function($font) {
            $font->file(Storage::get('public/font.ttf'));
            $font->size(75);
        });
        return $img->response('jpg');
    }


}
