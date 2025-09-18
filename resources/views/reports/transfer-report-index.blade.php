@extends('layouts.layout')

@section('content')
<link href="{{ asset('assets/css/index-mediaquery.css') }}" rel="stylesheet">

<style>
    /* Desktop styles (min-width to ensure it doesn't affect mobile) */
    @media (min-width: 768px) {
        table.table td:first-child,
        table.table th:first-child {
            text-align: center;
        }

        .content-header .breadcrumb {
            display: flex;
            justify-content: end;
        }
    }

    /* Mobile Form Styles */
    .mobile-form-container {
        background-color: #ebe6e6;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .mobile-form-row {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }

    .mobile-form-row .form-group {
        flex: 1;
        margin-bottom: 0;
    }

    /* Mobile-specific styles */
    @media (max-width: 767px) {
        .desktop-header {
            display: none;
        }
        
        .mobile-header {
            display: block;
        }

        .mobile-form-row {
            flex-direction: row;
            gap: 10px;
        }
        
        .mobile-form-row .form-group {
            margin-bottom: 10px;
            width: 1%;
        }

        .mobile-form-row label {
            display: none;
        }

        /* Show only date labels on mobile */
        .mobile-form-row .form-group label[for="date_from"],
        .mobile-form-row .form-group label[for="date_to"] {
            display: block;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
    }

    /* Desktop-specific styles */
    @media (min-width: 768px) {
        .mobile-header {
            display: none;
        }
        
        .desktop-header {
            display: block;
        }
    }

    .submit-container {
        text-align: center;
        margin-top: 20px;
    }
</style>

<!-- Mobile Content Header -->
<section class="content-header mobile-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="d-flex align-items-center">
                    <div class="ml-auto d-flex gap-2">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item active">@lang('Transfer Report')</li>
                        </ol>
                        <a class="btn btn-sm btn-default" target="_blank" href="{{ request()->fullUrlWithQuery(['export' => 1]) }}">
                            <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                        </a>
                        <button class="btn btn-sm btn-default" data-toggle="collapse" href="#filter">
                            <i class="fas fa-filter"></i> @lang('Filter')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Desktop Content Header -->
<section class="content-header desktop-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="d-flex align-items-center">
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
                    <li class="breadcrumb-item active">@lang('Transfer Report')</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">@lang('Report')</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('transfer-report.index') }}" method="get">
                    <div class="mobile-form-container">
                        <!-- Date From and Date To -->
                        <div class="mobile-form-row">
                            <div class="form-group">
                                <label for="date_from">@lang('Date From')</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input type="date" name="date_from" id="date_from" class="form-control" placeholder="dd/mm/yyyy" value="{{ old('date_from', request()->date_from) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="date_to">@lang('Date To')</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input type="date" name="date_to" id="date_to" class="form-control" placeholder="dd/mm/yyyy" value="{{ old('date_to', request()->date_to) }}">
                                </div>
                            </div>
                        </div>

                        <!-- From and To Account -->
                        <div class="mobile-form-row">
                            <div class="form-group">
                                <label for="from_account_id">@lang('From')</label>
                                <select name="from_account_id" id="from_account_id" class="form-control">
                                    <option value="">@lang('Select From Account')</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ request('from_account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->account_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="to_account_id">@lang('To Account')</label>
                                <select name="to_account_id" id="to_account_id" class="form-control">
                                    <option value="">@lang('Select To Account')</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ request('to_account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->account_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Amount and Notes -->
                        <div class="mobile-form-row">
                            <div class="form-group">
                                <label for="transfer_amount">@lang('Amount')</label>
                                <input type="number" name="transfer_amount" id="transfer_amount" class="form-control" value="{{ old('transfer_amount', request()->transfer_amount) }}" placeholder="@lang('100.0')">
                            </div>
                            <div class="form-group">
                                <label for="description">@lang('Notes')</label>
                                <input type="text" name="description" id="description" class="form-control" value="{{ old('description', request()->description) }}">
                            </div>
                        </div>

                        <!-- Submit and Clear buttons -->
                        <div class="submit-container">
                            <button type="submit" class="btn btn-info">@lang('Submit')</button>
                            @if(request()->hasAny(['date_from', 'date_to', 'transfer_amount', 'from_account_id', 'to_account_id', 'description']))
                                <a href="{{ route('transfer-report.index') }}" class="btn btn-secondary">@lang('Clear')</a>
                            @endif
                        </div>
                    </div>
                </form>

                <br>
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title">@lang('Transfers')</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered custom-table">
                                <thead>
                                    <tr>
                                        <th>@lang('S.No')</th>
                                        <th>@lang('From')</th>
                                        <th>@lang('To')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Notes')</th>
                                        <th>@lang('Date')</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-custom">
                                    @forelse ($transfers as $index => $transfer)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $transfer->fromAccount->account_title ?? '-' }}</td>
                                            <td>{{ $transfer->toAccount->account_title ?? '-' }}</td>
                                            <td>{{ isset($transfer->transfer_amount) ? formatAmount($transfer->transfer_amount) : '-' }}</td>
                                            <td>{{ $transfer->description ?? '-' }}</td>
                                            <td>{{ $transfer->transfer_date }}</td>
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="6" class="text-secondary">@lang('No records found for the selected filter')</td>
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
</div>
@endsection
