@extends('layouts.auth.master')

@section('title', 'E-signing')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            @if(Session::has('success'))
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show">{{ Session::get('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            @endif
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $data->borrower->name_prefix }} {{ $data->borrower->full_name }}</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                <i class="fas fa-expand"></i>
                            </button>
                            <a href="{{route('user.borrower.list')}}" class="btn btn-sm btn-primary"> <i class="fas fa-chevron-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.esign.submit') }}" method="POST" enctype="multipart/form-data">@csrf
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="" class="small text-muted">Select Document</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="document" name="file" accept=".pdf">
                                            <label class="custom-file-label" for="document" data-browse="Browse">Select PDF for E-signing</label>
                                        </div>
                                        @error('file') <p class="small text-danger mt-2">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="documentInformation" class="small text-muted">Document information</label>
                                        <input type="text" class="form-control" id="documentInformation" name="documentInformation" placeholder="Enter document details" value="{{ old('documentInformation') ? old('documentInformation') : 'This document is to be e-signed by borrower, co-borrower and authorised signatory' }}">
                                        @error('documentInformation') <p class="small text-danger mt-2">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- <div class="col-12">
                                    <p class="small text-muted">Borrower information</p>
                                </div> --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="borrower_name" class="small text-muted mb-0">Borower name</label>
                                        <input type="text" class="form-control" id="borrower_name" name="borrower_name" placeholder="Name" value="{{ old('borrower_name') ? old('borrower_name') : $data->borrower_name }}" readonly>
                                        @error('borrower_name') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="borrower_email" class="small text-muted mb-0">Borower email</label>
                                        <input type="email" class="form-control" id="borrower_email" name="borrower_email" placeholder="Email" value="{{old('borrower_email') ? old('borrower_email') : $data->borrower_email}}" readonly>
                                        @error('borrower_email') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="borrower_city" class="small text-muted mb-0">Borower city</label>
                                        <input type="text" class="form-control" id="borrower_city" name="borrower_city" placeholder="City" value="{{old('borrower_city') ? old('borrower_city') : $data->borrower_city}}" readonly>
                                        @error('borrower_city') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="borrower_purpose" class="small text-muted mb-0">Borower purpose</label>
                                        <input type="text" class="form-control" id="borrower_purpose" name="borrower_purpose" placeholder="Purpose" value="{{old('borrower_purpose') ? old('borrower_purpose') : 'e-signing by borrower'}}" readonly>
                                        @error('borrower_purpose') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- <div class="col-12">
                                    <p class="small text-muted">Co-Borrower information</p>
                                </div> --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="co_borrower_name" class="small text-muted mb-0">Co-Borower name</label>
                                        <input type="text" class="form-control" id="co_borrower_name" name="co_borrower_name" placeholder="Name" value="{{old('co_borrower_name') ? old('co_borrower_name') : $data->co_borrower_name}}" readonly>
                                        @error('co_borrower_name') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="co_borrower_email" class="small text-muted mb-0">Co-Borower email</label>
                                        <input type="email" class="form-control" id="co_borrower_email" name="co_borrower_email" placeholder="Email" value="{{old('co_borrower_email') ? old('co_borrower_email') : $data->co_borrower_email}}" readonly>
                                        @error('co_borrower_email') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="co_borrower_city" class="small text-muted mb-0">Co-Borower city</label>
                                        <input type="text" class="form-control" id="co_borrower_city" name="co_borrower_city" placeholder="City" value="{{old('co_borrower_city') ? old('co_borrower_city') : $data->co_borrower_city}}" readonly>
                                        @error('co_borrower_city') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="co_borrower_purpose" class="small text-muted mb-0">Co-Borower purpose</label>
                                        <input type="text" class="form-control" id="co_borrower_purpose" name="co_borrower_purpose" placeholder="Purpose" value="{{old('co_borrower_purpose') ? old('co_borrower_purpose') : 'e-signing by co-borrower'}}" readonly>
                                        @error('co_borrower_purpose') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- <div class="col-12">
                                    <p class="small text-muted">Authorised Signatory information</p>
                                </div> --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="authorised_signatory_name" class="small text-muted mb-0">Authorised Signatory name</label>
                                        <input type="text" class="form-control" id="authorised_signatory_name" name="authorised_signatory_name" placeholder="Name" value="Abhishek Tantia">

                                        {{-- <input type="text" class="form-control" id="authorised_signatory_name" name="authorised_signatory_name" placeholder="Name" value="{{old('authorised_signatory_name') ? old('authorised_signatory_name') : $data->authorised_signatory_name}}"> --}}
                                        @error('authorised_signatory_name') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="authorised_signatory_email" class="small text-muted mb-0">Authorised Signatory email</label>
                                        <input type="email" class="form-control" id="authorised_signatory_email" name="authorised_signatory_email" placeholder="Email" value="abhishek.tantia@peerlessfinance.in">

                                        {{-- <input type="email" class="form-control" id="authorised_signatory_email" name="authorised_signatory_email" placeholder="Email" value="{{ old('authorised_signatory_email') }}"> --}}

                                        @error('authorised_signatory_email') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="authorised_signatory_city" class="small text-muted mb-0">Authorised Signatory city</label>
                                        <input type="text" class="form-control" id="authorised_signatory_city" name="authorised_signatory_city" placeholder="City" value="{{ old('authorised_signatory_city') }}">
                                        @error('authorised_signatory_city') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="authorised_signatory_purpose" class="small text-muted mb-0">Authorised Signatory purpose</label>
                                        <input type="text" class="form-control" id="authorised_signatory_purpose" name="authorised_signatory_purpose" placeholder="Purpose" value="{{old('authorised_signatory_purpose') ? old('authorised_signatory_purpose') : 'e-signing by authorised signatory'}}">
                                        @error('authorised_signatory_purpose') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-right">
                                    <button type="submit" class="btn btn-sm btn-primary">Save changes</button>
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

    </script>
@endsection