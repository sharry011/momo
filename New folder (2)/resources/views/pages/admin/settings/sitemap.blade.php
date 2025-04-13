@extends('layouts/admin_layout')

@section('title', 'خريطة الموقع')


@section('content')
    <div class="first_title m-3">
        <h3>خريطة الموقع</h3>
    </div>

    <div class="cont">
        <div class="details m-2">
            <form action="" method="post">
                @csrf
                <div class="form-group mb-3">
                    <label for="sitemap">الرابط القديم</label>
                    <input type="text" class="form-control ltr" name="old_url" required>
                </div>
                <div class="form-group mb-3">
                    <label for="sitemap">الرابط الجديد</label>
                    <input type="text" class="form-control ltr" name="new_url" required>
                </div>
                <button type="submit" class="btn btn-primary">تحديث</button>
            </form>
        </div>
    </div>

@endsection
