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
                                <a href="{{ route('user.employee.list') }}" class="btn btn-sm btn-primary"> <i
                                        class="fas fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="form-horizontal" method="POST"
                                action="{{ route('user.employee.update', $data->user->id) }}" id="profile-form">
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
                                        <label for="employee_id" class="col-form-label">Employee id <span
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
                                            value="{{ $data->user->email }}" disabled>
                                        <p class="small text-muted mt-2 mb-0">Email id cannot be changed once registered</p>
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
                                        <label for="department" class="col-form-label">Department <span
                                                class="text-danger">*</span></label>
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
                                        <label for="designation" class="col-form-label">Designation <span
                                                class="text-danger">*</span></label>
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
                                        <label for="user_type" class="col-form-label">Role <span
                                                class="text-danger">*</span></label>
                                        <select name="user_type" id="user_type"
                                            class="form-control @error('user_type') {{ 'is-invalid' }} @enderror">
                                            <option value="" hidden selected>Select role</option>
                                            @foreach ($data->user_type as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $data->user->user_type == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_type')
                                            <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="parent_id" class="col-form-label">Manager</label>
                                        <select name="parent_id" id="parent_id"
                                            class="form-control @error('parent_id') {{ 'is-invalid' }} @enderror">
                                            <option value="" hidden selected>Select reporting person</option>
                                            @foreach ($data->users as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $data->user->parent_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name . ' - ' . $item->type->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('parent_id')
                                            <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
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
