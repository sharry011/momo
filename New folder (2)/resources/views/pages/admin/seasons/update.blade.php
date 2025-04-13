@extends('layouts/admin_layout')

@section('title', 'تعديل معلومات الموسم')

@section('content')
    <form class="row p-0 m-0 my-4" id="new_post_form"
          action="{{url(route('admin.seasons.update.post', ['id' => $post->id]))}}" method="post"  enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

        <div class="first_title col-12">
            <h3>تعديل معلومات الموسم</h3>
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
                                    <input class="form-check-input mt-0" type="radio" value="imdb" name="data_provider"
                                           checked>
                                </div>
                                <input class="form-control rtl" type="text" placeholder="كود imdb" id="imdb"/>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="input-group ltr mb-3 ">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" value="elcinemaCom"
                                           name="data_provider">
                                </div>
                                <input class="form-control rtl" type="text" placeholder="كود elcinemaCom"
                                       id="elcinemaCom"/>
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

                    <div class="mb-3" id="season-container">
                        <label for="" class="form-label">المسلسل :</label>
                        <select id="season_select" class="form-control" name="selected_serie" required>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">العنوان :</label>
                        <input class="form-control mb-3" type="text" placeholder="العنوان" value="{{$post->title}}"
                               name="title"
                               required/>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">الرابط :</label>
                        <input class="form-control mb-3" type="text" placeholder="العنوان" value="{{$post->slug}}"
                               name="slug"
                               required/>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">الوصف :</label>
                        <textarea class="w-100" placeholder="الوصف" name="story"
                                  required>{!! $post->story !!}</textarea>
                    </div>
                </div>
            </div>

            <div class="cont">
                <div class="head">
                    <h5>إعدادات عامة</h5>
                </div>
                <div class="details">

                    <div class="mb-3 row" id="ep_num-container">
                        <label for="" class="col-sm-2 col-form-label">رقم الموسم</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{$post->num}}" name="num"/>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">الجودة</label>
                        <div class="col-sm-10">
                            <select id="qualities_select" class="form-control" name="selected_qualities[]"
                                    multiple="multiple" required>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">التقييم</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" maxlength="5" value="{{$post->rating}}"
                                   name="rating"/>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">الممثلون</label>
                        <div class="col-sm-10">
                            <select id="actors_select" class="form-control" name="selected_actors[]"
                                    multiple="multiple">
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">المخرجون</label>
                        <div class="col-sm-10">
                            <select id="directors_select" class="form-control" name="selected_directors[]"
                                    multiple="multiple">
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">الكاتبون</label>
                        <div class="col-sm-10">
                            <select id="writers_select" class="form-control" name="selected_writers[]"
                                    multiple="multiple">
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">رابط الاعلان من اليوتيوب</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{$post->triller}}" name="triller"/>
                        </div>
                    </div>

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
                        <input class="form-control" type="file" name="post_img" id="post_img"/>
                        <input hidden name="img_link" id="img_link" type="text" value="{{$post->img}}"/>
                    </div>
                    <div class="show-card" id="img_preview"
                         style="background-image: url({{$post->img}}); --br: 0"></div>
                </div>
            </div>

            <div class="cont">
                <div class="head">
                    <h5>معلومات أساسية</h5>
                </div>
                <div class="details">
                    <div class="mb-3">
                        <label for="" class="form-label">القسم :</label>
                        <select id="categs_select" class="form-control" name="selected_categs[]" multiple="multiple"
                                required>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">السنة :</label>
                        <input class="form-control" type="number" placeholder="السنة" value="{{$post->year}}"
                               name="year"/>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">الأنواع :</label>
                        <select id="types_select" class="form-control" name="selected_types[]" multiple="multiple">
                        </select>
                    </div>
                </div>
            </div>

        </div>

        <div class="">
            <button class="btn btn-success mx-2 my-4" type="submit" name="show" value="1">
                تحديث
            </button>
        </div>

    </form>

    <script>
        $('form').on('submit', function () {
            $('#admin_preloader').removeClass('loaded');
        });
    </script>

    <script>
        $('#post_img').on('input', function () {
            $('#admin_preloader').removeClass('loaded');
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#img_preview').css('background-image', `url(${e.target.result})`);
                $('#admin_preloader').addClass('loaded');
            }
            reader.readAsDataURL(file);
        });
    </script>

    <!-- seasons -->
    <script>
        var seasons;
        var season_select;

        $(document).ready(function () {
            seasons = [@json($post->serie)];

            season_select = $('#season_select')
            season_select.select2({
                language: {
                    noResults: function () {
                        return "يجب كتابة 3 حروف على الأقل";
                    },
                    searching: function () {
                        return "جاري البحث...";
                    }
                }
            });
            seasons.forEach(season => {
                const option = new Option(season.title, season.id);
                option.selected = true;
                season_select.append(option);
            });

            season_select.on('select2:open', function () {
                const search_Input = $(this).parent().find('.select2-search__field');
                $(document).on('input', 'input[aria-controls="select2-season_select-results"]', function () {
                    const searchValue = $(this).val();
                    const optionExists = seasons.some(season => season.title.toLowerCase() === searchValue.trim());

                    if (!optionExists && searchValue.trim() !== '' && searchValue.trim().length > 2) {
                        search_seasons(searchValue);
                    }
                });
            });

            const search_seasons = async (search_value) => {
                const api_endpoint = `/data_provider/search_series/${encodeURIComponent(search_value)}`;
                console.log('calling api');
                fetch(api_endpoint)
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then((data) => {
                        data.series.forEach(season => {
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

    <!-- persons -->
    <script>
        {{--var post = @json($post);--}}
        {{--var all_persons = @json($post->actors + $post->directors + $post->writers);--}}
        var all_actors = @json($post->actors);
        var actors_select;
        var all_directors = @json($post->directors);
        var directors_select;
        var all_writers = @json($post->writers);
        var writers_select;

        $(document).ready(function () {
            // actors
            actors_select = $('#actors_select');
            actors_select.select2({
                language: {
                    noResults: function () {
                        return "يجب كتابة 3 حروف على الأقل";
                    },
                    searching: function () {
                        return "جاري البحث...";
                    }
                }
            });
            all_actors.forEach(person => {
                const option = new Option(person.name, person.name);
                option.selected = true;
                actors_select.append(option);
            });
            actors_select.on('select2:open', function () {
                reset_actors();
                const searchInput = $(this).parent().find('.select2-search__field');
                searchInput.on('input', function () {

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
                $('option', actors_select).each(function () {
                    const optionValue = $(this).val();
                    const optionSelected = $(this).prop('selected');
                    if (!all_actors.some(person => person.name.toString() == optionValue) && !optionSelected) {
                        $(this).remove();
                    }
                });
            }


            // directors
            directors_select = $('#directors_select');
            directors_select.select2({
                language: {
                    noResults: function () {
                        return "يجب كتابة 3 حروف على الأقل";
                    },
                    searching: function () {
                        return "جاري البحث...";
                    }
                }
            });
            all_directors.forEach(person => {
                const option = new Option(person.name, person.name);
                option.selected = true;
                directors_select.append(option);
            });
            directors_select.on('select2:open', function () {
                reset_directors();
                const searchInput = $(this).parent().find('.select2-search__field');
                searchInput.on('input', function () {

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
                $('option', directors_select).each(function () {
                    const optionValue = $(this).val();
                    const optionSelected = $(this).prop('selected');
                    if (!all_directors.some(person => person.name.toString() == optionValue) && !optionSelected) {
                        $(this).remove();
                    }
                });
            }


            // writers
            writers_select = $('#writers_select');
            writers_select.select2({
                language: {
                    noResults: function () {
                        return "يجب كتابة 3 حروف على الأقل";
                    },
                    searching: function () {
                        return "جاري البحث...";
                    }
                }
            });
            all_writers.forEach(person => {
                const option = new Option(person.name, person.name);
                option.selected = true;
                writers_select.append(option);
            });
            writers_select.on('select2:open', function () {
                reset_writers();
                const searchInput = $(this).parent().find('.select2-search__field');
                searchInput.on('input', function () {

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
                $('option', writers_select).each(function () {
                    const optionValue = $(this).val();
                    const optionSelected = $(this).prop('selected');
                    if (!all_writers.some(person => person.name.toString() == optionValue) && !optionSelected) {
                        $(this).remove();
                    }
                });
            }
        });
    </script>

    <!-- categories -->
    <script>
        var all_categs;
        var selected_categs;
        var categs_select;

        $(document).ready(function () {

            all_categs = @json($categories);
            selected_categs = @json($post->categories);

            categs_select = $('#categs_select')
            categs_select.select2();

            all_categs.forEach(categ => {
                const option = new Option(categ.name, categ.name);
                if (selected_categs.find(c => c.id == categ.id)) {
                    option.selected = true;
                }
                categs_select.append(option);
            });


            // Log the search value every time it changes
            categs_select.on('select2:open', function () {
                reset_categs();
                const searchInput = $(this).parent().find('.select2-search__field');
                searchInput.on('input', function () {
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
                $('option', categs_select).each(function () {
                    const optionValue = $(this).val();
                    const optionSelected = $(this).prop('selected');
                    if (!all_categs.some(categ => categ.name.toString() == optionValue) && !optionSelected) {
                        $(this).remove();
                    }
                });
            }

        });
    </script>

    <!-- qualities -->
    <script>
        $(document).ready(function () {

            let all_qualities = @json($qualities);
            let selected_qalities = @json($post->qualities);

            let qualities_select = $('#qualities_select')
            qualities_select.select2();

            for (let quality of all_qualities) {
                const option = new Option(quality.name, quality.name);
                if (selected_qalities.find(selectedQuality => selectedQuality.id === quality.id)) {
                    option.selected = true;
                }
                qualities_select.append(option);
            }
        });
    </script>

    <!-- types -->
    <script>
        var all_types;
        var selected_types;
        var types_selec;

        $(document).ready(function () {

            all_types = @json($types);
            selected_types = @json($post->types);

            types_select = $('#types_select')
            types_select.select2();

            all_types.forEach(type => {
                const option = new Option(type.name, type.name);
                if (selected_types.find(selectedType => selectedType.id === type.id)) {
                    option.selected = true;
                }
                types_select.append(option);
            });

            // Log the search value every time it changes
            types_select.on('select2:open', function () {
                reset_types();
                const searchInput = $(this).parent().find('.select2-search__field');
                searchInput.on('input', function () {
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
                $('option', types_select).each(function () {
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
        $('input[type=radio][name=data_provider]').change(function () {
            data_provider = this.value;
        });

        $('#get_data_btn').click(function () {
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
                success: function (response) {
                    console.log(response);
                    set_data(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);

                },
                complete: function () {
                    $('#admin_preloader').addClass('loaded');
                }
            });
        }

        function get_elcinemaCom_data() {
            $('#admin_preloader').removeClass('loaded');
            const url = `/data_provider/elcinema/${$('#elcinemaCom').val()}`
            $.get({
                url: url,
                success: function (response) {
                    console.log(response)
                    set_data(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                },
                complete: function () {
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
