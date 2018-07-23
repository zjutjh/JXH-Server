@extends('jxh.base')

@section('title', '个人页面')


@section('content')
    <div class="bind-container" id="app">
        <form action="" class="bind-form">
            <div class="form-head">
                <img src="{{ asset('images/title.png') }}" alt="" class="form-title">
                <img src="{{ asset('images/logo-red.png') }}" alt="" class="form-logo">
            </div>

            <div class="success-content">
                你已经绑定精小弘
            </div>

            <div class="form-button" @click="bind">
                切换绑定
            </div>

            @if($user->allow_send)
                <div class="form-button" @click="modal=!modal">
                    取消通知
                </div>
            @else
                <div class="form-button" @click="modal=!modal">
                    接受通知
                </div>
            @endif

        </form>

        @if($user->allow_send)
        <div class="modal" v-show="modal" style="display: none;">
            <div class="confirm">
                <div class="title">提示</div>
                <p>确认取消学校消息通知</p>
                <div class="cfm-btn">
                    <div class="left-btn" @click="sure">确认</div>
                    <div class="right-btn" @click="modal=!modal">取消</div>
                </div>
            </div>
        </div>
        @else
            <div class="modal" v-show="modal" style="display: none;">
                <div class="confirm">
                    <div class="title">提示</div>
                    <p>你是否确认接受学校消息通知</p>
                    <div class="cfm-btn">
                        <div class="left-btn" @click="sure">确认</div>
                        <div class="right-btn" @click="modal=!modal">取消</div>
                    </div>
                </div>
            </div>
        @endif


        <div class="back-logo">
            <img src="{{ asset('images/logo-left-top.png') }}" alt="" class="back-left-top">
            <img src="{{ asset('images/logo-bottom.png') }}" alt="" class="back-bottom">
            <img src="{{ asset('images/logo-bottom-right.png') }}" alt="" class="back-bottom-right">
        </div>
    </div>

    <script src="//cdn.jsdelivr.net/npm/vue@2.5.3"></script>
    <script src="//cdn.jsdelivr.net/npm/vue-resource@1.3.4"></script>
    <!-- 引入组件库 -->
    <script src="//cdn.jsdelivr.net/npm/element-ui@2.0.3/lib/index.js"></script>

    <script>
        var app = new Vue({
            el: '#app',
            data: {
                username: '{{ $user->sid }}',
                passwd: '',
                modal: false,
                help: false
            },
            methods: {
                bind: function () {
                    window.location.href = '/user/change'
                },
                sure: function () {
                    var _this = this
                    @if($user->allow_send)
                    _this.$http.get('/user/send/cancel').then(function (res) {
                        const result = res.body
                        if (result.code < 0) {
                            _this.modal = ! _this.modal
                            return
                        }
                        this.$message({
                            type: 'success',
                            message: '确认成功!'
                        });
                        _this.modal = !_this.modal
                    })
                    @else
                    _this.$http.post('/user/agree', { username: _this.username}).then(function (res) {
                        const result = res.body
                        if (result.code < 0) {
                            _this.modal = ! _this.modal
                            return
                        }
                        this.$message({
                            type: 'success',
                            message: '确认成功!'
                        });
                    })
                    @endif
                },
                cancel: function () {
                    this.modal = !this.modal
                }
            }

        })
    </script>
@endsection