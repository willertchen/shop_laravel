<!-- 指定繼承 layout.master 母模板 -->
@extends('layout.master')

<!-- 傳送資料到母模板，並指定變數為 title -->
@section('title', $title)

<!-- 傳送資料到母模板，並指定變數為 content -->
@section('content')
    <div class="contain">
        <h1>{{ $title }}</h1>

        <!-- 錯誤訊息模板元件 components.validationErrorMessage -->
        @include('components.validationErrorMessage')

        <form action="/user/auth/sign-in" method="post">
            <label>
                Email:
                <input type="text"
                       name="email"
                       placeholder="Email"
                >
            </label>

            <label>
                密碼：
                <input type="password"
                       name="password"
                       placeholder="密碼"
                >
            </label>

            <button type="submit">登入</button>

            <!-- CSRF 欄位 -->
            {!! csrf_field() !!}
        </form>
    </div>
@endsection
