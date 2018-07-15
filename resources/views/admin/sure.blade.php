@extends('jxh.base')

@section('title', '确认发送')


@section('content')
    <div class="bind-container" id="app">
        <form action="" class="bind-form">
            <div class="form-head">
                <img src="{{ asset('images/title.png') }}" alt="" class="form-title">
                <img src="{{ asset('images/logo-red.png') }}" alt="" class="form-logo">
            </div>

            <div class="success-content">
                    确定发送
            </div>
            <div class="form-button"  @click="agree">
                同意发送
            </div>
            <div class="form-button"  @click="cancel">
                不同意发送
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
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                id: '{{ $hashid }}'
            },
            methods: {
                agree: function () {
                    var _this = this
                    _this.$http.get('/message/agree/' + _this.id).then(function (response) {
                        window.location.href = '{{ url('message/info') }}'
                    }, function () {

                    })
                },
                cancel: function() {
                    var _this =this
                    _this.$http.get('/message/agree/cancel/' + _this.id).then(function (res) {
                        window.location.href = '{{ url('message/info') }}'
                    })
                },

            }

        })
    </script>
@endsection