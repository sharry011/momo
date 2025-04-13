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
                    <input type="text" class="form-control" id="name" name="name" placeholder="عنوان القسم" value="{{$category->name}}">
                </div>
                <div class="col-12 mb-3">
                    <input type="text" class="form-control" id="slug" name="slug" placeholder="رابط القسم" value="{{$category->slug}}">
                </div>
                <div class="col-12 mb-3">
                    <textarea class="form-control" id="desc" name="desc" rows="3" placeholder="وصف القسم">{{$category->desc}}</textarea>
                </div>
                <input type="hidden" name="id" value="{{$category->id}}">
                <div class="w-100">
                    <button type="submit" class="btn btn-success w-100">تحديث</button>
                </div>
            </form>
        </div>
    </div>

@endsection
