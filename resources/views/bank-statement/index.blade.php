@extends('layouts.layout')
@section('content')
    <link href="{{ asset('assets/css/index-mediaquery.css') }}" rel="stylesheet">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">

                    <h3>
                        <a href="{{ route('bank-statements.create') }}" class="btn btn-outline btn-info">
                            + @lang('Upload New Bank Statement')
                        </a>
                        <span class="pull-right"></span>
                    </h3>

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('Bank Statements')</li>
                        <li class="breadcrumb-item">
                            <a class="btn btn-sm btn-default" target="_blank" href="{{ request()->fullUrlWithQuery(['export' => 1]) }}">
                                <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <button class="btn btn-sm btn-default" data-toggle="collapse" href="#filter">
                                <i class="fas fa-filter"></i> @lang('Filter')
                            </button>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Bank Statement List')</h3>
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if (request()->isFilterActive) show @endif">
                        <div class="card-body border">
                            <form action="" method="get" role="form" autocomplete="off">
                                <input type="hidden" name="isFilterActive" value="true">
                                <div class="row">
                                    <!-- Account Filter -->
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Account')</label>
                                            <select name="account_id" class="form-control">
                                                <option value="">@lang('Select')</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}" 
                                                        {{ request()->account_id == $account->id ? 'selected' : '' }}>
                                                        {{ $account->account_title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                
                                    <!-- Uploaded Date Range -->
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Start Date')</label>
                                            <input type="date" name="start_date" class="form-control" value="{{ request()->start_date }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('End Date')</label>
                                            <input type="date" name="end_date" class="form-control" value="{{ request()->end_date }}">
                                        </div>
                                    </div>
                
                                    <!-- Submit Button -->
                                    <div class="col-sm-4 align-content-center">
                                        <button type="submit" class="btn btn-info mt-4">@lang('Submit')</button>
                                        @if (request()->isFilterActive)
                                            <a href="{{ route('bank-statements.index') }}" class="btn btn-secondary mt-4">@lang('Clear')</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table class="table table-striped" id="laravel_datatable">
                        <thead>
                            <tr>
                             
                                <th>@lang('Account')</th>
                                <th>@lang('Incomes')</th>
                                <th>@lang('Expenses')</th>
                                <th>@lang('In Amount')</th>
                                <th>@lang('Ex Amount')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('File')</th>

                                <th data-orderable="false">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bankStatements as $bankStatement)
                                <tr>
                                    
                                    <td><span style="text-wrap:nowrap;">{{ $bankStatement->account->account_title ?? '-' }}</span></td>
                                    <td><span style="text-wrap:nowrap;">{{ isset($bankStatement->total_credits) ? formatAmount($bankStatement->total_credits) : '-' }}</span></td>
                                    <td><span style="text-wrap:nowrap;">{{ isset($bankStatement->total_debits) ? formatAmount($bankStatement->total_debits) : '-' }}</span></td>
                                    <td><span style="text-wrap:nowrap;">{{ isset($bankStatement->credit_amount) ? formatAmount($bankStatement->credit_amount) : '-' }}</span></td>
                                    <td><span style="text-wrap:nowrap;">{{ isset($bankStatement->debit_amount) ? formatAmount($bankStatement->debit_amount) : '-' }}</span></td>
                                    <td><span style="text-wrap:nowrap;">{{ $bankStatement->uploaded_at ?? '-' }}</span></td>
                                    <td><span style="white-space: nowrap;">
                                        <a href="{{ asset('storage/' . $bankStatement->file_path) }}" download class="btn btn-link">CSV</a>
                                    </span></td>                                    
                                    <td class="responsive-width">
                                        <a href="#" data-href="{{ route('bank-statements.destroy', $bankStatement) }}"
                                            class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal"
                                            data-target="#myModal" title="@lang('Delete')"><i
                                                class="fa fa-trash ambitious-padding-btn"></i></a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $bankStatements->withQueryString()->links() }}

                </div>
            </div>
        </div>
    </div>
    @include('layouts.delete_modal')
@endsection
