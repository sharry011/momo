@extends('layouts/admin_layout')

@section('title', 'الأنواع')


@section('content')
    <div class="first_title m-3">
        <h3>الأنواع</h3>
    </div>

    <div class="cont">
        <div class="details">

            <form class="w-100" action="{{route('admin.types.new.post')}}" method="post">
                @csrf
                <input type="text" class="form-control mb-3" id="name" name="name" placeholder="إسم النوع" required>
                <button class="btn btn-success float-left mb-3 w-100">إضافة نوع جديد</button>
            </form>

            <table class="details table table-striped table-hover">
                <thead>
                <tr>
                    <th scope="col">العنوان</th>
                    <th scope="col" class="text-center">الأوامر</th>
                </tr>
                </thead>
                <tbody>
                @foreach($types as $type)
                    <tr>
                        <form action="{{route('admin.types.update.post', ['id' => $type->id])}}" method="post"
                              id="update_type">
                            @csrf
                            <td class="d-flex">
                                <input type="text" class="form-control" name="name" value="{{$type->name}}" required/>
                                <button class='btn btn-success me-2' title="تعديل">تحديث</button>
                            </td>
                        </form>
                        <td class="text-center">
                            <form
                                class="post_actions d-flex flex-row flex-nowrap justify-content-center align-items-center delete_type"
                                action="{{route('admin.types.delete.post', ['id' => $type->id])}}" method="post">
                                @csrf
                                <button class="btn btn-sm btn-danger btn-delete" title="حذف" type="submit">
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
            console.log('ready')
            $('.delete_type').on('submit', function (e) {
                e.preventDefault();
                console.log('delete')
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
