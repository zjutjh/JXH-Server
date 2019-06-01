<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <title>精小弘服务号 @yield('title')</title>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    <script>
{{--        wx.config({!! app('wechat')->jssdk->buildConfig(array('onMenuShareTimeline', 'onMenuShareAppMessage'), false) !!})--}}
    </script>

    <!-- rem 正比例缩放 -->
    <script>!function (a, b, c) {
            function q() {
                var d = Math.min((o ? e[h]().width : f.innerWidth) / (a / b), c);
                d != p && (k.innerHTML = "html{font-size:" + d + "px!important;" + n + "}", p = d)
            }

            function r() {
                clearTimeout(l), l = setTimeout(q, 500)
            }

            var l, d = document, e = d.documentElement, f = window, g = "addEventListener", h = "getBoundingClientRect",
                i = "pageshow", j = d.head || d.getElementsByTagName("HEAD")[0], k = d.createElement("STYLE"),
                m = "text-size-adjust:100%;", n = "-webkit-" + m + "-moz-" + m + "-ms-" + m + "-o-" + m + m, o = h in e,
                p = null;
            a = a || 320, b = b || 16, c = c || 32, j.appendChild(k), d[g]("DOMContentLoaded", q, !1), "on" + i in f ? f[g](i, function (a) {
                a.persisted && r()
            }, !1) : f[g]("load", r, !1), f[g]("resize", r, !1), q()

        }(
            375, 10, 16);</script>
    <!-- /rem -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/element-ui@2.0.3/lib/theme-chalk/index.css">
    <link rel="stylesheet" href="https://unpkg.com/cube-ui/lib/cube.min.css">
    <link rel="stylesheet" href="{{ asset('css/byz.css') }}">
</head>
<body>

<div class="app" id="app">
    @yield('content')
</div>

<script src="//static.zjutjh.com/jxh/app.js"></script>
<script src="https://unpkg.com/cube-ui/lib/cube.min.js"></script>
@yield('script')

</body>
</html>