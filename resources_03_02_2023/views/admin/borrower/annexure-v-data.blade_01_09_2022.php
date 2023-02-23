@extends('layouts.auth.master')

@section('title', 'Annexure V data')

@section('content')
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
                                {{-- @if (count($images) > 0)
                                    @if($images[0]->page_one_data != null)
                                        <img src="{{asset($images[0]->page_one_data)}}" height="150px" width="300px">
                                    @endif
                                @endif --}}
                                <div class="form-group row">
                                    <label for="rm_id" class="col-sm-2 col-form-label">Page 1 data<span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="file" name="page_one_data" class="form-control">
                                        @error('page_one_data') <p class="small mb-0 text-danger">{{$message}}</p>@enderror
                                    </div>
                                </div>


                                {{-- Page 2 data --}}

                                {{-- @if (count($images) > 0)
                                    @if($images[0]->page_two_data != null)
                                        <img src="{{asset($images[0]->page_two_data)}}" height="150px" width="300px">
                                    @endif
                                @endif --}}
                                <div class="form-group row">
                                    <label for="rm_id" class="col-sm-2 col-form-label">Page 2 data<span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="file" name="page_two_data" class="form-control">
                                        @error('page_two_data') <p class="small mb-0 text-danger">{{$message}}</p>@enderror
                                    </div>
                                </div>
                                
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
                                            <td><img src="{{asset($i->page_one_data)}}" height="60px" width="100px"></td>
                                            <td><img src="{{asset($i->page_two_data)}}" height="60px" width="100px"></td>
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

@endsection

@section('script')
    <script></script>
@endsection
