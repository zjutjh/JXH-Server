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

            <div class="form-help">

            </div>

            <div class="form-button"  @click="bind">
                确认绑定
            </div>

        </form>





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
                passwd: ''
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

                        _this.$confirm('你是否确认接受学校消息通知', '提示', {
                            confirmButtonText: '确定',
                            cancelButtonText: '取消',
                            type: 'warning'
                        }).then(function(res) {
                            _this.$http.post('/user/agree').then(function (res) {
                                const result = res.body
                                if (result.code < 0) {
                                  return window.close()
                                }
                                this.$message({
                                    type: 'success',
                                    message: '确认成功!'
                                });
                                window.close()

                            })
                        }).catch(function() {

                        })
                    }, function () {
                        _this.$message.error('好像发生了一点错误')
                        _this.loading = false
                    })
                }
            }
        })
    </script>
@endsection