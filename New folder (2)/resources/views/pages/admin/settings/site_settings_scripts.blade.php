@extends('layouts/admin_layout')

@section('title', 'إعدادات اكواد الموقع')

@section('content')
    <div class="first_title m-3">
        <h3>إعدادات اكواد الموقع</h3>
    </div>

    <form method="post" action="" class="row details m-3">
        @csrf
        <div class="col-12">
            <div class="mb-3">
                <label class="form-label">اكواد فى وسم head :</label>
                <textarea class="form-control ltr" placeholder="اكواد فى وسم head" rows="8" name="head_scripts">{{$site_settings_scripts['head']}}</textarea>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label class="form-label">اكواد فى وسم fotter :</label>
                <textarea class="form-control ltr" placeholder="اكواد فى وسم fotter" rows="8" name="fotter_scripts">{{$site_settings_scripts['fotter']}}</textarea>
            </div>
        </div>
        <div>
            <button class="btn btn-success">تحديث</button>
        </div>
    </form>

    <script>
        $('form').on('submit', function () {
            $('#admin_preloader').removeClass('loaded');
        });
    </script>
@endsection
