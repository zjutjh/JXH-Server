@extends('gdbyz.base')

@section('title', '毕业了')


@section('content')
    <div class="byz-container">
        <div class="byz-show">
            <div class="byz-text">
                <img src="https://static.zjutjh.com/jxh/byz-text.png" alt="">
            </div>
            
            <div class="show" style="background-image: url({{ $img }}); background-size: cover">

            </div>
        </div>
        @include('gdbyz.footer')
    </div>


@endsection