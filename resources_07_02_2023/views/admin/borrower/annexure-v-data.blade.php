@extends('layouts.auth.master')

@section('title', 'Annexure V data')

@section('content')
    <style>
        .modal-dialog{
            max-width: 1100px !important;
        }
    </style>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="text-small">{{ $data->borrowerDetails->full_name }} - {{$data->agreementDetails->name}}</h5>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                    <a href="{{ route('user.borrower.agreement',[$data->id]) }}" class="btn btn-sm btn-primary"> <i
                                            class="fas fa-chevron-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- <h6 class="badge badge-primary" style="font-size: 22px;border-radius: 0;">Updated: {{count($images)}} times</h6> --}}
                            <form class="form-horizontal" enctype="multipart/form-data" method="POST">
                                @csrf

                                <input type="hidden" name="borrower_agreement_id" value="{{ $data->id }}">

                                {{-- Page 1 data --}}

                                <div class="form-group row">
                                    <label for="rm_id" class="col-sm-2 col-form-label">Page 1 data<span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="file" name="page_one_data" class="form-control">
                                        @error('page_one_data') <p class="small mb-0 text-danger">{{$message}}</p>@enderror
                                    </div>
                                </div>
                                <input type="hidden" name="image1">


                                {{-- Page 2 data --}}

                                <div class="form-group row">
                                    <label for="rm_id" class="col-sm-2 col-form-label">Page 2 data<span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="file" name="page_two_data" class="form-control">
                                        @error('page_two_data') <p class="small mb-0 text-danger">{{$message}}</p>@enderror
                                    </div>
                                </div>
                                <input type="hidden" name="image2">
                                
                                {{-- Button --}}

                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-8">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="card-footer">
                            <div class="alert alert-sm alert-warning"><i class="fa fa-info-circle strong"></i> <span class="text-sm">Last Updated Image will be used in the agreement</span></div>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Page 1 data</th>
                                        <th>Page 2 data</th>
                                        <th>Uploaded At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($images as $key => $i)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td><img src="{{asset($i->page_one_data)}}" height="60px" width="100px" onclick="previewAnnexureVImage(this)"></td>
                                            <td><img src="{{asset($i->page_two_data)}}" height="60px" width="100px" onclick="previewAnnexureVImage(this)"></td>
                                            <td>{{date('d-m-Y H:i', strtotime($i->created_at))}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade bd-example-modal-lg" id="imagePreviewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 600px">
          <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" class="d-flex justify-content-center">
                <img src="" id="imagePreviewModalImage" height="600px" width="100%">
            </div>
          </div>
        </div>
    </div>

    <div class="modal" id="uploadimageModal1" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crop image 1</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div id="image_demo1"></div>
                            <button class="btn btn-sm btn-primary crop_image1">Crop & Upload Image one</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="uploadimageModal2" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crop image 2</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div id="image_demo2"></div>
                            <button class="btn btn-sm btn-primary crop_image2">Crop & Upload Image two</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function previewAnnexureVImage(x){
            $('#imagePreviewModalImage').attr('src',$(x).attr('src'))
            $('#imagePreviewModal').modal('show')
        }
    </script>

    <script>
        const viewportWidth = 695;
        const viewportHeight = 950;

        const viewportBoundaryWidth = 750;
        const viewportBoundaryHeight = 1000;

        $image_crop1 = $('#image_demo1').croppie({
            enableExif: true,
            viewport: {
                width: viewportWidth,
                height: viewportHeight,
                type: 'square'
            },
            boundary: {
                width: viewportBoundaryWidth,
                height: viewportBoundaryHeight
            },
        });

        $('input[name="page_one_data"]').on('change', function () {
            var reader1 = new FileReader();
            reader1.onload = function (event) {
                $image_crop1.croppie('bind', {
                    url: event.target.result
                });
            }
            reader1.readAsDataURL(this.files[0]);
            $('#uploadimageModal1').modal('show');
        });

        $('.crop_image1').click(function (event) {
            $image_crop1.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (response) {
                $('input[name="image1"]').val(response);
                $('#uploadimageModal1').modal('hide');
                
            })
        });

        // Second image crop modal
        $image_crop2 = $('#image_demo2').croppie({
            enableExif: true,
            viewport: {
                width: viewportWidth,
                height: viewportHeight,
                type: 'square'
            },
            boundary: {
                width: viewportBoundaryWidth,
                height: viewportBoundaryHeight
            },
        });
        /* $image_crop2 = $('#image_demo2').croppie({
            enableExif: true,
            viewport: {
                width: 920,
                height: 1035,
                type: 'square'
            },
            boundary: {
                width: 1050,
                height: 1050
            },
        }); */

        $('input[name="page_two_data"]').on('change', function () {
            var reader2 = new FileReader();
            reader2.onload = function (event) {
                $image_crop2.croppie('bind', {
                    url: event.target.result
                });
            }
            reader2.readAsDataURL(this.files[0]);
            $('#uploadimageModal2').modal('show');
        });

        $('.crop_image2').click(function (event) {
            $image_crop2.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (response) {
                $('input[name="image2"]').val(response);
                $('#uploadimageModal2').modal('hide');
                
            })
        });
    </script>
@endsection
