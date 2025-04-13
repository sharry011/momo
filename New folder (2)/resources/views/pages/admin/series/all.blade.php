@extends('layouts/admin_layout')

@section('title', 'كل المسلسلات')

@section('content')
    <div class="first_title m-3">
        <h3>كل المسلسلات</h3>
    </div>
    <div class="cont">
        <div class="head">
            <h4>كل المقالات</h4>
            <div class="row ">
                <div class="col-12">
                    <form class="input-group mb-3 ltr" onsubmit="search(event)">
                        <button class="btn btn-outline-secondary" type="submit" id="button-addon1">بحث</button>
                        <input id="search_input" value="{{request()->query('s')}}" type="text" class="form-control rtl"
                               placeholder="ابحث عن مقال" aria-label="Example text with button addon"
                               aria-describedby="button-addon1" v-model="s">
                    </form>
                </div>
            </div>
        </div>
        <div class="table">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">الصورة</th>
                    <th scope="col">العنوان</th>
                    <th scope="col">القسم</th>
                    <th scope="col">بتاريخ</th>
                    <th scope="col">الأوامر</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($series as $post)
                    <tr>
                        <th><img src="{{ $post->img }}" class="post_img"/></th>
                        <td>
                            {{ $post->title }}
                        </td>
                        <td>
                            {{ $post->categories->count() > 0 ? $post->categories[0]->name : 'بدون قسم' }}
                        </td>
                        <td>
                            {{ $post->created_at }}
                        </td>
                        <td>
                            <div class="post_actions d-flex flex-row flex-nowrap">
                                <a href="{{route('admin.seasons.copy_from_serie.get', ['id' => $post->id])}}"
                                   class="btn btn-sm btn-primary br-0" title="نسخ الى موسم"><i
                                        class="fa-solid fa-photo-film"></i>
                                </a>
                                <a href="{{route('admin.series.copy.get', ['id' => $post->id])}}"
                                   class="btn btn-sm btn-success br-0"
                                   title="نسخ"><i class="fa-solid fa-copy"></i>
                                </a>
                                <a href="{{route('admin.series.update.get', ['id' => $post->id])}}"
                                   class="btn btn-sm btn-warning br-0"
                                   title="تعديل"><i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form method="post" class="form_delete"
                                      action="{{route('admin.series.delete', ['page' => request()->route('page'), 's' => request()->query('s')])}}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$post->id}}">
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
            <nav aria-label="Page navigation" class="d-flex justify-content-center">
                <ul class="pagination">
                    @if (intval($pagination['currentPage']) > 1)
                        <li class="page-item">
                            <button class="page-link cursor-pointer" type="button" aria-label="Page Button"
                                    onclick="updateQuery('page', {{ intval($pagination['currentPage']) - 1 }})">
                                <i class="fa-solid fa-forward"></i>
                            </button>
                        </li>
                    @endif

                    @if (intval($pagination['currentPage']) > 1)
                        <li class="page-item">
                            <button class="page-link cursor-pointer" type="button" aria-label="Page Button"
                                    onclick="updateQuery('page', {{ intval($pagination['currentPage']) - 1 }})">
                                {{ intval($pagination['currentPage']) - 1 }}
                            </button>
                        </li>
                    @endif

                    <li class="page-item">
                        <button class="page-link cursor-normal" type="button" aria-label="Page Button"
                                style="background-color: var(--color-main);">
                            {{ $pagination['currentPage'] }}
                        </button>
                    </li>

                    @if (intval($pagination['currentPage']) < intval($pagination['lastPage']))
                        <li class="page-item">
                            <button class="page-link cursor-pointer" type="button" aria-label="Page Button"
                                    onclick="updateQuery('page', {{ intval($pagination['currentPage']) + 1 }})">
                                {{ intval($pagination['currentPage']) + 1 }}
                            </button>
                        </li>
                    @endif

                    @if (intval($pagination['currentPage']) < intval($pagination['lastPage']))
                        <li class="page-item">
                            <button class="page-link cursor-pointer" type="button" aria-label="Page Button"
                                    onclick="updateQuery('page', {{ intval($pagination['currentPage']) + 1 }})">
                                <i class="fa-solid fa-backward"></i>
                            </button>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>

    <script>
        const updateQuery = (key, value) => {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set(key, value);
            if (key != 'page') urlParams.delete('page');
            window.location.search = urlParams;
        }
        const update_filter = (key) => {
            const urlParams = new URLSearchParams();
            urlParams.set(key, true);
            window.location.search = urlParams;
        }
        const search = (e) => {
            e.preventDefault();
            updateQuery('s', document.getElementById('search_input').value);
        }
    </script>

    <script>
        $(document).ready(function () {
            $('.form_delete').on('submit', function (e) {
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
