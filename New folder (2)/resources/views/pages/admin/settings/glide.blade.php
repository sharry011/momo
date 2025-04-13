@extends('layouts/admin_layout')

@section('title', 'السلايدر')


@section('content')
    <div class="first_title m-3">
        <h3>السلايدر</h3>
    </div>

    <div class="cont">
        <div class="details">

            <form action="{{route('admin.glide_new_post.post')}}" method="post">
                @csrf
                <select class="form-select form-select-lg " aria-label="Default select example" name="new_post"
                        id="new_post" required>
                </select>
                <button class="btn btn-success my-3 w-100" type="submit" id="add_new_post">اضافة للسلايد</button>
            </form>

            <hr/>
            <table class="details table table-striped table-hover">
                <thead>
                <tr>
                    <th scope="col">الصورة</th>
                    <th scope="col">العنوان</th>
                    <th scope="col">القسم</th>
                    <th scope="col">التاريخ</th>
                    <th scope="col" class="text-center">الأوامر</th>
                </tr>
                </thead>
                <tbody>
                @foreach($glide_posts as $post)
                    <tr>
                        <td><img src="{{asset($post->post->img)}}" class="post_img"/></td>
                        <td>
                            {{$post->post->title}}
                        </td>
                        <td>
                            {{ $post->post->categories->count() > 0 ? $post->post->categories[0]->name : 'بدون قسم' }}
                        </td>
                        <td>
                            {{$post->post->created_at}}
                        </td>
                        <td class="text-center">
                            <form action="{{route('admin.glide_remove_post.post')}}" method="post"
                                  class="post_actions d-flex flex-row flex-nowrap justify-content-center">
                                @csrf
                                <input type="hidden" name="id" value="{{$post->id}}">
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

            const new_post_select = $('#new_post');
            new_post_select.select2({
                dir: "rtl",
                placeholder: "اختر منشور",
                allowClear: true,
                language: {
                    noResults: function () {
                        return "يجب كتابة 4 حروف على الأقل";
                    },
                    searching: function () {
                        return "جاري البحث...";
                    }
                }
            });

            const api_posts = []

            new_post_select.on('select2:open', function () {
                const search_Input = $(this).parent().find('.select2-search__field');
                $(document).on('input', 'input[aria-controls="select2-new_post-results"]',
                    function () {
                        const searchValue = $(this).val();
                        const optionExists = api_posts.some(post => post.title.toLowerCase() === searchValue.trim());

                        if (searchValue.trim() !== '' && searchValue.trim().length > 2) {
                            search_api_posts(searchValue);
                        }
                    });
            });

            const search_api_posts = async (search_value) => {
                const api_endpoint = `/data_provider/search_posts/${encodeURIComponent(search_value)}`;

                console.log('calling api');

                fetch(api_endpoint)
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then((data) => {
                        // console.log(data);
                        data.posts.forEach(post => {
                            if (!api_posts.some(p => p.id == post.id)) {
                                api_posts.push(post);
                                const newOption = new Option(post.title, post.id);
                                new_post_select.append(newOption).trigger('change');
                            }
                        });
                    })
                    .catch((error) => {
                        console.error('Error fetching data:', error);
                    });

            }
        });
    </script>

@endsection
