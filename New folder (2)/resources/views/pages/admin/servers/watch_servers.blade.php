@extends('layouts/admin_layout')

@section('title', 'سبرفرات المشاهدة')

@section('head')
    <style>
        .dd-handle {
            height: auto;
        }
    </style>
@endsection

@section('content')
    <div class="first_title m-3">
        <h3>سبرفرات المشاهدة</h3>
    </div>

    <div class="cont">
        <div class="row m-3">
            <div class="col-12 col-lg-4 p-2">

                <form class="details" action="{{route('admin.watch_servers.new.post')}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">عنوان السيرفر :</label>
                        <input type="text" class="form-control" name="name" placeholder="عنوان السيرفر" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">ايقونة السيرفر :</label>
                        <input type="file" class="form-control" name="img" placeholder="ايقونة السيرفر" required>
                    </div>
                    <div class="mb-3">
                        <label for="" text="form-label">ترتيب الظهور :</label>
                        <input type="number" class="form-control" name="rank" placeholder="ترتيب الظهور" required>
                    </div>
                    <hr/>
                    <div>
                        <div class="form-check">
                            <label for="normal_server" class="form-label">سيرفر عادي :</label>
                            <input id="normal_server" class="form-check-input" name="type" type="radio" value="1"
                                   checked>
                        </div>
                        <div class="form-check">
                            <label for="variable_server" class="form-label">سيرفر متغير :</label>
                            <input id="variable_server" class="form-check-input" name="type" type="radio" value="2">
                        </div>
                    </div>
                    <hr/>
                    <div class="mb-3">
                        <label for="" class="form-label">الجزء المقصوص :</label>
                        <textarea class="form-control" name="remove" placeholder="الجزء المقصوص"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">الجزء المضاف :</label>
                        <textarea class="form-control" name="add" placeholder="الجزء المضاف"></textarea>
                    </div>
                    <div class="mb-3 w-100">
                        <button class="btn btn-success w-100">اضافة</button>
                    </div>
                </form>

            </div>
            <div class="col-12 col-lg-8 p-2 dd">
                <form class="details" id="servers_order" action="{{route('admin.watch_servers.update.post')}}"
                      method="post">
                    <div class="w-100 text-start">
                        <button class="btn btn-primary " type="submit">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>تحديث</span>
                        </button>
                    </div>
                    <hr/>
                    <ol class="dd-list">
                        @foreach($servers as $server)
                            <li class="dd-item" data-id="{{$server->id}}">
                                <button class="delete-item"><i class="fa-solid fa-trash"></i></button>
                                <div class="dd-handle">
                                    <code>{{$server->name}}</code>
                                    <div class="d-flex"> حذف : &nbsp;
                                        <div class="ltr">{{$server->remove}}</div>
                                    </div>
                                    <div class="d-flex"> اضافة : &nbsp;
                                        <div class="ltr">{{$server->add}}</div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {

            $(document).on('click', '.delete-item', function () {
                var listItem = $(this).closest('.dd-item');
                listItem.remove();
                console.log('delete')
            });

            // Initialize Nestable
            $('.dd').nestable({
                maxDepth: 1
            });

            $('.dd').on('change', function () {
                var data = $('.dd').nestable('serialize');
            });

            $('#servers_order').on('submit', function (e) {
                e.preventDefault();
                $('#admin_preloader').removeClass('loaded');
                var data = $('.dd').nestable('serialize');
                $.ajax({
                    url: '{{route('admin.watch_servers.update.post')}}',
                    type: 'post',
                    data: {
                        _token: '{{csrf_token()}}',
                        data: data
                    },
                    complete: function (data) {
                        location.reload();
                    }
                });
            });

        });
    </script>

@endsection
