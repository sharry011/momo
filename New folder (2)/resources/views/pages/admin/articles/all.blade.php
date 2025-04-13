@extends('layouts/admin_layout')

@section('title', 'كل المقالات')

@section('content')
    <div class="first_title m-3">
        <h3>كل المقالات</h3>
    </div>
    <div class="cont">
        <div class="head">
            <h4>كل المقالات</h4>
            <div class="row w-50">
                <div class="col-8">
                    <form class="input-group mb-3 ltr" onsubmit="search(event)">
                        <button class="btn btn-outline-secondary" type="submit" id="button-addon1">بحث</button>
                        <input id="search_input" value="{{request()->query('s')}}" type="text" class="form-control rtl"
                               placeholder="ابحث عن مقال" aria-label="Example text with button addon"
                               aria-describedby="button-addon1" v-model="s">
                    </form>
                </div>

                <div class="col-4">
                    <select class="form-select form-select mb-3" aria-label=".form-select-lg example"
                            onchange="update_filter(this.value)">
                        <option value="all" selected>الكل</option>
                        <option value="pinned" {{ request()->query('pinned') ? 'selected' : '' }}>مثبت</option>
                        <option value="hidden" {{ request()->query('hidden') ? 'selected' : '' }}>مسودة</option>
                    </select>
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
                @foreach ($posts as $post)
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
                                <a href="{{route('admin.articles.copy.get', ['id' => $post->id])}}"
                                   class="btn btn-sm btn-success br-0"
                                   title="نسخ"><i class="fa-solid fa-copy"></i>
                                </a>
                                <a href="{{route('admin.articles.update.get', ['id' => $post->id])}}"
                                   class="btn btn-sm btn-warning br-0"
                                   title="تعديل"><i class="fa-solid fa-pen-to-square"></i></a>

                                <form method="post" class="form_delete"
                                      action="{{route('admin.articles.delete', ['page' => request()->route('page'), 'pinned' => request()->query('pinned'), 'hidden' => request()->query('hidden'), 's' => request()->query('s')])}}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$post->id}}">
                                    <button class="btn btn-sm btn-danger br-0" title="حذف" type="submit">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>

                                <form method="post"
                                      action="{{route('admin.articles.update_time', ['page' => request()->route('page'), 'pinned' => request()->query('pinned'), 'hidden' => request()->query('hidden'), 's' => request()->query('s')])}}">
                                    @csrf
                                    <button class="btn btn-sm btn-primary br-0" title="ارسال الى الأعلى" type="submit"
                                            name="id" value="{{$post->id}}">
                                        <i class="fa-solid fa-arrows-up-to-line"></i>
                                    </button>
                                </form>

                                @if ($post->pin_index != null)
                                    <form method="post"
                                          action="{{route('admin.articles.unpin', ['page' => request()->route('page'), 'pinned' => request()->query('pinned'), 'hidden' => request()->query('hidden'), 's' => request()->query('s')])}}">
                                        @csrf
                                        <button class="btn btn-sm btn-success br-0" title="الغاء التثبيت" type="submit"
                                                name="id" value="{{$post->id}}">
                                            <i class="fa-solid fa-toggle-on"></i>
                                        </button>
                                    </form>
                                @else
                                    <form method="post"
                                          action="{{route('admin.articles.pin', ['page' => request()->route('page'), 'pinned' => request()->query('pinned'), 'hidden' => request()->query('hidden'), 's' => request()->query('s')])}}">
                                        @csrf
                                        <button class="btn btn-sm btn-success br-0" title="تثبيت" type="submit"
                                                name="id" value="{{$post->id}}">
                                            <i class="fa-solid fa-toggle-off"></i>
                                        </button>
                                    </form>
                                @endif

                                @if ($post->show == 1)
                                    <form method="post"
                                          action="{{route('admin.articles.hide', ['page' => request()->route('page'), 'pinned' => request()->query('pinned'), 'hidden' => request()->query('hidden'), 's' => request()->query('s')])}}">
                                        @csrf
                                        <button class="btn btn-sm btn-primary br-0" title="اخفاء" type="submit"
                                                name="id" value="{{$post->id}}">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </form>
                                @elseif($post->show == 0)
                                    <form method="post"
                                          action="{{route('admin.articles.show', ['page' => request()->route('page'), 'pinned' => request()->query('pinned'), 'hidden' => request()->query('hidden'), 's' => request()->query('s')])}}">
                                        @csrf
                                        <button class="btn btn-sm btn-primary br-0" title="اظهار" type="submit"
                                                name="id" value="{{$post->id}}">
                                            <i class="fa-regular fa-eye-slash"></i>
                                        </button>
                                    </form>
                                @endif

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
