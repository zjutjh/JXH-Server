@extends('jxh.base')

@section('title', '')


@section('content')
    <div class="bind-container" id="app">
        <form action="" class="bind-form">
            <div class="form-head">
                <img src="{{ asset('images/title.png') }}" alt="" class="form-title">
                <img src="{{ asset('images/logo-red.png') }}" alt="" class="form-logo">
            </div>

            <div class="success-content">
                @if (isset($content))
                    {{ $content }}
                @else
                    你已经绑定服务号
                @endif

            </div>

        </form>


        <div class="back-logo">
            <img src="{{ asset('images/logo-left-top.png') }}" alt="" class="back-left-top">
            <img src="{{ asset('images/logo-bottom.png') }}" alt="" class="back-bottom">
            <img src="{{ asset('images/logo-bottom-right.png') }}" alt="" class="back-bottom-right">
        </div>
    </div>
@endsection