@extends('layouts.auth.master')

@section('title', 'Esigning Data')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            @if(auth()->user()->user_type == 1)
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Esigning Reports:</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm m-0" id="dashboard_esign_table">
                                <thead>
                                    <tr>
                                        <th>App ID</th>
                                        <th>Signatory Person</th>
                                        <th>Mail Status</th>
                                        <th>Signature Status</th>
                                        <th>Action</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- {{ dd($data->agreement_rfq) }} --}}
                                    @forelse ($data->agreement_rfq as $signers)
                                    @if (count(getEsigns($signers->rfqid)) > 0)
                                        @foreach (getEsigns($signers->rfqid) as $eitem)
                                            @foreach (getEsignDetails($eitem->unique_id) as $key => $item)
                                                <tr>
                                                    @if($key == 0)
                                                        <td rowspan="{{count(getEsignDetails($eitem->unique_id))}}">
                                                            <p class="small"><span class="text-bold">{{$signers->application_id}}</span><br>
                                                                <small class="text-muted">(#{{$eitem->unique_id}})</small><br>
                                                                <b><small class="text-bold">{{$eitem->created_at}}</small></b>
                                                            </p>
                                                        </td>
                                                    @endif
                                                    <td style="padding-left: 5px;">
                                                        {{$item->user_name}}
                                                        <p><small>{{$item->user_email}}</small></p>
                                                    </td>
                                                    <td>
                                                        <div class="badge badge-sm badge-success">Yes</div><br>
                                                        <div class="badge badge-sm badge-success">{{date('D, M Y, H:i:s', strtotime($item->created_at))}}</div>
                                                    </td>
                                                    <td>
                                                        @if(count(getWebHookResponses($item->unique_id)) > 0 && count(getWebHookResponses($item->unique_id)) == count(getEsignDetails($eitem->unique_id)) )
                                                            @if(getWebHookResponses($item->unique_id)[$key]->signed_at != null)
                                                                <div class="badge badge-sm badge-success">Yes</div><br>
                                                                <div class="badge badge-sm badge-success">{{getWebHookResponses($item->unique_id)[$key]->signed_at}}</div>
                                                            @else
                                                                <div class="badge badge-sm badge-danger">No</div>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(count(getWebHookResponses($item->unique_id)) > 0 && count(getWebHookResponses($item->unique_id)) == count(getEsignDetails($eitem->unique_id)))
                                                            @if(getWebHookResponses($item->unique_id)[$key]->signed_at != null)
                                                                <a target="_blank" href="{{json_decode(fetchAgreement(getWebHookResponses($item->unique_id)[$key]->request_id))->document->signed_url}}" class="badge badge-sm badge-success">Signed Document</a>
                                                            @else
                                                                <a href="javascript:void(0)" class="badge badge-sm badge-danger">Not Signed Yet</a>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    @if($key == 0)
                                                        <td rowspan="{{count(getEsignDetails($eitem->unique_id))}}">
                                                            <a href="{{route('user.esign.borrower.detail',[$signers->borrower_id,$signers->baid,$signers->rfqid])}}" class="badge badge-info badge-sm" target="_blank">Visit</a>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endif
                                    @empty
                                        <tr><td class="text-center text-muted" colspan="100%"><em>No data found</em></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        </div>
    </div>
</section>
@endsection

@section('script')
@endsection