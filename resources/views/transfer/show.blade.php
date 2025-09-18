@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('transfers.index') }}">@lang('Transfers')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Transfer Info')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Transfer Info')</h3>
                    {{-- @can('income-detail-update') --}}
                        <div class="card-tools">
                            <a href="{{ route('transfers.edit', $transfer) }}" class="btn btn-info">@lang('Edit')</a>
                        </div>
                    {{-- @endcan --}}
                </div>
                <div class="card-body">
                    <div class="bg-custom">
                        <div class="row m-0 p-0">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="user_id">@lang('User')</label>
                                    <p>{{ $transfer->user->name ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="from_account_id">@lang('From')</label>
                                    <p>{{ $transfer->fromAccount->account_title ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="to_account_id">@lang('To')</label>
                                    <p>{{ $transfer->toAccount->account_title ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="transfer_amount">@lang('Amount')</label>
                                    <p>{{ $transfer->transfer_amount ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="notes">@lang('Notes')</label>
                                    <p>{{ $transfer->notes ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="description">@lang('Description')</label>
                                    <p>{{ $transfer->description ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">@lang('Status')</label>
                                    <p>
                                        @if ($transfer->status == 1)
                                            <span class="badge badge-pill badge-success">@lang('Active')</span>
                                        @else
                                            <span class="badge badge-pill badge-danger">@lang('Inactive')</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
