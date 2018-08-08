@extends('jxh.base')

@section('title', '通知详情')

@section('content')

    <div class="show-container">
        <div class="show-head">
            <div class="head-left">
                <img src="{{ asset('images/radius.png') }}" alt="" class="radius-1">
                <img src="{{ asset('images/radius.png') }}" alt="" class="radius-2">
                <img src="{{ asset('images/radius.png') }}" alt="" class="radius-3">
            </div>
            <div src="" class="head-right">
                <img src="{{ asset('images/radius-y.png') }}" alt="" class="radius-1">
                <img src="{{ asset('images/radius-y.png') }}" alt="" class="radius-2">
                <img src="{{ asset('images/radius-y.png') }}" alt="" class="radius-3">
                <img src="{{ asset('images/radius-y.png') }}" alt="" class="radius-4">
            </div>
        </div>
        <div class="content-container content-container-show">
            <div class="msg-container">
                <h1 class="show-title">{{ $message->title }}</h1>

                <div class="show-inform">
                    <p><span>{{ $message->informer }}</span>&nbsp;&nbsp;&nbsp;{{ $message->created_at->format('Y-m-d H:i:s') }} &nbsp;&nbsp;&nbsp; 阅读量: {{ $message->view }}</p>
                </div>
                <div class="">
                    {!! $message->content !!}
                </div>

            </div>

        </div>
        <div class="show-footer">
            <img src="{{ asset('images/footer-left.png') }}" alt="" class="footer-left">
            <div class="footer-right">
                <div class="radius-1"></div>
                <div class="radius-2"></div>
            </div>
        </div>
    </div>
    <script>
        wx.ready(function(){
            // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
            wx.onMenuShareTimeline({
                title: '{{ $message->title }}', // 分享标题
                link: document.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '{{ $message->cover_image }}', // 分享图标
                success: function () {
                    // 用户点击了分享后执行的回调函数
                }
            });

            wx.onMenuShareAppMessage({
                title: '{{ $message->title }}', // 分享标题
                desc: '{{ trim_words($message->content, 100) }}', // 分享描述
                link: document.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '{{ $message->cover_image }}', // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
// 用户点击了分享后执行的回调函数
                }
            });
        });

    </script>
@endsection