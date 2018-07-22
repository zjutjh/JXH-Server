@extends('jxh.base')

@section('title', '绑定服务号')


@section('content')
    <div class="bind-container" id="app">

        <form action="" class="bind-form">
            <div class="form-head">
                <img src="{{ asset('images/title.png') }}" alt="" class="form-title">
                <img src="{{ asset('images/logo-red.png') }}" alt="" class="form-logo">
            </div>
            
            <div class="form-item">
                <label for="" class="item-title">账号</label>
                <input v-model="username" type="text" class="item-input" placeholder="请输入精弘通行证/学号">
            </div>
            
            <div class="form-item">
                <label for="" class="item-title">密码</label>
                <input v-model="passwd" type="password" class="item-input" placeholder="请输入密码">
            </div>

            <div class="form-help" @click="help=!help">

                <img src="{{ asset('images/trouble.png') }}" alt="" class="help-logo">
                <div class="help-title">帮助</div>
            </div>

            <div class="form-button"  @click="bind">
                确认绑定
            </div>

        </form>
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

        <div class="modal" v-show="help" style="display: none;">
            <div class="confirm help-content">
                <div class="title">提示</div>
                <p class="help-content">绑定前请先进行精弘账号激活，进入精小弘在线—账号激活—精弘账号激活，本科生、研究生、0+4辅导员请使用学号激活，在编教职工请使用五位工号激活</p>
                <div class="cfm-btn">
                    <div class="right-btn" @click="help=!help">取消</div>
                </div>
            </div>
        </div>

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
                loading: false,
                username: '',
                passwd: '',
                modal: false,
                help: false
            },
            methods: {
                bind: function () {
                    var _this = this
                    this.loading = true
                    if (!_this.username || !_this.passwd) {
                        return _this.$message.error('请输入你的精弘通行证或密码')
                    }
                    _this.$http.post('/wechat/login', {
                        username: _this.username,
                        passwd: _this.passwd,
                    }).then(function (response) {
                        const result = response.body
                        if (result.code < 0) {
                            return _this.$message({
                                showClose: true,
                                message: result.msg || '发生了一点错误',
                                type: 'warning'
                            })
                        }
                        if (result.code === 100) {
                            window.location.href = '{{ url('success') }}'
                            return
                        }

                        _this.modal = !_this.modal
                    }, function () {
                        _this.$message.error('好像发生了一点错误')
                        _this.loading = false
                    })
                },
                sure: function() {
                    var _this =this
                    _this.$http.post('/user/agree', { username: _this.username}).then(function (res) {
                        const result = res.body
                        if (result.code < 0) {
                            window.close();
                            WeixinJSBridge.call('closeWindow');
                            return
                        }
                        this.$message({
                            type: 'success',
                            message: '确认成功!'
                        });
                        window.location.href = '{{ url('success') }}'
                    })
                },
                cancel: function() {
                    this.modal = !this.modal
                    window.location.href = '{{ url('success') }}'

                }
            }

        })
    </script>
@endsection