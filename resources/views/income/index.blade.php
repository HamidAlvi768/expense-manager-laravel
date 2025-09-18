@extends('layouts.layout')

@section('content')
    <link href="{{ asset('assets/css/index-mediaquery.css') }}" rel="stylesheet">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center">
                        <h3 class="mb-0">
                            <a href="{{ route('incomes.create') }}" class="btn btn-outline btn-info">
                                + @lang('Add Income')
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
                        <li class="breadcrumb-item active">@lang('Incomes List')</li>
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
                        @lang('Incomes List') 
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
                                            <label>@lang('Account')</label>
                                            <select name="account_id" class="form-control select2">
                                                <option value="">@lang('Select Account')</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}" 
                                                        {{ request()->account_id == $account->id ? 'selected' : '' }}>
                                                        {{ $account->account_title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Income Category')</label>
                                            <select name="income_category_id" class="form-control select2">
                                                <option value="">@lang('Select Category')</option>
                                                @foreach ($incomeCategories as $category)
                                                    <option value="{{ $category->id }}" 
                                                        {{ request()->income_category_id == $category->id ? 'selected' : '' }}>
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
                                            <a href="{{ route('incomes.index') }}" class="btn btn-secondary mt-4">@lang('Clear')</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- New Section for Category Colors -->
                    <div class="text-center">
                        <div class="d-flex flex-wrap justify-content-center">
                            @foreach ($usedCategories as $category)
                                <div class="mr-3 mb-2">
                                    <span class="d-inline-block" style="width: 15px; height: 15px; background-color: {{ $categoryColors[$category->id] ?? '#FFFFFF' }}; border-radius: 50%;"></span>
                                    <span class="ml-1">{{ $category->title }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <table class="table table-striped" id="laravel_datatable">
                        <thead>
                            <tr>
                                <th>@lang('Account')</th>
                                <th>@lang('Category')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Description')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($incomes as $item)
                            <tr style="background: linear-gradient(to right, rgba(255,255,255,0) 0%, {{ $categoryColors[$item->income_category_id] ?? '#FFFFFF' }} 50%, rgba(255,255,255,0) 100%);">
                                <td>{{ $item->account->account_title ?? '-' }}</td>
                                    <td>{{ $item->incomeCategory->title ?? '-' }}</td>
                                    <td>{{ isset($item->amount) ? formatAmount($item->amount) : '-' }}</td>
                                    <td>{{ $item->description ?? '-' }}</td>
                                    <td>{{ $item->income_date }}</td>
                                    <td>
                                        <a href="{{ route('incomes.show', $item->id) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="@lang('View')">
                                            <i class="fa fa-eye ambitious-padding-btn"></i>
                                        </a>
                                        <a href="{{ route('incomes.edit', $item->id) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="@lang('Edit')">
                                            <i class="fa fa-edit ambitious-padding-btn"></i>
                                        </a>
                                        <a href="#" data-href="{{ route('incomes.destroy', $item->id) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="@lang('Delete')">
                                            <i class="fa fa-trash ambitious-padding-btn"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $incomes->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    @include('layouts.delete_modal')
@endsection
