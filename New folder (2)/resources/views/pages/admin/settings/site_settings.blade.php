@extends('layouts/admin_layout')

@section('title', 'اعدادات الموقع')

@section('content')
    <div class="first_title m-3">
        <h3>اعدادات الموقع</h3>
    </div>

    <form method="post" action="{{route('admin.site_settings.post')}}" enctype="multipart/form-data" class="row details m-3">
        @csrf
        <div class="col-12 col-lg-12">
            <div class="mb-3">
                <label class="form-label">اسم الموقع :</label>
                <input class="form-control" placeholder="اسم الموقع" value="{{ old('site_name', $site_settings['site_name']) }}" name="site_name" />
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="mb-3">
                <label class="form-label">رابط لوحة التحكم :</label>
                <input class="form-control" placeholder="رابط لوحة التحكم" value="{{ old('settings_admin_link', $site_settings['admin_link']) }}" name="settings_admin_link" />
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="mb-3">
                <label class="form-label">عدد المقالات في الصفحة :</label>
                <input class="form-control" placeholder="عدد المقالات في الصفحة" value="{{ old('per_page', $site_settings['per_page']) }}" name="per_page" />
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="mb-3">
                <label class="form-label">وصف الموقع :</label>
                <textarea class="form-control" placeholder="{{ __('وصف الموقع') }}" rows="5" name="site_desc">{{ old('site_desc', $site_settings['site_desc']) }}</textarea>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="mb-3">
                <label class="form-label">الكلمات المفتاحية :</label>
                <textarea class="form-control" placeholder="الكلمات المفتاحية" rows="5" name="keywords">{{ old('keywords', $site_settings['keywords']) }}</textarea>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label class="form-label">نص الفوتر :</label>
                <textarea class="form-control" placeholder="نص الفوتر" rows="5" name="site_footer">{{ old('site_footer', $site_settings['site_footer']) }}</textarea>
            </div>
        </div>
        <hr />
        <div class="col-12">
            <div class="mb-3">
                <label class="form-label">لوغو الموقع :</label>

                <div class="row ltr">
                    <div class="col-12 col-lg-4 mb-3">
                        <input class="form-control" type="text" name="en_t1" value="{{ old('en_t1', $site_settings['site_logo']['en']['t1']) }}" required />
                    </div>
                    <div class="col-12 col-lg-4 mb-3">
                        <input class="form-control" type="text" name="en_t2" value="{{ old('en_t2', $site_settings['site_logo']['en']['t2']) }}" required />
                    </div>
                    <div class="col-12 col-lg-4 mb-3">
                        <input class="form-control" type="text" name="en_t3" value="{{ old('en_t3', $site_settings['site_logo']['en']['t3']) }}" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <input class="form-control" type="text" name="ar_t1" value="{{ old('ar_t1', $site_settings['site_logo']['ar']['t1']) }}" required />
                    </div>
                    <div class="col-12 col-lg-6 mb-3">
                        <input class="form-control" type="text" name="ar_t2" value="{{ old('ar_t2', $site_settings['site_logo']['ar']['t2']) }}" required />
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">اقل قيمة لطول اللوغو :</label>
                        <input class="form-control" type="text" placeholder="{{ __('اقل قيمة لطول اللوغو') }}" name="style_min" value="{{ old('style_min', $site_settings['site_logo']['style']['min']) }}" required />
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">اكبر قيمة لطول اللوغو :</label>
                        <input class="form-control" type="text" placeholder="{{ __('اكبر قيمة لطول اللوغو') }}" name="style_max" value="{{ old('style_max', $site_settings['site_logo']['style']['max']) }}" required />
                    </div>
                </div>

            </div>
        </div>
        <hr />

        <div class="col-12 mb-3">
            <input class="form-control mb-3" type="file" name="site_icon" id="site_icon" />
            <img src="/faveicon.ico?v={{ time() }}" style="height: 150px; width: 150px;" class="img-fluid" />
        </div>

        <hr />

        <div class="col-12">
            <button class="btn btn-success">تحديث</button>
        </div>
    </form>

    <script>
        $('form').on('submit', function () {
            $('#admin_preloader').removeClass('loaded');
        });
    </script>

    <script>
        $('#site_icon').on('input', function() {
            var file = $(this)[0].files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.img-fluid').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        });
    </script>
@endsection
