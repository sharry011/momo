@extends('layouts/admin_layout')

@section('title', 'مقال جديد')

@section('content')
    <form class="row p-0 m-0 my-4" id="new_post_form" method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

        <div class="first_title col-12">
            <h3>مقال جديد</h3>
        </div>

        <div class="col-12 col-lg-8">
            <div class="cont">
                <div class="head">
                    <h5>استخراج البيانات</h5>
                </div>
                <div class="details">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="input-group ltr mb-3 ">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" value="imdb" name="data_provider" checked>
                                </div>
                                <input class="form-control rtl" type="text" placeholder="كود imdb" id="imdb" />
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="input-group ltr mb-3 ">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" value="elcinemaCom" name="data_provider">
                                </div>
                                <input class="form-control rtl" type="text" placeholder="كود elcinemaCom" id="elcinemaCom" />
                            </div>
                        </div>


                    </div>
                    <button type="button" class="btn btn-secondary w-100" id="get_data_btn">استخراج</button>
                </div>
            </div>

            <div class="cont">
                <div class="head">
                    <h5>العنوان والوصف</h5>
                </div>
                <div class="details">

                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input fs-4 ms-0" type="radio" role="switch" id="film_radio" name="opt" value="1" checked>
                                <label class="form-check-label fs-4" for="film_radio">فيلم</label>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input fs-4 ms-0" type="radio" role="switch" id="ep_radio" name="opt" value="2">
                                <label class="form-check-label fs-4" for="ep_radio">حلقة</label>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input fs-4 ms-0" type="radio" role="switch" id="post_radio" name="opt" value="3">
                                <label class="form-check-label fs-4" for="post_radio">عرض</label>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="mb-3" id="season-container" style="display: none;">
                        <label for="" class="form-label">الموسم :</label>
                        <select id="season_select" class="form-control" name="selected_season" single>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">العنوان :</label>
                        <input class="form-control mb-3" type="text" placeholder="العنوان" value="" name="title" required />
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">الوصف :</label>
                        <textarea class="w-100" placeholder="الوصف" name="story" required ></textarea>
                    </div>
                </div>
            </div>

            <div class="cont">
                <div class="head">
                    <h5>إعدادات عامة</h5>
                </div>
                <div class="details">

                    <div class="mb-3 row" id="ep_num-container" style="display: none;">
                        <label for="" class="col-sm-2 col-form-label">رقم الحلقة</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="" name="ep_num" />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">الجودة</label>
                        <div class="col-sm-10">
                            <select id="qualities_select" class="form-control" name="selected_qualities[]" multiple="multiple" required>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">مدة العرض</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="" name="runtime" />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">التقييم</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" maxlength="5" value="" name="rating" />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">الممثلون</label>
                        <div class="col-sm-10">
                            <select id="actors_select" class="form-control" name="selected_actors[]" multiple="multiple">
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">المخرجون</label>
                        <div class="col-sm-10">
                            <select id="directors_select" class="form-control" name="selected_directors[]" multiple="multiple">
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">الكاتبون</label>
                        <div class="col-sm-10">
                            <select id="writers_select" class="form-control" name="selected_writers[]" multiple="multiple">
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row" >
                        <label for="" class="col-sm-2 col-form-label">الملصق</label>
                        <div class="col-sm-10 d-flex gap-1">
                            <input type="text" class="form-control" name="sticker_text" />
                            <input type="color" class="form-control form-control-color" id="" name="sticker_color" value="#ed3c3c" />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">رابط الاعلان من اليوتيوب</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="" name="triller" />
                        </div>
                    </div>

                </div>
            </div>

            <div class="cont">
                <div class="head">
                    <h5>سيرفرات المشاهدة والتحميل</h5>
                </div>
                <div class="details">
                    <label for="" class="form-label">سيرفرات المشاهدة :</label>
                    <textarea class="w-100 mb-2" name="watch_servers"></textarea>
                    <hr />
                    <label for="" class="form-label"> سيرفرات تحميل 1080 :</label>
                    <textarea class="w-100 mb-2" name="post_down_servers_1080"></textarea>
                    <label for="" class="form-label"> سيرفرات تحميل 720 :</label>
                    <textarea class="w-100 mb-2" name="post_down_servers_720"></textarea>
                    <label for="" class="form-label"> سيرفرات تحميل 480 :</label>
                    <textarea class="w-100 mb-2" name="post_down_servers_480"></textarea>

                    <label for="" class="form-label d-none"> سيرفرات تحميل متعددة الجودات :</label>
                    <textarea class="w-100 mb-2 d-none" name="post_down_servers_multi"></textarea>
                    <label for="" class="form-label d-none"> سيرفرات تحميل 360 :</label>
                    <textarea class="w-100 mb-2 d-none" name="post_down_servers_360"></textarea>
                </div>
            </div>

        </div>

        <div class="col-12 col-lg-4">
            <div class="cont">
                <div class="head">
                    <h5>الصورة والكفر</h5>
                </div>
                <div class="details">
                    <div class="mb-3">
                        <input class="form-control" type="file" name="post_img" id="post_img" />
                        <input hidden name="img_link" id="img_link" type="text" value="/photos/imgs/placeholder.webp" />
                    </div>
                    <div class="show-card" id="img_preview" style="background-image: url('/photos/imgs/placeholder.webp'); --br: 0"></div>
                </div>
            </div>

            <div class="cont">
                <div class="head">
                    <h5>معلومات أساسية</h5>
                </div>
                <div class="details">
                    <div class="mb-3">
                        <label for="" class="form-label">القسم :</label>
                        <select id="categs_select" class="form-control" name="selected_categs[]" multiple="multiple" required>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">السنة :</label>
                        <input class="form-control" type="number" placeholder="السنة" value="" name="year" />
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">الأنواع :</label>
                        <select id="types_select" class="form-control" name="selected_types[]" multiple="multiple">
                        </select>
                    </div>
                </div>
            </div>

            <div class="cont">
                <div class="head">
                    <h5>التفاصيل</h5>
                </div>
                <div class="details">

                    <div class="form-check form-switch py-2">
                        <input class="form-check-input fs-4 m-0 mt-1" type="checkbox" role="switch" id="pinned" name="pin" />
                        <label class="form-check-label fs-5" for="pinned">تثبيت</label>
                    </div>
                    <div class="row g-3 align-items-center justify-content-between m-0">
                        <div class="col-auto">
                            <label for="inputPassword6" class="col-form-label fs-5">المشاهدات:</label>
                        </div>
                        <div class="col-auto">
                            <input type="number" id="views" class="form-control" value="0" name="views" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="">
            <button class="btn btn-success mx-2 my-4" type="submit" name="show" value="1">
                نشر
            </button>
            <button class="btn btn-warning mx-2 my-4" type="submit" name="show" value="0">
                مسودة
            </button>
        </div>

    </form>

    <script>
        $('form').on('submit', function () {
            $('#admin_preloader').removeClass('loaded');
        });
    </script>

    <!-- opt -->
    <script>
        // name opt on change
        $('input[type=radio][name=opt]').change(function() {
            let opt = this.value;
            if (opt == 2) {
                $('#season-container').show();
                $('#ep_num-container').show();
            } else {
                $('#season-container').hide();
                $('#ep_num-container').hide();
            }
        });
    </script>

    <!-- post img -->
    <script>
        $('#post_img').on('input', function() {
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#img_preview').css('background-image', `url(${e.target.result})`);
            }
            reader.readAsDataURL(file);
        });
    </script>

    <!-- persons -->
    <script>
        var actors_select;
        var directors_select;
        var writers_select;
        $(document).ready(function() {
            var all_persons = []

            // actors
            var all_actors = [...all_persons]
            actors_select = $('#actors_select');
            actors_select.select2({
                language: {
                    noResults: function() {
                        return "يجب كتابة 3 حروف على الأقل";
                    },
                    searching: function() {
                        return "جاري البحث...";
                    }
                }
            });

            actors_select.on('select2:open', function() {
                reset_actors();
                const searchInput = $(this).parent().find('.select2-search__field');
                searchInput.on('input', function() {

                    const searchValue = $(this).val();
                    //const optionExists = all_persons.some(choice => choice.name.toLowerCase() == searchValue.trim());

                    if (searchValue.trim() !== '' && searchValue.trim().length > 1) {
                        reset_actors();
                        search_actors(searchValue);
                        const optionExists = all_actors.some(choice => choice.name.toLowerCase() == searchValue.trim());
                        if (!optionExists) {
                            const newOption = new Option(searchValue, searchValue);
                            actors_select.append(newOption).trigger('change');
                        }

                    }

                });
            });
            const search_actors = (search_value) => {
                const api_endpoint = `/data_provider/search_persons/${encodeURIComponent(search_value)}`;

                fetch(api_endpoint)
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then((data) => {
                        data.persons.forEach(person => {
                            if (!all_actors.some(p => p.name == person.name)) {
                                all_actors.push(person);
                                const newOption = new Option(person.name, person.name);
                                actors_select.append(newOption).trigger('change');
                                //if (!actors_select.find(`option[value="${person.name}"]`).is(':selected')) {}
                            }
                        });
                        return data.persons.length;
                    })
                    .catch((error) => {
                        console.error('Error fetching data:', error);
                        return 0;
                    });

            }

            function reset_actors() {
                $('option', actors_select).each(function() {
                    const optionValue = $(this).val();
                    const optionSelected = $(this).prop('selected');
                    if (!all_actors.some(person => person.name.toString() == optionValue) && !optionSelected) {
                        $(this).remove();
                    }
                });
            }


            // directors
            var all_directors = [...all_persons]
            directors_select = $('#directors_select');
            directors_select.select2({
                language: {
                    noResults: function() {
                        return "يجب كتابة 3 حروف على الأقل";
                    },
                    searching: function() {
                        return "جاري البحث...";
                    }
                }
            });
            all_directors.forEach(person => {
                const option = new Option(person.name, person.name);
                directors_select.append(option);
            });
            directors_select.on('select2:open', function() {
                reset_directors();
                const searchInput = $(this).parent().find('.select2-search__field');
                searchInput.on('input', function() {

                    const searchValue = $(this).val();
                    //const optionExists = all_persons.some(choice => choice.name.toLowerCase() == searchValue.trim());

                    if (searchValue.trim() !== '' && searchValue.trim().length > 1) {
                        reset_directors();
                        search_directors(searchValue);
                        const optionExists = all_directors.some(choice => choice.name.toLowerCase() == searchValue.trim());
                        if (!optionExists) {
                            const newOption = new Option(searchValue, searchValue);
                            directors_select.append(newOption).trigger('change');
                        }

                    }

                });
            });
            const search_directors = (search_value) => {
                const api_endpoint = `/data_provider/search_persons/${encodeURIComponent(search_value)}`;

                fetch(api_endpoint)
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then((data) => {
                        data.persons.forEach(person => {
                            const optionExists = all_directors.some(choice => choice.name.toLowerCase() == person.name.toLowerCase());

                            if (!optionExists) {

                                all_directors.push(person);
                                const newOption = new Option(person.name, person.name);
                                directors_select.append(newOption).trigger('change');

                            }
                        });
                        return data.persons.length;
                    })
                    .catch((error) => {
                        console.error('Error fetching data:', error);
                        return 0;
                    });

            }

            function reset_directors() {
                $('option', directors_select).each(function() {
                    const optionValue = $(this).val();
                    const optionSelected = $(this).prop('selected');
                    if (!all_directors.some(person => person.name.toString() == optionValue) && !optionSelected) {
                        $(this).remove();
                    }
                });
            }


            // writers
            var all_writers = [...all_persons]
            writers_select = $('#writers_select');
            writers_select.select2({
                language: {
                    noResults: function() {
                        return "يجب كتابة 3 حروف على الأقل";
                    },
                    searching: function() {
                        return "جاري البحث...";
                    }
                }
            });
            all_writers.forEach(person => {
                const option = new Option(person.name, person.name);
                writers_select.append(option);
            });
            writers_select.on('select2:open', function() {
                reset_writers();
                const searchInput = $(this).parent().find('.select2-search__field');
                searchInput.on('input', function() {

                    const searchValue = $(this).val();
                    //const optionExists = all_persons.some(choice => choice.name.toLowerCase() == searchValue.trim());

                    if (searchValue.trim() !== '' && searchValue.trim().length > 1) {
                        reset_writers();
                        search_writers(searchValue);
                        const optionExists = all_writers.some(choice => choice.name.toLowerCase() == searchValue.trim());
                        if (!optionExists) {
                            const newOption = new Option(searchValue, searchValue);
                            writers_select.append(newOption).trigger('change');
                        }

                    }

                });
            });
            const search_writers = (search_value) => {
                const api_endpoint = `/data_provider/search_persons/${encodeURIComponent(search_value)}`;

                fetch(api_endpoint)
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then((data) => {
                        data.persons.forEach(person => {
                            const optionExists = all_writers.some(choice => choice.name.toLowerCase() == person.name.toLowerCase());

                            if (!optionExists) {

                                all_writers.push(person);
                                const newOption = new Option(person.name, person.name);
                                writers_select.append(newOption).trigger('change');

                            }
                        });
                        return data.persons.length;
                    })
                    .catch((error) => {
                        console.error('Error fetching data:', error);
                        return 0;
                    });

            }

            function reset_writers() {
                $('option', writers_select).each(function() {
                    const optionValue = $(this).val();
                    const optionSelected = $(this).prop('selected');
                    if (!all_writers.some(person => person.name.toString() == optionValue) && !optionSelected) {
                        $(this).remove();
                    }
                });
            }
        });
    </script>

    <!-- seasons -->
    <script>
        $(document).ready(function() {
            const seasons = []

            const season_select = $('#season_select')
            season_select.select2({
                language: {
                    noResults: function() {
                        return "يجب كتابة 3 حروف على الأقل";
                    },
                    searching: function() {
                        return "جاري البحث...";
                    }
                }
            });

            season_select.on('select2:open', function() {
                const search_Input = $(this).parent().find('.select2-search__field');
                $(document).on('input', 'input[aria-controls="select2-season_select-results"]', function() {
                    const searchValue = $(this).val();
                    const optionExists = seasons.some(season => season.title.toLowerCase() === searchValue.trim());

                    if (!optionExists && searchValue.trim() !== '' && searchValue.trim().length > 2) {
                        search_seasons(searchValue);
                    }
                });
            });

            const search_seasons = async (search_value) => {
                const api_endpoint = `/data_provider/search_seasons/${encodeURIComponent(search_value)}`;
                console.log('calling api');
                fetch(api_endpoint)
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then((data) => {
                        data.seasons.forEach(season => {
                            if (!seasons.some(s => s.id == season.id)) {
                                seasons.push(season);
                                const newOption = new Option(season.title, season.id);
                                season_select.append(newOption).trigger('change');
                            }
                        });
                    })
                    .catch((error) => {
                        console.error('Error fetching data:', error);
                    });

            }

        });
    </script>

    <!-- qualities -->
    <script>
        $(document).ready(function() {

            let all_qualities = @json($qualities);

            let qualities_select = $('#qualities_select')
            qualities_select.select2();

            for (let quality of all_qualities) {
                const option = new Option(quality.name, quality.name);
                qualities_select.append(option);
            }
        });
    </script>

    <!-- categories -->
    <script>
        $(document).ready(function() {

            var all_categs = @json($categories);

            var categs_select = $('#categs_select')
            categs_select.select2();

            for (categ of all_categs) {
                const option = new Option(categ.name, categ.name);
                categs_select.append(option);
            }

            // Log the search value every time it changes
            categs_select.on('select2:open', function() {
                reset_categs();
                const searchInput = $(this).parent().find('.select2-search__field');
                searchInput.on('input', function() {
                    reset_categs();

                    const searchValue = $(this).val();
                    const optionExists = all_categs.some(choice => choice.name.toLowerCase() == searchValue.trim());

                    if (!optionExists && searchValue.trim() !== '') {
                        const newOption = new Option(searchValue, searchValue);
                        categs_select.append(newOption).trigger('change');
                    }

                });
            });

            function reset_categs() {
                $('option', categs_select).each(function() {
                    const optionValue = $(this).val();
                    const optionSelected = $(this).prop('selected');
                    if (!all_categs.some(categ => categ.name.toString() == optionValue) && !optionSelected) {
                        $(this).remove();
                    }
                });
            }

        });
    </script>

    <!-- types -->
    <script>
        var all_types;
        var types_select;
        $(document).ready(function() {

            all_types = @json($types);

            types_select = $('#types_select')
            types_select.select2({
                language: {
                    noResults: function() {
                        return "لا يوجد نتائج";
                    },
                    searching: function() {
                        return "جاري البحث...";
                    }
                }
            });

            for (type of all_types) {
                const option = new Option(type.name, type.name);
                types_select.append(option);
            }

            // Log the search value every time it changes
            types_select.on('select2:open', function() {
                reset_types();
                const searchInput = $(this).parent().find('.select2-search__field');
                searchInput.on('input', function() {
                    reset_types();

                    const searchValue = $(this).val();
                    const optionExists = all_types.some(choice => choice.name.toLowerCase() == searchValue.trim());

                    if (!optionExists && searchValue.trim() !== '') {
                        const newOption = new Option(searchValue, searchValue);
                        types_select.append(newOption).trigger('change');
                    }

                });
            });

            function reset_types() {
                $('option', types_select).each(function() {
                    const optionValue = $(this).val();
                    const optionSelected = $(this).prop('selected');
                    if (!all_types.some(type => type.name.toString() == optionValue) && !optionSelected) {
                        $(this).remove();
                    }
                });
            }

        })
    </script>

    <!-- get post data -->
    <script>
        let data_provider = 'imdb';
        $('input[type=radio][name=data_provider]').change(function() {
            data_provider = this.value;
        });

        $('#get_data_btn').click(function() {
            if (data_provider == 'imdb') {
                get_imdb_data();
            } else if (data_provider == 'elcinemaCom') {
                get_elcinemaCom_data();
            }
        });

        function get_imdb_data() {
            $('#admin_preloader').removeClass('loaded');
            const url = `/data_provider/imdb/${$('#imdb').val()}`
            $.get({
                url: url,
                success: function(response) {
                    console.log(response);
                    set_data(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);

                },
                complete: function() {
                    $('#admin_preloader').addClass('loaded');
                }
            });
        }

        function get_elcinemaCom_data() {
            $('#admin_preloader').removeClass('loaded');
            const url = `/data_provider/elcinema/${$('#elcinemaCom').val()}`
            $.get({
                url: url,
                success: function(response) {
                    console.log(response)
                    set_data(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                },
                complete: function() {
                    $('#admin_preloader').addClass('loaded');
                }
            })
        }

        function set_data(data) {
            $('input[name=title]').val(data.Title);
            $('input[name=slug]').val(data.Title.replace(/ /g, '-'));
            $('textarea[name=story]').val(data.Plot);
            $('input[name=runtime]').val(data.Runtime);
            $('input[name=rating]').val(data.imdbRating);

            actors_select.val(null).trigger('change');
            for (actor of data.Actors) {
                const option = new Option(actor, actor);
                option.selected = true;
                actors_select.append(option).trigger('change');
            }

            directors_select.val(null).trigger('change');
            for (director of data.Director) {
                const option = new Option(director, director);
                option.selected = true;
                directors_select.append(option).trigger('change');
            }

            writers_select.val(null).trigger('change');
            for (writer of data.Writer) {
                const option = new Option(writer, writer);
                option.selected = true;
                writers_select.append(option).trigger('change');
            }

            $('input[name=year]').val(data.Year);

            types_select.val(null).trigger('change');
            for (type of data.Genre) {
                // check if type exists
                const optionExists = types_select.find(`option[value="${type}"]`).length > 0;
                if (!optionExists) {
                    const newOption = new Option(type, type);
                    newOption.selected = true;
                    types_select.append(newOption).trigger('change');
                } else {
                    // set the option selected
                    types_select.find(`option[value="${type}"]`).prop('selected', true).trigger('change');
                }
            }

            // set the image
            if (data.Poster) {
                let poster = data.Poster.replace(/\\/g, '/');
                if (data.original_poster) $('#img_preview').css('background-image', `url(${data.original_poster})`);
                $('#img_link').val(poster);
                $('#post_img').val('');
            }


        }
    </script>
@endsection
