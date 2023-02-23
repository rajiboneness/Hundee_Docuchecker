@extends('layouts.auth.master')

@section('title', request()->query('term') == 'Relationship Manager' ? "Relationship Manager list" : "Employee list")
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"></h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                    <i class="fas fa-expand"></i>
                                </button>
                                @if(request()->query('term') == 'Relationship Manager')
                                    <a href="{{ route('user.emp.create') }}" class="btn btn-sm btn-primary"> <i
                                        class="fas fa-plus"></i> Create New Relationship Manager</a>
                                @else
                                    <a href="{{ route('user.emp.create') }}" class="btn btn-sm btn-primary"> <i
                                    class="fas fa-plus"></i> Create new employee</a>
                                    <a href="{{ route('user.department.list') }}" class="btn btn-sm btn-primary"> <i
                                            class="fas fa-cog"></i> Department</a>
                                    <a href="{{ route('user.designation.list') }}" class="btn btn-sm btn-primary"> <i
                                            class="fas fa-cog"></i> Designation</a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-7">
                                    <p class="small text-muted">Displaying {{ $data->firstItem() }} to
                                        {{ $data->lastItem() }} out of {{ $data->total() }} entries</p>
                                </div>
                                <div class="col-sm-5 text-right">
                                    <form action="{{ route('user.emp.list') }}" method="GET" role="search">
                                        <div class="input-group">
                                            <input type="search" class="form-control form-control-sm" name="term"
                                                placeholder="What are you looking for..." id="term"
                                                value="{{ app('request')->input('term') }}" autocomplete="off">
                                            <div class="input-group-append">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-info btn-sm rounded-0" type="submit"
                                                        title="Search projects">
                                                        <i class="fas fa-search"></i> Search
                                                    </button>
                                                </span>
                                                <a href="{{ route('user.emp.list') }}">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-danger btn-sm rounded-0" type="button"
                                                            title="Refresh page"> Reset <i
                                                                class="fas fa-sync-alt"></i></button>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <table class="table table-sm table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th width="20%">{{ (request()->query('term') == 'Relationship Manager')? "RM Name" : "Name" }}</th>
                                        <th width="10%">Emp Code</th>
                                        <th>Contact</th>
                                        @if(request()->query('term') == 'Relationship Manager')
                                            <th>Branch</th>
                                        @endif
                                        <th>Reporting Manager</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $index => $item)
                                        <tr id="tr_{{ $item->id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                @if(request()->query('term') == 'Relationship Manager')
                                                {{ $item->name }}
                                                <br>
                                                <span class="badge badge-dark text-light">{{ "Code: ".$item->emp_id }}</span>
                                                @else
                                                <div class="user-profile-holder">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ asset($item->image_path) }}"
                                                            alt="user-image-{{ $item->id }}">
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <p class="name">{{ $item->name }}</p>
                                                    </div>
                                                </div>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $item->emp_id}}
                                            </td>
                                            <td>
                                                <p class="small text-dark mb-1"><i class="fas fa-envelope mr-2"></i>
                                                    {{ $item->email }}</p>
                                                <p class="small text-dark mb-0">@php
                                                    if (!empty($item->mobile)) {
                                                        echo '<i class="fas fa-phone fa-rotate-90 mr-2"></i> ' . $item->mobile;
                                                    } else {
                                                        echo '<i class="fas fa-phone fa-rotate-90 text-danger"></i>';
                                                    }
                                                @endphp</p>
                                            </td>
                                            @if(request()->query('term') == 'Relationship Manager')
                                                <td>{{ $item->office->name }}</td>
                                            @endif
                                            <td>
                                                {{ $item->reporting_manager == 0 ? 'NA' : $item->manager->name }}
                                            </td>
                                            <td class="text-right">
                                                <div class="single-line">
                                                    <a href="javascript: void(0)" class="badge badge-dark action-button"
                                                        title="View"
                                                        onclick="viewDeta1ls('{{ route('user.emp.show') }}', {{ $item->id }})">View</a>
                                                        {{-- @if ($item->block == 0)
                                                            <a href="javascript: void(0)"
                                                                class="badge badge-dark action-button block-button"
                                                                title="Block"
                                                                onclick="confirm4lert('{{ route('user.emp.block') }}', {{ $item->id }}, 'block')">Active</a>
                                                        @else
                                                            <a href="javascript: void(0)"
                                                                class="badge badge-danger action-button block-button"
                                                                title="Block"
                                                                onclick="confirm4lert('{{ route('user.employee.block') }}', {{ $item->id }}, 'activate')">Blocked</a>
                                                        @endif --}}

                                                        <a href="{{ route('user.emp.edit', $item->id) }}"
                                                            class="badge badge-dark action-button" title="Edit">Edit</a>

                                                        <a href="javascript: void(0)" class="badge badge-dark action-button"
                                                            title="Delete"
                                                            onclick="confirm4lert('{{ route('user.emp.destroy') }}', {{ $item->id }}, 'delete')">Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%" class="text-center text-muted"><em>No records found</em></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="pagination-view">
                                {{ $data->appends(request()->query())->links() }}
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
        function viewDeta1ls(route, id) {
            $.ajax({
                url: route,
                method: 'post',
                data: {
                    '_token': '{{ csrf_token() }}',
                    id: id
                },
                success: function(result) {
                    let content = '';
                    if (result.error == false) {
                        let mobileShow = parentShow = departmentShow = designationShow = officeShow =
                            '<em class="text-muted">No data</em>';
                        if (result.data.mobile != null) {
                            mobileShow = result.data.mobile;
                        }
                        if (result.data.reporting_manager != null) {
                            parentShow = result.data.reporting_manager;
                        }

                        if (result.data.department != null) {
                            departmentShow = result.data.department;
                        }

                        if (result.data.designation != null) {
                            designationShow = result.data.designation;
                        }

                        if (result.data.office != null) {
                            officeShow = result.data.office;
                        }

                        content += '<div class="w-100 user-profile-holder mb-3"><img src="' + result.data
                            .image_path + '"></div>';
                        content += '<p class="text-muted small mb-1">Name</p><h6>' + result.data.name + '</h6>';
                        content += '<p class="text-muted small mb-1">Employee ID</p><h6>' + result.data.emp_id +
                            '</h6>';
                        content += '<p class="text-muted small mb-1">Email</p><h6>' + result.data.email +
                            '</h6>';
                        content += '<p class="text-muted small mb-1">Phone number</p><h6>' + mobileShow +
                            '</h6>';
                        content += '<p class="text-muted small mb-1">Manager</p><h6>' + parentShow + '</h6>';
                        content += '<p class="text-muted small mb-1">Department</p><h6>' + departmentShow +
                            '</h6>';
                        content += '<p class="text-muted small mb-1">Designation</p><h6>' + designationShow +
                            '</h6>';
                        content += '<p class="text-muted small mb-1">Office</p><h6>' + officeShow + '</h6>';
                    } else {
                        content += '<p class="text-muted small mb-1">No data found. Try again</p>';
                    }
                    $('#appendContent').html(content);
                    $('#userDetailsModalLabel').text('Employee details');
                    $('#userDetails').modal('show');
                }
            });
        }
    </script>
@endsection
