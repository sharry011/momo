@extends('layouts/admin_layout')

@section('title', 'إعدادات مواقع التواصل الإجتماعي')


@section('content')
    <div class="first_title m-3">
        <h3>إعدادات مواقع التواصل الإجتماعي</h3>
    </div>

    <div class="cont">
        <div class="row m-1">
            <div class="col-12 col-lg-4 p-2">
                <form class="details" action="{{route('admin.social_media_new.post')}}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">الإسم</label>
                        <input type="text" class="form-control" placeholder="الإسم" name="name" required/>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">الرابط</label>
                        <input type="text" class="form-control ltr" placeholder="الرابط" name="link" value="#"/>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">الأيقونة</label>
                        <input type="text" class="form-control" placeholder="الأيقونة" name="icon" value=""/>
                        <div id="" class="form-text">
                            الأيقونة من موقع <a target="_blank" href="https://fontawesome.com/search?o=r&m=free">fontawesome</a>
                            <br/>
                            مثال: <code>&lt;i class="fab fa-facebook-f"&gt;&lt;/i&gt;</code>
                        </div>
                    </div>
                    <div class="w-100">
                        <button class="w-100 btn btn-success">اضافة</button>
                    </div>
                </form>
            </div>

            <div class="col-12 col-lg-8 p-2 ">
                <form class="details" action="{{route('admin.social_media_update.post')}}" method="post">
                    @csrf
                    @foreach($social_media as $sm)
                        <div class="row align-items-stretch">
                            <div class="col-12 col-md-6 mb-2">
                                <div class="form-control d-flex justify-content-between align-items-center">
                                    <h5 class="m-0">{!! $sm->icon !!} {{ $sm->name }}</h5>
                                    <button type="button" class="btn delete_sm p-0" data-value="{{$sm->id}}"><i
                                            class="fa-solid fa-trash text-danger"></i></button>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 mb-2">
                                <input type="text" class="form-control ltr h-100"
                                       placeholder="رابط الصفحة على {!! $sm->name !!}"
                                       value="{!! $sm->link !!}" name="sm_{!! $sm->id !!}"/>
                            </div>
                        </div>
                        <hr/>
                    @endforeach
                    <div class="w-100">
                        <button class="w-100 btn btn-success">حفظ</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        $('.delete_sm').click(function(){
            $('#admin_preloader').removeClass('loaded');
            const id = $(this).data('value')
            const formData = new FormData();
            formData.append('id', id);
            formData.append('_token', '{{csrf_token()}}');
            $.post({
                url: '{{route('admin.social_media_delete.post')}}',
                data: formData,
                processData: false,
                contentType: false,
                complete: function() {
                    window.location.reload();
                }
            })
        })
    </script>

@endsection
