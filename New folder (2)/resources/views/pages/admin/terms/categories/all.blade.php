@extends('layouts/admin_layout')

@section('title', 'الأقسام')


@section('content')
    <div class="first_title m-3">
        <h3>الأقسام</h3>
    </div>

    <div class="cont">
        <div class="details">

            <div class="w-100">
                <a href="{{route('admin.categories.new.get')}}" class="btn btn-success float-left mb-3 w-100">إضافة قسم
                    جديد</a>
            </div>

            <table class="details table table-striped table-hover">
                <thead>
                <tr>
                    <th scope="col">العنوان</th>
                    <th scope="col" class="text-center">الأوامر</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($categories as $post)
                    <tr>
                        <td>
                            {{ $post->name }}
                        </td>
                        <td class="text-center">
                            <form class="deleteCateg" action="{{route('admin.categories.delete.post', ['id' => $post->id])}}" method="post"
                                  class="post_actions d-flex flex-row flex-nowrap justify-content-center">
                                @csrf
                                <a href="{{route('admin.categories.update.get', ['id' => $post->id])}}"
                                   class="btn btn-sm btn-warning br-0"
                                   title="تعديل">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button class="btn btn-sm btn-danger br-0" title="حذف" type="submit">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>


    <script>
        $(document).ready(function () {
            $('.deleteCateg').on('submit', function (e) {
                e.preventDefault();
                swal({
                    title: "هل أنت متأكد من الحذف؟",
                    text: "بعد الحذف لن تستطيع استرجاع البيانات!",
                    icon: "warning",
                    buttons: ["إلغاء", "حذف"],
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            this.submit();
                        }
                    });
            })
        });
    </script>
@endsection
