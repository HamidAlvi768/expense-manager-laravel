@extends('layouts.layout')
@section('content')
    <link href="{{ asset('assets/css/index-mediaquery.css') }}" rel="stylesheet">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center">
                        <h3 class="mb-0">
                            <a href="{{ route('accounts.create') }}" class="btn btn-outline btn-info">
                                + @lang('Add Account')
                            </a>
                        </h3>
                        <div class="ml-auto d-flex gap-2">
                            <a class="btn btn-sm btn-default" target="_blank" href="{{ request()->fullUrlWithQuery(['export' => 1]) }}">
                                <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                            </a>
                            <button class="btn btn-sm btn-default" data-toggle="collapse" href="#filter">
                                <i class="fas fa-filter"></i> @lang('Filter')
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('Accounts List')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">
                        @lang('Accounts List') 
                        <span class="badge badge-light ml-2">{{ formatAmount($total) }}</span>
                    </h3>                    
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if (request()->isFilterActive) show @endif">
                        <div class="card-body border">
                            <form action="" method="get" role="form" autocomplete="off">
                                <input type="hidden" name="isFilterActive" value="true">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Account Title')</label>
                                            <input type="text" name="account_title" class="form-control"
                                                value="{{ request()->account_title }}" placeholder="@lang('Account Title')">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Account Type')</label>
                                            <select name="account_type_id" class="form-control select2" style="width: 100%;">
                                                <option value="">@lang('Select Account Type')</option>
                                                @foreach($accountTypes as $type)
                                                    <option value="{{ $type->id }}" 
                                                        {{ request()->account_type_id == $type->id ? 'selected' : '' }}>
                                                        {{ $type->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 align-content-center">
                                        <button type="submit" class="btn btn-info mt-4">@lang('Submit')</button>
                                        @if (request()->isFilterActive)
                                            <a href="{{ route('accounts.index') }}"
                                                class="btn btn-secondary mt-4">@lang('Clear')</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table class="table table-striped" id="laravel_datatable">
                        <thead>
                            <tr>
                                <th>@lang('Account Title')</th>
                                <th>@lang('Account Type')</th>
                                <th>@lang('Deposit')</th>
                                <th>@lang('Balance')</th>
                                <th>@lang('Withdrawal')</th>
                                <th>@lang('Total')</th>                                
                                <th>@lang('Notes')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts as $account)
                                <tr>
                                    <td>{{ $account->account_title ?? '-' }}</td>
                                    <td>{{ $account->accountType->title ?? '-' }}</td>
                                    <td>{{ isset($account->deposit) ? formatAmount($account->deposit) : '-' }}</td>
                                    <td>{{ isset($account->balance) ? formatAmount($account->balance) : '-' }}</td>
                                    <td>{{ isset($account->withdrawal) ? formatAmount($account->withdrawal) : '-' }}</td>
                                    <td>{{ isset($account->total) ? formatAmount($account->total) : '-' }}</td>                                    
                                    <td>{{ $account->notes ?? '-' }}</td>
                                    <td style="text-align:end;">
                                        <a href="{{ route('accounts.show', $account) }}"
                                            class="btn btn-info btn-outline btn-circle btn-lg"
                                            data-toggle="tooltip" title="@lang('View')">
                                            <i class="fa fa-eye ambitious-padding-btn"></i>
                                        </a>
                                        
                                            <a href="{{ route('accounts.edit', $account) }}"
                                                class="btn btn-info btn-outline btn-circle btn-lg"
                                                data-toggle="tooltip" title="@lang('Edit')">
                                                <i class="fa fa-edit ambitious-padding-btn"></i>
                                            </a>
                                        
                                            <a href="#"
                                                data-href="{{ route('accounts.destroy', $account) }}"
                                                class="btn btn-info btn-outline btn-circle btn-lg"
                                                data-toggle="modal" data-target="#myModal" title="@lang('Delete')">
                                                <i class="fa fa-trash ambitious-padding-btn"></i>
                                            </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $accounts->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('layouts.delete_modal')
@endsection
