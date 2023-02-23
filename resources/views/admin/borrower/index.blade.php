@extends('layouts.auth.master')

@if( request()->input('show') == 'borrower & agreement' )
    @section('title', 'Borrower-Agreement list')
@else
    @section('title', 'Customers list')
@endif

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @if( request()->input('show') == 'borrower & agreement' )
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                    <i class="fas fa-expand"></i>
                                </button>
                                <a href="#csvExportBorrowersModal" data-toggle="modal" class="btn {{auth()->user()->user_type == 1 || auth()->user()->user_type == 3 ? '' : 'disabled'}} btn-sm btn-primary"> <i
                                        class="fas fa-file-csv"></i> Export CSV</a>
                            </div>
                        </div>
                        @endif
                        @if( request()->input('show') != 'borrower & agreement' )
                            <div class="card-header">
                                <h3 class="card-title"></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                    {{-- <a href="#csvUploadModalTest" data-toggle="modal" class="btn btn-sm btn-primary"> <i class="fas fa-file-csv"></i> Upload CSV test</a> --}}
                                    <a href="#csvExportModal" data-toggle="modal" class="btn {{auth()->user()->user_type == 1 || auth()->user()->user_type == 3 ? '' : 'disabled'}} btn-sm btn-primary"> <i
                                            class="fas fa-file-csv"></i> Export CSV</a>
                                    <a href="#csvUploadModal" data-toggle="modal" class="btn {{auth()->user()->user_type == 1 || auth()->user()->user_type == 3 ? '' : 'disabled'}} btn-sm btn-primary"> <i
                                            class="fas fa-file-csv"></i> Upload CSV</a>
                                    <a href="{{ route('user.borrower.create') }}" class="btn {{auth()->user()->user_type == 1 || auth()->user()->user_type == 3 ? '' : 'disabled'}} btn-sm btn-primary"> <i
                                            class="fas fa-plus"></i> Create new Customer</a>
                                </div>
                            </div>
                        @endif
                        <div class="card-body fixed-width">
                            @if (Session::has('message'))
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>{{ Session::get('message') }}</strong>
                                </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-sm-5">
                                    <p class="small text-muted">Displaying {{ $data->firstItem() }} to
                                        {{ $data->lastItem() }} out of {{ $data->total() }} entries</p>
                                </div>
                                <div class="col-sm-7 text-right">
                                    <form action="{{ route('user.borrower.list') }}" method="GET" role="search">
                                        <div class="input-group">
                                            <select name="show" onchange="$(this).parent().parent().submit()" class="form-control  form-control-sm mx-1">
                                                <option {{request()->input('show') == 'borrowers' ? 'selected' : ''}} value="borrowers">Customers</option>
                                                <option {{request()->input('show') == 'borrower & agreement' ? 'selected' : ''}} value="borrower & agreement">Borrowers & Agreement</option>
                                            </select>
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
                                                <a href="{{ route('user.borrower.list') }}">
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
                            <div class="borrowers-table-wrapper">
                                <table class="table table-sm table-bordered table-hover" id="borrowers-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            @if(request()->input('show') == 'borrower & agreement')
                                                <th>App. id's</th>
                                            @endif
                                            <th>Name</th>
                                            @if (request()->input('show') == 'borrower & agreement')    
                                                <th>Co Borrower</th>
                                                <th>Guarantor</th>
                                            @endif
                                            <th>Contact</th>
                                            <th>PAN card</th>
                                            <th>Address</th>
                                            @if (request()->input('show') == 'borrower & agreement')
                                                <th>Loan details</th>
                                            @else
                                                <th>Loan Action</th>
                                            @endif
                                            <th class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="loadBorrowers">
                                        @forelse ($data as $index => $item)
                                            @if(request()->input('show') == 'borrower & agreement')
                                                @if(getApplicationID($item->id, $item->agreement) != false)
                                                    <tr id="tr_{{ $item->id }}">
                                                        <td>{{ $index + 1 }}</td>
                                                        @if(request()->input('show') == 'borrower & agreement')
                                                        {{-- {{ $item->id }} --}}
                                                        @php
                                                        $getApp = App\Models\BorrowerAgreement::where('borrower_id', $item->id)->get();
                                                        @endphp
                                                            <td>
                                                                @foreach($getApp as $AppData)
                                                                {{ $AppData->application_id }}
                                                                @endforeach
                                                                {{-- {!! getApplicationID($item->id, $item->agreement) !!} --}}
                                                            </td>
                                                        @endif
                                                        <td>
                                                            <div class="user-profile-holder">
                                                                <div class="flex-grow-1 ms-3">
                                                                    <p class="name">{{ ucwords($item->name_prefix) }}
                                                                        {{ $item->full_name }}</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        @if (request()->input('show') == 'borrower & agreement')
                                                            @if ($item->borrowerAgreementRfq)
                                                                <td>
                                                                    <div class="user-profile-holder" style="flex-direction: column">
                                                                        <div class="flex-grow-1 ms-3">
                                                                            <p class="name">
                                                                                {{coborrowerAndgurantor($item->borrowerAgreementRfq->id)['prefix_cb1']}} 
                                                                                {{ coborrowerAndgurantor($item->borrowerAgreementRfq->id)['name_cb1']}} 
                                                                            </p>
                                                                            <p class="small text-muted mb-0">
                                                                                {{coborrowerAndgurantor($item->borrowerAgreementRfq->id)['email_cb1']}} 
                                                                            </p>
                                                                            <p class="small text-muted mb-0">{{coborrowerAndgurantor($item->borrowerAgreementRfq->id)['mobile_cb1']}}</p>
                                                                        </div>
                                                                        <div class="flex-grow-1 ms-3">
                                                                            <p class="name">
                                                                                {{coborrowerAndgurantor($item->borrowerAgreementRfq->id)['prefix_cb2']}} 
                                                                                {{ coborrowerAndgurantor($item->borrowerAgreementRfq->id)['name_cb2']}} 
                                                                            </p>
                                                                            <p class="small text-muted mb-0">
                                                                                {{coborrowerAndgurantor($item->borrowerAgreementRfq->id)['email_cb2']}} 
                                                                            </p>
                                                                            <p class="small text-muted mb-0">{{coborrowerAndgurantor($item->borrowerAgreementRfq->id)['mobile_cb2']}}</p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="user-profile-holder">
                                                                        <div class="flex-grow-1 ms-3">
                                                                            <p class="name">
                                                                                {{coborrowerAndgurantor($item->borrowerAgreementRfq->id)['prefix_gur']}} 
                                                                                {{ coborrowerAndgurantor($item->borrowerAgreementRfq->id)['name_gur']}} 
                                                                            </p>
                                                                            <p class="small text-muted mb-0">
                                                                                {{coborrowerAndgurantor($item->borrowerAgreementRfq->id)['email_gur']}} 
                                                                            </p>
                                                                            <p class="small text-muted mb-0">{{coborrowerAndgurantor($item->borrowerAgreementRfq->id)['mobile_gur']}}</p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            @else
                                                                <td></td>
                                                                <td></td>
                                                            @endif
                                                        @endif
                                                        <td>
                                                            <p class="small text-dark mb-1">{{ $item->email }}</p>
                                                            <p class="small text-dark mb-0">@php
                                                                if (!empty($item->mobile)) {
                                                                    echo $item->mobile;
                                                                } else {
                                                                    echo '<i class="fas fa-phone fa-rotate-90 text-danger"></i>';
                                                                }
                                                            @endphp</p>
                                                        </td>
                                                        <td>
                                                            <p class="small text-muted mb-0" title="Street address">
                                                                {{ $item->pan_card_number }}</p>
                                                        </td>
                                                        <td>
                                                            <p class="small text-muted mb-0" title="Street address">
                                                                {{ strtolower($item->KYC_HOUSE_NO) != 'na' && $item->KYC_HOUSE_NO != '' ? $item->KYC_HOUSE_NO . ' ' : '' }}
                                                                {{ $item->KYC_HOUSE_NO == $item->KYC_Street ? '' : $item->KYC_Street }}
                                                            </p>
                                                            <p class="small text-muted mb-0">
                                                                <span
                                                                    title="City">{{ $item->KYC_Street == $item->KYC_LOCALITY ? '' : $item->KYC_LOCALITY }}</span>
                                                                <span title="Pincode">{{ $item->KYC_CITY }}</span>
                                                                <span title="State">{{ $item->KYC_State }}</span>
                                                                <span title="State">{{ $item->KYC_PINCODE }}</span>
                                                                <span title="State">{{ $item->KYC_Country }}</span>
                                                            </p>
                                                        </td>
                                                        @if (request()->input('show') == 'borrower & agreement')
                                                            <td>
                                                                <div class="single-line">
                                                                    @if (count($item->agreement) > 0)
                                                                        {{-- @if ($item->borrowerAgreementRfq)
                                                                            <a href="{{ route('user.borrower.setRm', [$item->id]) }}"
                                                                                class="badge badge-dark action-button">Set
                                                                                R.M</a>
                                                                            <br>
                                                                        @endif --}}

                                                                        @foreach ($item->agreement as $agreement)
                                                                            @php
                                                                            $getAgreement = App\Models\Agreement::where('id', $agreement->agreement_id)->first();
                                                                            @endphp
                                                                            <a href="{{ route('user.borrower.agreement', $agreement->id) }}"
                                                                                class="badge {{ $item->borrowerAgreementRfq ? 'badge-primary' : 'badge-danger' }} action-button d-inline-block"
                                                                                title="Setup loan application form">{{ $item->borrowerAgreementRfq ? $getAgreement->name : 'Prepare Agreement' }}</a>
                                                                            {{-- @if ($item->borrowerAgreementRfq)
                                                                                <a href="{{ route('user.esign.borrower.detail', [$item->id, $agreement->id, $item->borrowerAgreementRfq->id]) }}"
                                                                                    class="badge badge-secondary action-button d-inline-block"
                                                                                    title="E-signing">E-sign</a>
                                                                            @endif --}}
                                                                            <br>
                                                                        @endforeach
                                                                        <br>
                                                                        @if (!$item->borrowerAgreementRfq)
                                                                        <a href="{{ route('user.borrower.agreement.setup', $item->id) }}"
                                                                                class="badge badge-dark action-button">Settings</a>
                                                                        @else
                                                                        <a href="{{ route('user.borrower.agreement.setup', $item->id) }}"
                                                                            class="badge badge-primary action-button">Settings</a>
                                                                        @endif
                                                                    @else
                                                                        <p class="small text-muted mb-1"> <em>No agreement yet</em> </p>
                                                                        <br>
                                                                        <a href="{{ route('user.borrower.agreement.setup', $item->id) }}"
                                                                            class="badge badge-dark action-button">Add New Agreement</a>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        @else
                                                        <td>
                                                            @if (!$item->borrowerAgreementRfq)
                                                                <a href="{{ route('user.borrower.agreement.setup', $item->id) }}"
                                                                    class="badge badge-dark action-button">Settings</a>
                                                            @else
                                                                <a href="{{ route('user.borrower.agreement.setup', $item->id) }}"
                                                                    class="badge badge-primary action-button">Settings</a>
                                                            @endif
                                                        </td>
                                                        @endif
                                                        <td class="text-right">
                                                            <div class="single-line">
                                                                <a href="javascript: void(0)" class="badge badge-dark action-button"
                                                                    title="View"
                                                                    onclick="viewDeta1ls('{{ route('user.borrower.show') }}', {{ $item->id }})">View</a>

                                                                @if(count($item->agreement) <= 0)
                                                                    @if(Auth::guard('web')->user()->user_type == 1 || Auth::guard('web')->user()->user_type == 3)
                                                                        <a href="{{ route('user.borrower.edit', $item->id) }}"
                                                                            class="badge badge-dark action-button" title="Edit">Edit</a>
                                                                
                                                                        <a href="javascript: void(0)" class="badge badge-dark action-button" title="Delete" onclick="confirm4lert('{{ route('user.borrower.destroy') }}', {{ $item->id }}, 'delete')">Delete</a>
                                                                    @endif
                                                                @endif
                                                                {{-- @if ($item->borrowerAgreementRfq)
                                                                    <br>
                                                                    <a href="{{ route('user.borrower.setRm') }}"
                                                                        class="badge badge-dark action-button">Set
                                                                        Relationship Manager</a>
                                                                @endif --}}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @else
                                                <tr id="tr_{{ $item->id }}">
                                                    <td>{{ $index + 1 }}</td>
                                                    @if(request()->input('show') == 'borrower & agreement')
                                                        <td>{{getApplicationID($item->id, $item->agreement)}}</td>
                                                    @endif
                                                    <td>
                                                        <div class="user-profile-holder">
                                                            {{-- <div class="flex-shrink-0">
                                                        <img src="{{asset($item->image_path)}}" alt="user-image-{{ $item->id }}">
                                                    </div> --}}
                                                            <div class="flex-grow-1 ms-3">
                                                                <p class="name">{{ ucwords($item->name_prefix) }}
                                                                    {{ $item->full_name }}</p>
                                                                <p class="small text-muted mb-0">{{ $item->occupation }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @if (request()->input('show') == 'borrower & agreement')
                                                        @if ($item->borrowerAgreementRfq)
                                                            <td>
                                                                <div class="user-profile-holder" style="flex-direction: column">
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <p class="name">
                                                                            {{coborrowerAndgurantor($item->borrowerAgreementRfq->id)['prefix_cb1']}} 
                                                                            {{ coborrowerAndgurantor($item->borrowerAgreementRfq->id)['name_cb1']}} 
                                                                        </p>
                                                                        <p class="small text-muted mb-0">
                                                                            {{coborrowerAndgurantor($item->borrowerAgreementRfq->id)['email_cb1']}} 
                                                                        </p>
                                                                        <p class="small text-muted mb-0">{{coborrowerAndgurantor($item->borrowerAgreementRfq->id)['mobile_cb1']}}</p>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <p class="name">
                                                                            {{coborrowerAndgurantor($item->borrowerAgreementRfq->id)['prefix_cb2']}} 
                                                                            {{ coborrowerAndgurantor($item->borrowerAgreementRfq->id)['name_cb2']}} 
                                                                        </p>
                                                                        <p class="small text-muted mb-0">
                                                                            {{coborrowerAndgurantor($item->borrowerAgreementRfq->id)['email_cb2']}} 
                                                                        </p>
                                                                        <p class="small text-muted mb-0">{{coborrowerAndgurantor($item->borrowerAgreementRfq->id)['mobile_cb2']}}</p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="user-profile-holder">
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <p class="name">
                                                                            {{coborrowerAndgurantor($item->borrowerAgreementRfq->id)['prefix_gur']}} 
                                                                            {{ coborrowerAndgurantor($item->borrowerAgreementRfq->id)['name_gur']}} 
                                                                        </p>
                                                                        <p class="small text-muted mb-0">
                                                                            {{coborrowerAndgurantor($item->borrowerAgreementRfq->id)['email_gur']}} 
                                                                        </p>
                                                                        <p class="small text-muted mb-0">{{coborrowerAndgurantor($item->borrowerAgreementRfq->id)['mobile_gur']}}</p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        @else
                                                            <td></td>
                                                            <td></td>
                                                        @endif
                                                    @endif
                                                    <td>
                                                        <p class="small text-dark mb-1">{{ $item->email }}</p>
                                                        <p class="small text-dark mb-0">@php
                                                            if (!empty($item->mobile)) {
                                                                echo $item->mobile;
                                                            } else {
                                                                echo '<i class="fas fa-phone fa-rotate-90 text-danger"></i>';
                                                            }
                                                        @endphp</p>
                                                    </td>
                                                    <td>
                                                        <p class="small text-muted mb-0" title="Street address">
                                                            {{ $item->pan_card_number }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="small text-muted mb-0" title="Street address">
                                                            {{ strtolower($item->KYC_HOUSE_NO) != 'na' && $item->KYC_HOUSE_NO != '' ? $item->KYC_HOUSE_NO . ' ' : '' }}
                                                            {{ $item->KYC_HOUSE_NO == $item->KYC_Street ? '' : $item->KYC_Street }}
                                                        </p>
                                                        <p class="small text-muted mb-0">
                                                            <span
                                                                title="City">{{ $item->KYC_Street == $item->KYC_LOCALITY ? '' : $item->KYC_LOCALITY }}</span>
                                                            <span title="Pincode">{{ $item->KYC_CITY }}</span>
                                                            <span title="State">{{ $item->KYC_State }}</span>
                                                            <span title="State">{{ $item->KYC_PINCODE }}</span>
                                                            <span title="State">{{ $item->KYC_Country }}</span>
                                                        </p>
                                                    </td>
                                                    @if (request()->input('show') == 'borrower & agreement')
                                                        <td>
                                                            <div class="single-line">
                                                                @if (count($item->agreement) > 0)
                                                                    {{-- @if ($item->borrowerAgreementRfq)
                                                                        <a href="{{ route('user.borrower.setRm', [$item->id]) }}"
                                                                            class="badge badge-dark action-button">Set
                                                                            R.M</a>
                                                                        <br>
                                                                    @endif --}}

                                                                    @foreach ($item->agreement as $agreement)
                                                                        <a href="{{ route('user.borrower.agreement', $agreement->id) }}"
                                                                            class="badge {{ $item->borrowerAgreementRfq ? 'badge-primary' : 'badge-danger' }} action-button d-inline-block"
                                                                            title="Setup loan application form">{{ $item->borrowerAgreementRfq ? 'View Agreement' : 'Prepare Agreement' }}</a>
                                                                        {{-- @if ($item->borrowerAgreementRfq)
                                                                            <a href="{{ route('user.esign.borrower.detail', [$item->id, $agreement->id, $item->borrowerAgreementRfq->id]) }}"
                                                                                class="badge badge-secondary action-button d-inline-block"
                                                                                title="E-signing">E-sign</a>
                                                                        @endif --}}
                                                                        <br>
                                                                    @endforeach
                                                                    <br>
                                                                    @if (!$item->borrowerAgreementRfq)
                                                                    <a href="{{ route('user.borrower.agreement.setup', $item->id) }}"
                                                                            class="badge badge-dark action-button">Settings</a>
                                                                    @else
                                                                    <a href="{{ route('user.borrower.agreement.setup', $item->id) }}"
                                                                        class="badge badge-primary action-button">Settings</a>
                                                                    @endif
                                                                @else
                                                                    <p class="small text-muted mb-1"> <em>No agreement yet</em> </p>
                                                                    <br>
                                                                    <a href="{{ route('user.borrower.agreement.setup', $item->id) }}"
                                                                        class="badge badge-dark action-button">Add New Agreement</a>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    @else
                                                        <td>
                                                            @if (!$item->borrowerAgreementRfq)
                                                                <a href="{{ route('user.borrower.agreement.setup', $item->id) }}"
                                                                    class="badge badge-dark action-button">Settings</a>
                                                            @else
                                                                <a href="{{ route('user.borrower.agreement.setup', $item->id) }}"
                                                                    class="badge badge-primary action-button">Settings</a>
                                                            @endif
                                                        </td>
                                                    @endif
                                                    <td class="text-right">
                                                        <div class="single-line">
                                                            <a href="javascript: void(0)" class="badge badge-dark action-button"
                                                                title="View"
                                                                onclick="viewDeta1ls('{{ route('user.borrower.show') }}', {{ $item->id }})">View</a>

                                                            @if(count($item->agreement) <= 0)
                                                                @if(Auth::guard('web')->user()->user_type == 1 || Auth::guard('web')->user()->user_type == 3)
                                                                    <a href="{{ route('user.borrower.edit', $item->id) }}"
                                                                        class="badge badge-dark action-button" title="Edit">Edit</a>
                                                            
                                                                    <a href="javascript: void(0)" class="badge badge-dark action-button" title="Delete" onclick="confirm4lert('{{ route('user.borrower.destroy') }}', {{ $item->id }}, 'delete')">Delete</a>
                                                                @endif
                                                            @endif
                                                            {{-- @if ($item->borrowerAgreementRfq)
                                                                <br>
                                                                <a href="{{ route('user.borrower.setRm') }}"
                                                                    class="badge badge-dark action-button">Set
                                                                    Relationship Manager</a>
                                                            @endif --}}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="100%"><em>No records found</em></td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination-view">
                                {{ $data->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="csvUploadModal" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Import CSV data
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('user.borrower.csv.upload') }}" enctype="multipart/form-data"
                        id="borrowerCsvUpload">
                        @csrf
                        <input type="file" name="file" class="form-control" accept=".csv">
                        <br>
                        <a href="{{ asset('admin/static-required/borrower-sample.csv') }}">Download Sample CSV</a>
                        <br>
                        <button type="submit" class="btn btn-sm btn-primary mt-3" id="csvImportBtn">Import <i class="fas fa-upload"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Export --}}
    <div class="modal fade" id="csvExportModal" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Export Customers Data
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="get" action="{{ route('user.borrower.csv.export') }}"
                        id="borrowerCsvExport">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <label for="">Start</label>
                                <input type="date" class="form-control" name="to_date" placeholder="" required>
                            </div>
                            <div class="col">
                                <label for="">End</label>
                                <input type="date" class="form-control" name="from_date" placeholder="" required>
                            </div>
                        </div>
                        <div class="text-right mt-3">
                            <a href="{{ route('user.borrower.list') }}">
                                <span class="input-group-btn">
                                    <button class="btn btn-danger btn-sm rounded-0" type="button"
                                        title="Refresh page"> Reset <i
                                            class="fas fa-sync-alt"></i></button>
                                </span>
                            </a>
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-success btn-sm rounded-0">Export <i class="fas fa-upload"></i></button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Export Borroers--}}
    <div class="modal fade" id="csvExportBorrowersModal" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Export Borrowers Data
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="get" action="{{ route('user.borrower.csv.export') }}">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <label for="">Start</label>
                                <input type="date" class="form-control" name="to_date" placeholder="" required>
                            </div>
                            <div class="col">
                                <label for="">End</label>
                                <input type="date" class="form-control" name="from_date" placeholder="" required>
                            </div>
                        </div>
                        <input type="hidden" name="borrower" value="borrower">
                        <div class="text-right mt-3">
                            <a href="{{ route('user.borrower.list',['show'=>'borrower & agreement']) }}">
                                <span class="input-group-btn">
                                    <button class="btn btn-danger btn-sm rounded-0" type="button"
                                        title="Refresh page"> Reset <i
                                            class="fas fa-sync-alt"></i></button>
                                </span>
                            </a>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-success btn-sm rounded-0">Export <i class="fas fa-upload"></i></button>
                                </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="csvUploadModalTest" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Import CSV data
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('user.borrower.csv.upload.test') }}"
                        enctype="multipart/form-data" id="borrowerCsvUpload">
                        @csrf
                        <input type="file" name="file" class="form-control" accept=".csv">
                        <br>
                        <button type="submit" class="btn btn-sm btn-primary" id="csvImportBtn">Import <i
                                class="fas fa-upload"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#borrowerCsvUpload').on('submit', function() {
            $('#csvImportBtn').attr('disabled', true).html('Please wait...');
            $('.close').attr('disabled', true);
        });
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
                        let mobileShow = '<em class="text-muted">No data</em>';
                        if (result.data.mobile != null) {
                            mobileShow = result.data.mobile;
                        }

                        content += '<div class="w-100 user-profile-holder mb-3"><img src="' + result.data
                            .image_path + '"></div>';
                        content += '<p class="text-muted small mb-1">Name</p><h6>' + result.data.name_prefix +
                            ' ' + result.data.name + '</h6>';
                        content += '<p class="text-muted small mb-1">Email</p><h6>' + result.data.email +
                            '</h6>';
                        content += '<p class="text-muted small mb-1">Phone number</p><h6>' + mobileShow +
                            '</h6>';
                        content += '<p class="text-muted small mb-1">Address</p><h6>' + result.data
                            .street_address + '</h6>';
                        content += '<h6>' + result.data.city + ', ' + result.data.pincode + ', ' + result.data
                            .state + '</h6>';

                        let route = '{{ route('user.borrower.details', 'id') }}';
                        route = route.replace('id', result.data.user_id);
                        $('#userDetails .modal-content').append('<div class="modal-footer"><a href="' + route +
                            '" class="btn btn-sm btn-primary">View details <i class="fa fa-chevron-right"></i> </a></div>'
                        );
                    } else {
                        content += '<p class="text-muted small mb-1">No data found. Try again</p>';
                    }
                    $('#appendContent').html(content);
                    $('#userDetailsModalLabel').text('Borrower details');
                    $('#userDetails').modal('show');
                }
            });
        }
    </script>
@endsection
