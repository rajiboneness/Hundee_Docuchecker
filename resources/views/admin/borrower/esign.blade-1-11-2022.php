@extends('layouts.auth.master')

@section('title', 'E-signing')

@section('content')
@php
    // if no co-borrowers
    if ($data->co_borrower_name == "" && $data->co_borrower_name2 == "") {
        $documentInfo = "This document is to be e-signed by borrower and authorised signatory";
        $coBorrowersCount = 0;
    } else {
        // if one co-borrower
        if ($data->co_borrower_name2 == "") {
            $documentInfo = "This document is to be e-signed by borrower, co-borrower and authorised signatory";
            $coBorrowersCount = 1;
        }
        // if 2 co-borrowers
        else {
            $documentInfo = "This document is to be e-signed by borrower, co-borrowers and authorised signatory";
            $coBorrowersCount = 2;
        }
    }
@endphp

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
                        <form action="{{ route('user.esign.submit') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="unique_id" value="{{ time() }}">
                            {{-- DOCUMENT --}}
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
                                        <input type="text" class="form-control" id="documentInformation" name="documentInformation" placeholder="Enter document details" value="{{ old('documentInformation') ? old('documentInformation') : $documentInfo }}">
                                        @error('documentInformation') <p class="small text-danger mt-2">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- BORROWER --}}
                            <div class="row">
                                <div class="col-sm-1">
                                    <div class="form-check">
                                        <label class="form-check-label" for="is_borrower">
                                            <input class="" onclick="checkin(this)" type="checkbox" name="is_borrower" value="1" id="is_borrower" {{$b_checked}} {{$b_disabled}}>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-11">
                                    <div class="row">
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
                                </div>
                            </div>

                            {{-- @php print_r(coborrowerAndgurantor($rfqId)); exit(); @endphp --}}

                            {{-- CO-BORROWER --}}
                            @if(coborrowerAndgurantor($rfqId)['name_cb1'] != '' )
                            <div class="row">
                                <div class="col-sm-1">
                                    <div class="form-check">
                                        <label class="form-check-label" for="is_co_borrower">
                                            <input class="" onclick="checkin(this)" type="checkbox" name="is_co_borrower" value="1" id="is_co_borrower"  {{$cb_checked}} {{$cb_disabled}}>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-11">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="co_borrower_name" class="small text-muted mb-0">Co-Borower 1 name</label>
                                                <input type="text" class="form-control" id="co_borrower_name" name="co_borrower_name" placeholder="Name" value="{{old('co_borrower_name') ? old('co_borrower_name') : $data->co_borrower_name}}" readonly>
                                                @error('co_borrower_name') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="co_borrower_email" class="small text-muted mb-0">Co-Borower 1 email</label>
                                                <input type="email" class="form-control" id="co_borrower_email" name="co_borrower_email" placeholder="Email" value="{{old('co_borrower_email') ? old('co_borrower_email') : $data->co_borrower_email}}" readonly>
                                                @error('co_borrower_email') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="co_borrower_city" class="small text-muted mb-0">Co-Borower 1 city</label>
                                                <input type="text" class="form-control" id="co_borrower_city" name="co_borrower_city" placeholder="City" value="{{old('co_borrower_city') ? old('co_borrower_city') : $data->co_borrower_city}}" readonly>
                                                @error('co_borrower_city') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="co_borrower_purpose" class="small text-muted mb-0">Co-Borower 1 purpose</label>
                                                <input type="text" class="form-control" id="co_borrower_purpose" name="co_borrower_purpose" placeholder="Purpose" value="{{old('co_borrower_purpose') ? old('co_borrower_purpose') : 'e-signing by co-borrower'}}" readonly>
                                                @error('co_borrower_purpose') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            @endif

                            {{-- CO-BORROWER 2 --}}
                            @if(coborrowerAndgurantor($rfqId)['name_cb2'] != '')
                            <div class="row">
                                <div class="col-sm-1">
                                    <div class="form-check">
                                        <label class="form-check-label" for="co_borrower_2" >
                                            <input class="" onclick="checkin(this)" type="checkbox" name="co_borrower_2" value="1" id="co_borrower_2" {{$cb2_checked}} {{$cb2_disabled}}>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-11">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="co_borrower_name2" class="small text-muted mb-0">Co-Borower 2 name</label>
                                                <input type="text" class="form-control" id="co_borrower_name2" name="co_borrower_name2" placeholder="Name" value="{{old('co_borrower_name2') ? old('co_borrower_name2') : $data->co_borrower_name2}}" readonly>
                                                @error('co_borrower_name2') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="co_borrower_email2" class="small text-muted mb-0">Co-Borower 2 email</label>
                                                <input type="email" class="form-control" id="co_borrower_email2" name="co_borrower_email2" placeholder="Email" value="{{old('co_borrower_email2') ? old('co_borrower_email2') : $data->co_borrower_email2}}" readonly>
                                                @error('co_borrower_email2') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="co_borrower_city2" class="small text-muted mb-0">Co-Borower 2 city</label>
                                                <input type="text" class="form-control" id="co_borrower_city2" name="co_borrower_city2" placeholder="City" value="{{old('co_borrower_city2') ? old('co_borrower_city2') : $data->co_borrower_city2}}" readonly>
                                                @error('co_borrower_city2') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="co_borrower_purpose2" class="small text-muted mb-0">Co-Borower 2 purpose</label>
                                                <input type="text" class="form-control" id="co_borrower_purpose2" name="co_borrower_purpose2" placeholder="Purpose" value="{{old('co_borrower_purpose2') ? old('co_borrower_purpose2') : 'e-signing by co-borrower'}}" readonly>
                                                @error('co_borrower_purpose2') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- GUARANTOR --}}
                            @if(coborrowerAndgurantor($rfqId)['name_gur'] != '')
                            <div class="row">
                                <div class="col-sm-1">
                                    <div class="form-check">
                                        <label class="form-check-label" for="guarantor" >
                                            <input class="" onclick="checkin(this)" type="checkbox" name="guarantor" value="1" id="guarantor">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-11">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="guarantor_name" class="small text-muted mb-0">Guarantor name</label>
                                                <input type="text" class="form-control" id="guarantor_name" name="guarantor_name" placeholder="Name" value="{{old('guarantor_name') ? old('guarantor_name') : $data->guarantor_name}}" readonly>
                                                @error('guarantor_name') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="guarantor_email" class="small text-muted mb-0">Guarantor email</label>
                                                <input type="email" class="form-control" id="guarantor_email" name="guarantor_email" placeholder="Email" value="{{old('guarantor_email') ? old('guarantor_email') : $data->guarantor_email}}" readonly>
                                                @error('guarantor_email') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="guarantor_city" class="small text-muted mb-0">Guarantor city</label>
                                                <input type="text" class="form-control" id="guarantor_city" name="guarantor_city" placeholder="City" value="{{old('guarantor_city') ? old('guarantor_city') : $data->guarantor_city}}" readonly>
                                                @error('guarantor_city') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="guarantor_purpose" class="small text-muted mb-0">Guarantor purpose</label>
                                                <input type="text" class="form-control" id="guarantor_purpose" name="guarantor_purpose" placeholder="Purpose" value="{{old('guarantor_purpose') ? old('guarantor_purpose') : 'e-signing by Guarantor'}}" readonly>
                                                @error('guarantor_purpose') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Executant data --}}
                            @if(CheckVernaculationData($borrowerAgreementId))
                            <div class="row">
                                <div class="col-sm-1">
                                    <div class="form-check">
                                        <label class="form-check-label" for="executant">
                                            <input class="" onclick="checkin(this)" type="checkbox" name="executant" value="1" id="executant">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-11">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="executant_name" class="small text-muted mb-0">Executant name</label>
                                                <input type="text" class="form-control" id="executant_name" name="executant_name" placeholder="Name" value="{{old('executant_name') ? old('executant_name') : VernaculationData($borrowerAgreementId)['executant_name']}}" readonly>
                                                @error('executant_name') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="executant_email" class="small text-muted mb-0">Executant email</label>
                                                <input type="email" class="form-control" id="executant_email" name="executant_email" placeholder="Email" value="{{old('executant_email') ? old('executant_email') : VernaculationData($borrowerAgreementId)['executant_email']}}" readonly>
                                                @error('executant_email') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="executant_city" class="small text-muted mb-0">Executant city</label>
                                                <input type="text" class="form-control" id="executant_city" name="executant_city" placeholder="City" value="{{old('executant_city') ? old('executant_city') : ''}}">
                                                @error('executant_city') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="executant_purpose" class="small text-muted mb-0">Executant purpose</label>
                                                <input type="text" class="form-control" id="executant_purpose" name="executant_purpose" placeholder="Purpose" value="{{old('executant_purpose') ? old('executant_purpose') : 'e-signing by executant'}}" readonly>
                                                @error('executant_purpose') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            {{-- AUTHORISED SIGNATORY --}}
                            <div class="row">
                                <div class="col-sm-1">
                                    <div class="form-check">
                                        <label class="form-check-label" for="is_authorised_signatory">
                                            <input class="" onclick="checkin(this)" type="checkbox" value="1" name="is_authorised_signatory" id="is_authorised_signatory" {{$as_checked}} {{$as_disabled}} >
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-11">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="authorised_signatory_name" class="small text-muted mb-0">Authorised Signatory name</label>
                                                <select name="authorised_signatory_name" id="authorised_signatory_name" onchange="setValue(this)" id="authorised_signatory_name" class="form-control">
                                                    <option value="">Select Authorised signatory</option>
                                                    @foreach ($all_auth_sig as $item)
                                                        <option value="{{$item->name}}" data-email="{{$item->email}}" data-city="{{$item->city}}" {{ (old('authorised_signatory_name') == $item->name) ? 'selected' : '' }}>{{$item->name}}</option>      
                                                    @endforeach
                                                </select>
                                                @error('authorised_signatory_name') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="authorised_signatory_email" class="small text-muted mb-0">Authorised Signatory email</label>
                                                <input type="email" class="form-control" id="authorised_signatory_email" name="authorised_signatory_email" placeholder="select authorised signatory first">
        
                                                @error('authorised_signatory_email') <p class="small text-danger mt-2">{{$message}}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="authorised_signatory_city" class="small text-muted mb-0">Authorised Signatory city</label>
                                                <input type="text" class="form-control" id="authorised_signatory_city" name="authorised_signatory_city" placeholder="select authorised signatory first">
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
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-right">
                                    <input type="hidden" name="rfqId" value="{{$rfqId}}">
                                    <button type="submit" {{$submit_disabled}} class="btn btn-primary">SEND Email for E-Signature</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="borrowers-table-wrapper">
                            <table class="table table-sm table-bordered table-hover" id="borrowers-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Sl No.</th>
                                        <th>Sent To</th>
                                        <th>Sent At</th>
                                        <th>Signed</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="loadBorrowers">
                                    @forelse ($e_signatures as $index =>  $item)
                                        <tr id="tr_{{ $item['id'] }}">
                                            <td>{{ ($index + 1) }}</td>
                                            <td>{{ $item['unique_id'] }}</td>
                                            <td>{{ ucwords(str_replace("_"," ",$item['user_type'])) }}: {{ $item['user_name'] }} ({{$item['user_email']}})</td>
                                            <td>{{ date('d/m/Y h:i A', strtotime($item['created_at'])) }}</td>
                                            <td>
                                                @if(!empty($item['signed_pdf']))
                                                <span class="badge bg-success">Yes</span>
                                                @else
                                                <span class="badge bg-danger">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($item['signed_pdf']))
                                                <a href="{{$item['signed_pdf']}}" target="_blank" class="btn btn-sm btn-success">Signed Document</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="100%"><em>No records found</em></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
    <script>
        function setValue(){
            var element =  $('#authorised_signatory_name').find('option:selected');
            const email = element.attr('data-email');
            const city = element.attr('data-city');
            $('#authorised_signatory_email').val(email);
            $('#authorised_signatory_city').val(city);
        }

        setValue()

        function checkin(x){
            if($(x).prop('checked')==true){
                $('input[type="checkbox"]').prop('checked',false);
                $(x).prop('checked',true);
            }
        }
    </script>
@endsection