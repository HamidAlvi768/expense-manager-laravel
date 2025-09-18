@extends('layouts.layout')

@section('content')
    <link href="{{ asset('assets/css/index-mediaquery.css') }}" rel="stylesheet">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center">
                        <h3 class="mb-0">
                            <a href="{{ route('transfers.create') }}" class="btn btn-outline btn-info">
                                + @lang('Add Transfer')
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
                        <li class="breadcrumb-item active">@lang('Transfers List')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
<<<<<<< HEAD

=======
>>>>>>> 59200bb (Initial commit with expense manager code)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">
                        @lang('Transfers List') 
                        <span class="badge badge-light ml-2">{{ formatAmount($total) }}</span>
                    </h3>   
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if (request()->isFilterActive) show @endif">
                        <div class="card-body border">
                            <form action="" method="get" role="form" autocomplete="off">
                                <input type="hidden" name="isFilterActive" value="true">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('From Account')</label>
                                            <select name="from_account" class="form-control">
                                                <option value="">@lang('Select')</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}" 
                                                        {{ request()->from_account == $account->id ? 'selected' : '' }}>
                                                        {{ $account->account_title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('To Account')</label>
                                            <select name="to_account" class="form-control">
                                                <option value="">@lang('Select')</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}" 
                                                        {{ request()->to_account == $account->id ? 'selected' : '' }}>
                                                        {{ $account->account_title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Amount From')</label>
                                            <input type="text" name="amount_from" class="form-control"
                                                value="{{ request()->amount_from }}" placeholder="e.g., 1000">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Amount To')</label>
                                            <input type="text" name="amount_to" class="form-control"
                                                value="{{ request()->amount_to }}" placeholder="e.g., 5000">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Start Date')</label>
                                            <input type="date" name="start_date" class="form-control"
                                                value="{{ request()->start_date }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('End Date')</label>
                                            <input type="date" name="end_date" class="form-control"
                                                value="{{ request()->end_date }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                        @if (request()->isFilterActive)
                                            <a href="{{ route('transfers.index') }}" class="btn btn-secondary">@lang('Clear')</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="laravel_datatable">
                            <thead>
                                <tr>
                                    <th>@lang('From')</th>
                                    <th>@lang('To')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Notes')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transfers as $transfer)
                                    <tr>
                                        <td>{{ $transfer->fromAccount->account_title ?? '-' }}</td>
                                        <td>{{ $transfer->toAccount->account_title ?? '-' }}</td>
                                        <td>{{ isset($transfer->transfer_amount) ? formatAmount($transfer->transfer_amount) : '-' }}</td>
                                        <td>{{ $transfer->notes ?? '-' }}</td>
                                        <td>{{ $transfer->transfer_date ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('transfers.show', $transfer) }}"
                                                class="btn btn-info btn-outline btn-circle btn-lg"
                                                data-toggle="tooltip" title="@lang('View')">
                                                <i class="fa fa-eye ambitious-padding-btn"></i>
                                            </a>
                                            <a href="{{ route('transfers.edit', $transfer) }}"
                                                class="btn btn-info btn-outline btn-circle btn-lg"
                                                data-toggle="tooltip" title="@lang('Edit')">
                                                <i class="fa fa-edit ambitious-padding-btn"></i>
                                            </a>
                                            <a href="#"
                                                data-href="{{ route('transfers.destroy', $transfer) }}"
                                                class="btn btn-info btn-outline btn-circle btn-lg"
                                                data-toggle="modal" data-target="#myModal" title="@lang('Delete')">
                                                <i class="fa fa-trash ambitious-padding-btn"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $transfers->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    @include('layouts.delete_modal')
@endsection
