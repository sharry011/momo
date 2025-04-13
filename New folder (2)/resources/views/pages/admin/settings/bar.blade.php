@extends('layouts/admin_layout')

@section('title', 'القائمة : ' . $bar->name)


@section('content')
    <div class="first_title m-3">
        <h3>القائمة : {{$bar->name}}</h3>
    </div>
    <div class="row m-3">
        <div class="col-12 col-lg-4 p-2">
            <form class="details mb-3" id="add_categ_form" action="" method="post">
                <h4>الأقسام</h4>
                <hr/>
                <select class="form-select mb-3" id="categs" required>
                    @foreach ($categories as $categ)
                        <option value="{{ $categ->id }}">{{ $categ->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-success">اضافة</button>
            </form>

            <form class="details mb-3" id="add_link_form" action="" method="post">
                <h4>روابط مخصصة</h4>
                <hr/>
                <input type="text" class="form-control mb-3" placeholder="الاسم" id="link_name" required/>
                <input type="text" class="form-control mb-3" placeholder="الرابط" id="link_url"/>
                <button class="btn btn-success">اضافة</button>
            </form>

        </div>

        <form class="col-12 col-lg-8 p-2" id="order_form" method="post" action="">
            <div class="dd details">
                <div class="w-100">
                    <button class="btn btn-success">حفظ ترتيب القائمة</button>
                </div>

                <hr class="my-4"/>

                <ol class="dd-list">
                    @foreach ($bar['items'] as $item)
                        @if ($item['type'] == 'multi')
                            <li class="dd-item" data-text="{{ $item['name'] }}">
                                <button class="delete-item"><i class="fa-solid fa-trash"></i></button>
                                <div class="dd-handle">
                                    <span>{{ $item['name'] }}</span>
                                </div>
                                <ol class="dd-list">
                                    @foreach ($item['items'] as $child)
                                        <li class="dd-item" data-text="{{ $child['name'] }}"
                                            data-type="{{ $child['type'] }}"
                                            data-value="{{ $child['type'] == 'categ' ? $child['id'] : $child['link'] }}">
                                            <button class="delete-item"><i class="fa-solid fa-trash"></i></button>
                                            <div class="dd-handle">
                                                <span>{{ $child['name'] }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>
                            </li>
                        @elseif ($item['type'] == 'single')
                            <li class="dd-item" data-text="{{ $item['item']['name'] }}"
                                data-type="{{ $item['item']['type'] }}"
                                data-value="{{ $item['item']['type'] == 'categ' ? $item['item']['id'] : $item['item']['link'] }}">
                                <button class="delete-item"><i class="fa-solid fa-trash"></i></button>
                                <div class="dd-handle">
                                    <span>{{ $item['item']['name'] }}</span>
                                </div>
                            </li>
                        @endif
                    @endforeach
                </ol>
            </div>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            // Add Event Listener to Delete Buttons
            $(document).on('click', '.delete-item', function() {
                var listItem = $(this).closest('.dd-item');
                listItem.remove();
                console.log('delete')
            });

            // Initialize Nestable
            $('.dd').nestable({
                maxDepth: 2
            });

            // update bar
            $('#order_form').on('submit', function(e){
                e.preventDefault();
                var data = $('.dd').nestable('serialize');
                $.post({
                    url: '/{{request()->admin_link}}/bars/{{$bar->id}}/update',
                    data: {
                        _token: '{{csrf_token()}}',
                        items: data
                    },
                    complete: function(data){
                        $('#admin_preloader').removeClass('loaded');
                        window.location.reload();
                    }
                })
            })

            // add link
            $('#add_link_form').on('submit', function(e){
                e.preventDefault();
                $('#admin_preloader').removeClass('loaded');
                const name = $('#link_name').val();
                const url = $('#link_url').val();
                const item = {
                    type: 'single',
                    item:{
                        type: 'link',
                        name: name,
                        link: url
                    }
                }
                add_item(item);
            })

            // add categ
            $('#add_categ_form').on('submit', function(e){
                e.preventDefault();
                $('#admin_preloader').removeClass('loaded');
                const categ_id = $('#categs').val();
                const item = {
                    type: 'single',
                    item:{
                        type: 'categ',
                        value: categ_id
                    }
                }
                add_item(item);
            })

            function add_item(item){
                $.post({
                    url: `/{{request()->admin_link}}/bars/{{$bar->id}}/add_item`,
                    data: {
                        _token: '{{csrf_token()}}',
                        item: item
                    },
                    complete: function(data){
                        window.location.reload();
                    }
                })
            }
        });
    </script>
@endsection
