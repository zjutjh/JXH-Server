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
        wx.onMenuShareTimeline({
            title: '{{ $message->title }}', // 分享标题
            link: document.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '{{ $message->cover_image }}', // 分享图标
            success: function () {
                // 用户点击了分享后执行的回调函数
            }
        })
    </script>
@endsection