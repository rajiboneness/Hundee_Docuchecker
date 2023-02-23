@extends('layouts.auth.master')

@section('title', 'Edit employee')

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
                                <a href="{{ route('user.emp.list') }}" class="btn btn-sm btn-primary"> <i
                                        class="fas fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="form-horizontal" method="POST"
                                action="{{ route('user.emp.update', $data->user->id) }}" id="profile-form">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="name" class="col-form-label">Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('name') {{ 'is-invalid' }} @enderror" id="name"
                                            name="name" placeholder="Full name"
                                            value="{{ old('name') ? old('name') : $data->user->name }}">
                                        @error('name')
                                            <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="employee_id" class="col-form-label">Employee Code <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('employee_id') {{ 'is-invalid' }} @enderror"
                                            id="employee_id" name="employee_id" placeholder="Employee id"
                                            value="{{ old('employee_id') ? old('employee_id') : $data->user->emp_id }}">
                                        @error('employee_id')
                                            <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="email" class="col-form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email"
                                            class="form-control @error('email') {{ 'is-invalid' }} @enderror"
                                            id="email" name="email" placeholder="Email ID"
                                            value="{{ $data->user->email }}">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="phone_number" class="col-form-label">Phone number</label>
                                        <input type="text"
                                            class="form-control @error('phone_number') {{ 'is-invalid' }} @enderror"
                                            id="phone_number" name="phone_number" placeholder="Phone number"
                                            value="{{ old('phone_number') ? old('phone_number') : $data->user->mobile }}">
                                        @error('phone_number')
                                            <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="department" class="col-form-label">Department(optional)</label>
                                        <select name="department" id="department"
                                            class="form-control @error('department') {{ 'is-invalid' }} @enderror">
                                            <option value="" hidden selected>Select department</option>
                                            @foreach ($data->departments as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $data->user->department_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('department')
                                            <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="designation" class="col-form-label">Designation (optional)</label>
                                        <select name="designation" id="designation"
                                            class="form-control @error('designation') {{ 'is-invalid' }} @enderror">
                                            <option value="" hidden selected>Select designation</option>
                                            @foreach ($data->designations as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $data->user->designation_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('designation')
                                            <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="reporting_manager" class="col-form-label">Manager</label>
                                        <select name="reporting_manager" id="reporting_manager"
                                            class="form-control @error('reporting_manager') {{ 'is-invalid' }} @enderror">
                                            <option value="" selected disabled>Select Reporting Manager</option>
                                            <option value="">No Reporting Manager</option>
                                            @foreach ($data->users as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $data->user->reporting_manager == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('reporting_manager')
                                            <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="office" class="col-form-label">Office <span
                                                class="text-danger">*</span></label>
                                        <select name="office" id="office"
                                            class="form-control @error('office') {{ 'is-invalid' }} @enderror">
                                            <option value="" hidden selected>Select office</option>
                                            @foreach ($data->offices as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $data->user->office_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('office')
                                            <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label for="designation" class="col-form-label">Stree Address </label>
                                        <textarea name="street_address" id="street_address" cols="30" rows="2" class="form-control">{{ $data->user->street_address }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
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
    <script></script>
@endsection
