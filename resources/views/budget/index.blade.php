@extends('layouts.layout')
@section('content')

    <link href="{{ asset('assets/css/index-mediaquery.css') }}" rel="stylesheet">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center">
                        <h3 class="mb-0">
                            <a href="{{ route('budgets.create') }}" class="btn btn-outline btn-info">
                                + @lang('Add Budget')
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
                        <li class="breadcrumb-item active">@lang('Budget List')</li>
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
                        @lang('Budgets List') 
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
                                            <label>@lang('Expense Category')</label>
                                            <select name="expense_category_id" class="form-control">
                                                <option value="">@lang('Select Category')</option>
                                                @foreach ($expenseCategories as $category)
                                                    <option value="{{ $category->id }}" 
                                                        {{ request()->expense_category_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Amount')</label>
                                            <input type="text" name="amount" class="form-control" 
                                                   value="{{ request()->amount }}" placeholder="@lang('Amount')">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="month">@lang('Month')</label>
                                            <select id="month" name="month" class="form-control">
                                                <option value="" disabled {{ old('month', request()->month) ? '' : 'selected' }}>@lang('Select Month')</option>
                                                @foreach (range(1, 12) as $month)
                                                    <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}"
                                                        {{ old('month') == str_pad($month, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                        @lang(date('F', mktime(0, 0, 0, $month, 10)))
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Start Date')</label>
                                            <input type="date" name="start_date" id="start_date"
                                                   class="form-control" 
                                                   placeholder="@lang('Start Date')" 
                                                   value="{{ old('start_date', request()->start_date) }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('End Date')</label>
                                            <input type="date" name="end_date" id="end_date"
                                                   class="form-control" 
                                                   placeholder="@lang('End Date')" 
                                                   value="{{ old('end_date', request()->end_date) }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 align-content-center">
                                        <button type="submit" class="btn btn-info mt-4">@lang('Submit')</button>
                                        @if (request()->isFilterActive)
                                            <a href="{{ route('budgets.index') }}" class="btn btn-secondary mt-4">@lang('Clear')</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table class="table table-striped" id="laravel_datatable">
                        <thead>
                            <tr>
                                <th>@lang('Category')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Month')</th>
                                <th>@lang('Description')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($budgets as $budget)
                                <tr>
                                    <td>{{ $budget->expenseCategory->title ?? '-' }}</td>
                                    <td>{{ isset($budget->amount) ? formatAmount($budget->amount) : '-' }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('m-y', $budget->month . '-01')->format('F') }}</td>
                                    <td>{{ $budget->description ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('budgets.show', $budget->id) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="@lang('View')">
                                            <i class="fa fa-eye ambitious-padding-btn"></i>
                                        </a>
                                        <a href="{{ route('budgets.edit', $budget->id) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="@lang('Edit')">
                                            <i class="fa fa-edit ambitious-padding-btn"></i>
                                        </a>
                                        <a href="#" data-href="{{ route('budgets.destroy', $budget->id) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="@lang('Delete')">
                                            <i class="fa fa-trash ambitious-padding-btn"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $budgets->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    @include('layouts.delete_modal')
@endsection
