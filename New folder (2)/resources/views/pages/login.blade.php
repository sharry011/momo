@extends('layouts/main_layout')

@section('title', 'تسجيل الدخول - ' . $settings->site_name)

@section('remove_meta', true)

@section('content')
    <style>
        .form {
            display: flex;
            justify-content: center;
            background-color: var(--color-gray1);
            margin-top: 20px;
            padding: 20px;
            color: white;
            font-weight: bold;
            border-radius: 10px;
        }

        .cont {
            width: 400px;
            max-width: 90%;
        }
    </style>

    <div class="container">
        <form action="{{route('try_login')}}" method="post" class="form">
            @csrf
            <div class="container d-flex justify-content-center">
                <form class="form" method="post" action="">
                    <div class="cont">
                        @if($errors->any())
                            @foreach($errors->all() as $error)
                                <h5 class="text-center text-danger mb-4">{{$error}}</h5>
                            @endforeach
                        @endif
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="email" aria-describedby="emailHelp" required
                                   name="email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة السر</label>
                            <input type="password" class="form-control" id="password" required name="password">
                        </div>
                        <button type="submit" class="btn btn-main fw-bold">تسجيل الدخول</button>
                    </div>
                </form>
            </div>
        </form>
    </div>
@endsection

