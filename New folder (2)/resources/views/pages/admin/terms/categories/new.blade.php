@extends('layouts/admin_layout')

@section('title', 'اضافة قسم')


@section('content')
    <div class="first_title m-3">
        <h3>اضافة قسم</h3>
    </div>

    <div class="cont">
        <div class="details">
            <form class="row" action="" method="post">
                @csrf
                <div class="col-12 mb-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="عنوان القسم" value="">
                </div>
                <div class="col-12 mb-3">
                    <textarea class="form-control" id="desc" name="desc" rows="3" placeholder="وصف القسم"></textarea>
                </div>
                <div class="w-100">
                    <button type="submit" class="btn btn-success w-100">اضافة</button>
                </div>
            </form>
        </div>
    </div>

@endsection
