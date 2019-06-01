@extends('gdbyz.base')

@section('title', '等待')



@section('content')
    <div class="byz-container">
        <div class="loading">
            <div class="loading-title">
                <img src="https://static.zjutjh.com/jxh/loading-title.png" alt="">
            </div>

            <div class="loading-ani">
                <span class="ani-circle"></span>
                <span class="ani-circle"></span>
                <span class="ani-circle"></span>
                <span class="ani-circle"></span>
                <span class="ani-circle"></span>
                <span class="ani-circle"></span>

            </div>

            <div class="loading-text">
                <img src="https://static.zjutjh.com/jxh/loading-text.png" alt="">
                
            </div>

        </div>
        @include('gdbyz.footer')
    </div>
@endsection