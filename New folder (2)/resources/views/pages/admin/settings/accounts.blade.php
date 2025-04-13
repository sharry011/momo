@extends('layouts/admin_layout')

@section('title', 'الحسابات')


@section('content')
    <div class="first_title m-3">
        <h3>الحسابات</h3>
    </div>

    <div class="cont">
        <div class="w-100 mb-3">
            <button class="w-1002 btn btn-primary" data-bs-toggle="modal" data-bs-target="#newUserModal">اضافة حساب
                جديد
            </button>
        </div>
        <table class="details table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th scope="col">الاسم</th>
                <th scope="col">الإيميل</th>
                <th scope="col" class="text-center">الأوامر</th>
            </tr>
            </thead>
            <tbody>

            @foreach ($users as $account)
                <tr>
                    <td>
                        {{ $account->name }}
                    </td>
                    <td>
                        {{ $account->email }}
                    </td>

                    <td class="text-center">
                        <div class="post_actions d-flex flex-row flex-nowrap justify-content-center">
                            <button class="btn btn-sm btn-warning br-0 edit_btn" title="تعديل"
                                    data-value="{{ $account->id }}"><i class="fa-solid fa-pen-to-square"></i></button>
                            <form action="" method="post" class="delete_user">
                                @csrf
                                <input type="hidden" name="id" value="{{ $account->id }}"/>
                                <button class="btn btn-sm btn-danger br-0" title="حذف" type="submit">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>

                </tr>
            @endforeach

            </tbody>
        </table>
    </div>

    <!--edit user modal-->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
         data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered2">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editUserModalLabel">تعديل بيانات الحساب</h1>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="update_info">
                        <div class="mb-3">
                            <label for="" class="form-label">الإسم :</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="الإسم" required/>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">البريد الإلكتروني :</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   placeholder="البريد الإلكتروني" required/>
                        </div>
                        <input hidden id="id_user_info" name="id"/>

                        <div class="w-100">
                            <button type="submit" class="btn btn-primary w-100">تعديل البيانات</button>
                        </div>
                    </form>
                    <hr/>
                    <form action="" method="post" id="update_password">
                        <div class="mb-3">
                            <label for="" class="form-label">كلمة السر :</label>
                            <input type="password" class="form-control" id="password1" name="password"
                                   placeholder="كلمة السر "/>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">اعادة كلمة السر :</label>
                            <input type="password" class="form-control" id="password2" placeholder="اعادة كلمة السر"/>
                        </div>
                        <input hidden id="id_user_password" name="id"/>

                        <div class="w-100">
                            <button type="submit" class="btn btn-primary w-100">تعديل كلمة السر</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <!--new user modal-->
    <div class="modal fade" id="newUserModal" tabindex="-1" aria-labelledby="newUserModalLabel"
         data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered2">
            <form class="modal-content" action="" method="post" id="new_account">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="newUserModalLabel">حساب جديد</h1>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <div class="mb-3">
                            <label for="" class="form-label">الإسم :</label>
                            <input type="text" class="form-control" id="new_name" name="new_name" placeholder="الإسم"
                                   required/>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">البريد الإلكتروني :</label>
                            <input type="email" class="form-control" id="new_email" name="new_email"
                                   placeholder="البريد الإلكتروني" required/>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">كلمة السر :</label>
                            <input type="password" class="form-control" id="new_password1" name="new_password"
                                   placeholder="كلمة السر " required/>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">اعادة كلمة السر :</label>
                            <input type="password" class="form-control" id="new_password2" placeholder="اعادة كلمة السر"
                                   required/>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                    <button type="submit" class="btn btn-primary">اضافة</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const accounts = @json($users)

        $(document).ready(function () {

            $('.edit_btn').click(function () {
                let id = $(this).data('value')
                let account = accounts.find(account => account.id == id)
                let modal = $('#editUserModal')

                modal.find('#name').val(account.name)
                modal.find('#email').val(account.email)
                modal.find('#id_account').val(account.id)
                modal.find('#id_user_info').val(account.id)
                modal.find('#id_user_password').val(account.id)

                modal.modal('show')

            })

            $('#editUserModal').on('hidden.bs.modal', function (e) {
                $(this).find('#name').val('')
                $(this).find('#email').val('')
                $(this).find('#password1').val('')
                $(this).find('#password2').val('')
                $(this).find('#id_account').val('')
            })

            $('#update_info').on('submit', function (e) {
                e.preventDefault()
                let data = $(this).serializeArray();
                data.push({name: '_token', value: '{{csrf_token()}}'});
                $('#admin_preloader').removeClass('loaded');
                $.ajax({
                    url: '{{route('admin.accounts.update_info.post')}}',
                    type: 'POST',
                    data: data,
                    success: function (data) {
                        location.reload()
                    },
                    error: function (err) {
                        $('#admin_preloader').addClass('loaded');
                        if (err.responseJSON.error && err.responseJSON.error == 'email_taken') {
                            swal({
                                icon: 'error',
                                title: 'لا يمكن استخدام هذا البريد الإلكتروني',
                                text: 'جرب بريد إلكتروني آخر',
                                button: 'حسناً'
                            })
                        }
                    }
                })
            })

            $('#update_password').on('submit', function (e) {
                e.preventDefault()

                let password1 = $(this).find('#password1').val()
                let password2 = $(this).find('#password2').val()

                if (password1 !== password2) {
                    swal({
                        icon: 'error',
                        title: 'كلمة السر غير متطابقة',
                        text: 'كلمة السر غير متطابقة',
                        button: 'حسناً'
                    })
                    return
                }

                let data = $(this).serializeArray();
                data.push({name: '_token', value: '{{csrf_token()}}'});
                $('#admin_preloader').removeClass('loaded');
                $.ajax({
                    url: '{{route('admin.accounts.update_password.post')}}',
                    type: 'POST',
                    data: data,
                    success: function (data) {
                        location.reload()
                    },
                    error: function (err) {
                        $('#admin_preloader').addClass('loaded');
                        console.log(err)
                    }
                })
            })

            $('#new_account').on('submit', function (e) {
                e.preventDefault()
                console.log('here')

                let password1 = $(this).find('#new_password1').val()
                let password2 = $(this).find('#new_password2').val()

                if (password1 !== password2) {
                    swal({
                        icon: 'error',
                        title: 'كلمة السر غير متطابقة',
                        text: 'كلمة السر غير متطابقة',
                        button: 'حسناً'
                    })
                    return
                }

                let data = $(this).serializeArray();
                data.push({name: '_token', value: '{{csrf_token()}}'});
                $('#admin_preloader').removeClass('loaded');
                $.ajax({
                    url: '{{route('admin.accounts.new.post')}}',
                    type: 'POST',
                    data: data,
                    success: function (data) {
                        location.reload()
                    },
                    error: function (err) {
                        $('#admin_preloader').addClass('loaded');
                        console.log(err)
                        if (err.responseJSON.error && err.responseJSON.error == 'email_taken') {
                            swal({
                                icon: 'error',
                                title: 'لا يمكن استخدام هذا البريد الإلكتروني',
                                text: 'جرب بريد إلكتروني آخر',
                                button: 'حسناً'
                            })
                        }
                    }
                })
            })

            $('.delete_user').on('submit', function (e) {
                e.preventDefault()
                let data = $(this).serializeArray();
                data.push({name: '_token', value: '{{csrf_token()}}'});
                $('#admin_preloader').removeClass('loaded');
                swal({
                    title: "هل أنت متأكد من الحذف؟",
                    text: "هل تريد حذف هذا الحساب !!!",
                    icon: "warning",
                    buttons: ["إلغاء", "حذف"],
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: '{{route('admin.accounts.delete.post')}}',
                                type: 'POST',
                                data: data,
                                success: function (data) {
                                    location.reload()
                                },
                                error: function (err) {
                                    $('#admin_preloader').addClass('loaded');
                                    console.log(err)
                                }
                            })
                        }
                    });
            })
        })
    </script>

@endsection
