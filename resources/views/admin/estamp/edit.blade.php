@extends('layouts.auth.master')

@section('title', 'Edit Estamp')

@section('content')
<style>
    .modal-dialog{
        max-width: 500px !important;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                <i class="fas fa-expand"></i>
                            </button>
                            <a href="{{route('user.estamp.list')}}" class="btn btn-sm btn-primary"> <i class="fas fa-chevron-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.estamp.update',$stamp_details->id) }}" method="POST"  enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <label for="unique_stamp_code" class="col-sm-2 col-form-label">Unique Stamp Code <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('unique_stamp_code') {{'is-invalid'}} @enderror" id="unique_stamp_code" name="unique_stamp_code" placeholder="Unique Stamp Code" value="{{$stamp_details->unique_stamp_code ?? old('unique_stamp_code')}}" autofocus>
                                    @error('unique_stamp_code') <p class="small mb-0 text-danger">{{$message}}</p> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="unique_stamp_code" class="col-sm-2 col-form-label">Front Page <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control @error('front_page') {{'is-invalid'}} @enderror" id="hundred_rs_front_page" name="front_page" placeholder="Unique stamp code" value="{{old('front_page')}}">
                                    @error('front_page') <p class="small mb-0 text-danger">{{$message}}</p> @enderror
                                    <p class="small mb-0 text-danger" id="hundred_rs_front_page_err">
                                </div>
                            </div>
                            <input type="hidden" name="image">
                            <div class="form-group row">
                                <label for="front_text" class="col-sm-2 col-form-label">Front Text </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('front_text') {{'is-invalid'}} @enderror" id="ten_rs_front_text" name="front_text" placeholder="Please add front page text" value="{{$stamp_details->front_text ?? old('front_text')}}" >
                                    
                                    @error('front_text') <p class="small mb-0 text-danger">{{$message}}</p> @enderror
                                    <p class="small mb-0 text-danger" id="ten_rs_front_text_err">
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                                <label for="unique_stamp_code" class="col-sm-2 col-form-label">Back Page <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control @error('back_page') {{'is-invalid'}} @enderror" id="hundred_rs_back_page" name="back_page" placeholder="Unique stamp code" value="{{old('back_page')}}" autofocus>
                                    @error('back_page') <p class="small mb-0 text-danger">{{$message}}</p> @enderror
                                    <p class="small mb-0 text-danger" id="hundred_rs_back_page_err">
                                </div>
                            </div> --}}
                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
    <script>
        $image_crop = $('#image_demo').croppie({
            enableExif: true,
            viewport: {
                width: 400,
                height: 100,
                type: 'rectangle'
            },
            boundary: {
                width: 450,
                height: 450
            },
        });

        $('input[name="front_page"]').on('change', function () {
            var reader = new FileReader();
            reader.onload = function (event) {
                $image_crop.croppie('bind', {
                    url: event.target.result
                });
            }
            reader.readAsDataURL(this.files[0]);
            $('#uploadimageModal').modal('show');
        });

        $('.crop_image').click(function (event) {
            $image_crop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (response) {
                $('input[name="image"]').val(response);
                $('#uploadimageModal').modal('hide');
                
            })
        });
    </script>
@endsection