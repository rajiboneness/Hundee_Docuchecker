@extends('layouts.auth.master')

@section('title', 'Edit Borrower')

@section('content')
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
                                <a href="{{ route('user.borrower.list') }}" class="btn btn-sm btn-primary"> <i class="fas fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="form-horizontal" method="POST"
                                action="{{ route('user.borrower.update', $data->user->id) }}" id="profile-form">
                                @csrf
                                {{-- <div class="form-group row">
                                    <label for="application_id" class="col-sm-2 col-form-label">App ID<span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('application_id') {{ 'is-invalid' }} @enderror" id="application_id" name="application_id" placeholder="App ID" value="{{ old('application_id') ? old('application_id') : $data->user->application_id }}">
                                        @error('application_id') <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div> --}}
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Name <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-1">
                                        <select class="form-control px-0" id="name_prefix" name="name_prefix">
                                            <option value="" hidden selected>Select Prefix</option>
                                            @foreach ($APP_data->namePrefix as $item)
                                                <option value="{{ $item }}"
                                                    {{ $data->user->name_prefix == $item ? 'selected' : '' }}>
                                                    {{ ucwords($item) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text"
                                            class="form-control @error('first_name') {{ 'is-invalid' }} @enderror"
                                            id="first_name" name="first_name" placeholder="First name"
                                            value="{{ old('first_name') ? old('first_name') : $data->user->first_name }}"
                                            autofocus>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text"
                                            class="form-control @error('middle_name') {{ 'is-invalid' }} @enderror"
                                            id="middle_name" name="middle_name" placeholder="Middle name"
                                            value="{{ old('middle_name') ? old('middle_name') : $data->user->middle_name }}"
                                            autofocus>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text"
                                            class="form-control @error('last_name') {{ 'is-invalid' }} @enderror"
                                            id="last_name" name="last_name" placeholder="Last name"
                                            value="{{ old('last_name') ? old('last_name') : $data->user->last_name }}"
                                            autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8 offset-sm-2">
                                        @error('name_prefix') <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                        @error('full_name') <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="gender" class="col-sm-2 col-form-label">Gender <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="gender" name="gender">
                                            <option value="" hidden selected>Select Gender</option>
                                            @foreach ($APP_data->genderList as $item)
                                                <option value="{{ $item }}"
                                                    {{ $data->user->gender == $item ? 'selected' : '' }}>
                                                    {{ ucwords($item) }}</option>
                                            @endforeach
                                        </select>
                                        @error('gender') <p class="small mb-0 text-danger">{{ $message }}</p>@enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="date_of_birth" class="col-sm-2 col-form-label">Date of birth <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        @php
                                            $date_of_birth = date('Y-m-d', strtotime($data->user->date_of_birth));
                                        @endphp
                                        <input type="date"
                                            class="form-control @error('date_of_birth') {{ 'is-invalid' }} @enderror"
                                            id="date_of_birth" name="date_of_birth" placeholder="Date of birth"
                                            value="{{ old('date_of_birth') ? old('date_of_birth') : $date_of_birth }}">
                                        @error('date_of_birth') <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="email"
                                            class="form-control @error('email') {{ 'is-invalid' }} @enderror" id="email"
                                            name="email" placeholder="Email ID"
                                            value="{{ old('email') ? old('email') : $data->user->email }}">
                                        @error('email') <p class="small mb-0 text-danger">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="mobile" class="col-sm-2 col-form-label">Mobile number <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control @error('mobile') {{ 'is-invalid' }} @enderror"
                                            id="mobile" name="mobile" placeholder="Phone number"
                                            value="{{ old('mobile') ? old('mobile') : $data->user->mobile }}">
                                        @error('mobile') <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="pan_card_number" class="col-sm-2 col-form-label">PAN card number <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control @error('pan_card_number') {{ 'is-invalid' }} @enderror"
                                            id="pan_card_number" name="pan_card_number" placeholder="Pan card number"
                                            value="{{ old('pan_card_number') ? old('pan_card_number') : $data->user->pan_card_number }}">
                                        @error('pan_card_number') <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="occupation" class="col-sm-2 col-form-label">Occupation <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control @error('occupation') {{ 'is-invalid' }} @enderror"
                                            id="occupation" name="occupation" placeholder="Occupation"
                                            value="{{ old('occupation') ? old('occupation') : $data->user->occupation }}">
                                        @error('occupation') <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="marital_status" class="col-sm-2 col-form-label">Marital status <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="marital_status" name="marital_status">
                                            <option value="" hidden selected>Select Marital status</option>
                                            @foreach ($APP_data->maritalStatus as $item)
                                                <option value="{{ $item }}"
                                                    {{ $data->user->marital_status == $item ? 'selected' : '' }}>
                                                    {{ ucwords($item) }}</option>
                                            @endforeach
                                        </select>
                                        @error('marital_status') <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="street_address" class="col-sm-2 col-form-label">Permanent Address <span class="text-danger">*</span></label>

                                    <div class="col-sm-10 mt-2">
                                        <input type="text" class="form-control @error('KYC_PINCODE') {{ 'is-invalid' }} @enderror" id="KYC_PINCODE" name="KYC_PINCODE" placeholder="Pincode" value="{{ old('KYC_PINCODE',$data->user->KYC_PINCODE) }}">

                                        @error('KYC_PINCODE') <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-sm-10 offset-sm-2 mt-2">
                                        <select type="text" class="form-control @error('KYC_POST_OFFICE') {{ 'is-invalid' }} @enderror" id="KYC_POST_OFFICE" name="KYC_POST_OFFICE" value="{{ old('KYC_POST_OFFICE',$data->user->KYC_POST_OFFICE) }}">
                                            <option value="">--Post Office(Enter Pincode)--</option>
                                        </select>

                                        @error('KYC_POST_OFFICE') <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-sm-10 offset-sm-2 mt-2">
                                        <input type="text" class="form-control @error('KYC_HOUSE_NO') {{ 'is-invalid' }} @enderror" id="KYC_HOUSE_NO" name="KYC_HOUSE_NO" placeholder="House number" value="{{ old('KYC_HOUSE_NO',$data->user->KYC_HOUSE_NO) }}">

                                        @error('KYC_HOUSE_NO') <p class="small mb-0 text-danger">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="col-sm-10 offset-sm-2 mt-2">
                                        <textarea class="form-control @error('KYC_Street') {{ 'is-invalid' }} @enderror" id="KYC_Street" name="KYC_Street" placeholder="Street">{{ old('KYC_Street',$data->user->KYC_Street) }}</textarea>
                                        @error('KYC_Street') <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-sm-10 offset-sm-2 mt-2">
                                        <input type="text" class="form-control @error('KYC_LOCALITY') {{ 'is-invalid' }} @enderror" id="KYC_LOCALITY" name="KYC_LOCALITY" placeholder="Locality" value="{{ old('KYC_LOCALITY',$data->user->KYC_LOCALITY) }}">

                                        @error('KYC_LOCALITY') <p class="small mb-0 text-danger">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="col-sm-10 offset-sm-2 mt-2">
                                        <input type="text" class="form-control @error('KYC_District') {{ 'is-invalid' }} @enderror" id="KYC_District" name="KYC_District" placeholder="District" value="{{ old('KYC_District',$data->user->KYC_District) }}">

                                        @error('KYC_District') <p class="small mb-0 text-danger">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="col-sm-10 offset-sm-2 mt-2">
                                        <input type="text" class="form-control @error('KYC_CITY') {{ 'is-invalid' }} @enderror" id="KYC_CITY" name="KYC_CITY" placeholder="City" value="{{ old('KYC_CITY',$data->user->KYC_CITY) }}">

                                        @error('KYC_CITY') <p class="small mb-0 text-danger">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="col-sm-10 offset-sm-2 mt-2">
                                        <input type="text" class="form-control @error('KYC_State') {{ 'is-invalid' }} @enderror" id="KYC_State" name="KYC_State" placeholder="State" value="{{ old('KYC_State',$data->user->KYC_State) }}">

                                        @error('KYC_State') <p class="small mb-0 text-danger">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="col-sm-10 offset-sm-2 mt-2">
                                        <input type="text" class="form-control @error('KYC_Country') {{ 'is-invalid' }} @enderror" id="KYC_Country" name="KYC_Country" placeholder="Country" value="{{ old('KYC_Country',$data->user->KYC_Country) }}">

                                        @error('KYC_Country') <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                </div>

                                <div class="form-group row" style="position: sticky;bottom: -1px;z-index: 99;background-color: #e9e9e9;text-align: right;padding: 5px 0;">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="submit" class="btn btn-sm btn-primary">Update changes <i class="fas fa-edit"></i></button>
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
        function autoFillPincodeWithPreviousData(){
            var pincode = $('input[name="KYC_PINCODE"]').val();

            if (pincode.length == 6) {
				$('#pinResp').text('Please wait...');
                $('input[name="KYC_PINCODE"]').css('borderColor', '#4caf50').css('boxShadow', '0 0 0 0.2rem #4caf5057');

                $.ajax({
                    url: 'https://api.postalpincode.in/pincode/'+pincode,
                    method: 'GET',
                    success: function(result){
                        if(result[0].Message != 'No records found'){
                            if(result[0].PostOffice.length > 0){
                                $('#KYC_POST_OFFICE').html('');
                                result[0].PostOffice.forEach(element => {
                                    if(element.Name == "{{old('KYC_POST_OFFICE',$data->user->KYC_POST_OFFICE)}}")
                                        $('#KYC_POST_OFFICE').append('<option data-state="'+element.State+'" data-country="'+element.Country+'" data-dist="'+element.District+'" selected value="'+element.Name+'">'+element.Name+'</option>');
                                    else
                                        $('#KYC_POST_OFFICE').append('<option data-state="'+element.State+'" data-country="'+element.Country+'" data-dist="'+element.District+'" value="'+element.Name+'">'+element.Name+'</option>');
                                });
                            }
                            if(result[0].PostOffice[0].State == "{{ old('KYC_State',$data->user->KYC_State) }}")
                                $('input[name="KYC_State"]').val(result[0].PostOffice[0].State).attr('disabled', false);
                            
                            if(result[0].PostOffice[0].Country == "{{ old('KYC_Country',$data->user->KYC_Country) }}")
                                $('input[name="KYC_Country"]').val(result[0].PostOffice[0].Country).attr('disabled', false);
                            
                            if(result[0].PostOffice[0].District == "{{ old('KYC_District',$data->user->KYC_District) }}")
                                $('input[name="KYC_District"]').val(result[0].PostOffice[0].District).attr('disabled', false);

                        }else{
                            $('#KYC_POST_OFFICE').html('<option value="">--No Post Office Found--</option>')
                        }
						// $('input[name="area"]').val(result[0].PostOffice[0].District).attr('disabled', false);
                        // console.log(result[0].PostOffice[0]);
                    }
                });
				$('#pinResp').text('');
            } else {
                $('input[name="KYC_PINCODE"]').css('borderColor', 'red').css('boxShadow', '0 0 0 0.2rem #dc34345c');
				$('input[name="KYC_State"]').val('');
				$('input[name="KYC_Country"]').val('');
                $('input[name="KYC_District"]').val('');
            }
        }


        function autoFillPincode(){
            var pincode = $('input[name="KYC_PINCODE"]').val();

            if (pincode.length == 6) {
				$('#pinResp').text('Please wait...');
                $('input[name="KYC_PINCODE"]').css('borderColor', '#4caf50').css('boxShadow', '0 0 0 0.2rem #4caf5057');

                $.ajax({
                    url: 'https://api.postalpincode.in/pincode/'+pincode,
                    method: 'GET',
                    success: function(result){
                        if(result[0].Message != 'No records found'){
                            if(result[0].PostOffice.length > 0){
                                $('#KYC_POST_OFFICE').html('');
                                result[0].PostOffice.forEach(element => {
                                    if(element.Name == "{{old('KYC_POST_OFFICE',$data->user->KYC_POST_OFFICE)}}")
                                        $('#KYC_POST_OFFICE').append('<option data-state="'+element.State+'" data-country="'+element.Country+'" data-dist="'+element.District+'" selected value="'+element.Name+'">'+element.Name+'</option>');
                                    else
                                        $('#KYC_POST_OFFICE').append('<option data-state="'+element.State+'" data-country="'+element.Country+'" data-dist="'+element.District+'" value="'+element.Name+'">'+element.Name+'</option>');
                                });
                            }
                            $('input[name="KYC_State"]').val(result[0].PostOffice[0].State).attr('disabled', false);
                            $('input[name="KYC_Country"]').val(result[0].PostOffice[0].Country).attr('disabled', false);
                            $('input[name="KYC_District"]').val(result[0].PostOffice[0].District).attr('disabled', false);
                        }else{
                            $('#KYC_POST_OFFICE').html('<option value="">--No Post Office Found--</option>')
                        }
						// $('input[name="area"]').val(result[0].PostOffice[0].District).attr('disabled', false);
                        // console.log(result[0].PostOffice[0]);
                    }
                });
				$('#pinResp').text('');
            } else {
                $('input[name="KYC_PINCODE"]').css('borderColor', 'red').css('boxShadow', '0 0 0 0.2rem #dc34345c');
				$('input[name="KYC_State"]').val('');
				$('input[name="KYC_Country"]').val('');
                $('input[name="KYC_District"]').val('');
            }
        }

        $('input[name="KYC_PINCODE"]').on('keyup', ()=>{
            autoFillPincode();
        });
        
        autoFillPincodeWithPreviousData();
    </script>
@endsection
