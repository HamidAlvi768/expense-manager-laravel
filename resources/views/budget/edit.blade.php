@extends('layouts.layout')
@section('content')
<style>
    .parsley-errors-list{
        top: 100%;
    }
</style>
    <section class="content-header">
        <link href="{{ asset('assets/css/expense/edit.css') }}" rel="stylesheet">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 d-flex">
                    <h3 class="mr-2">
                        <a href="{{ route('budgets.create') }}" class="btn btn-outline btn-info">
                            + @lang('Add Budget')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                    <h3>
                        <a href="{{ route('budgets.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('budgets.index') }}">@lang('Budgets')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Edit Budget')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Edit Budget') ({{ $budget->expenseCategory->title }})</h3>
                </div>
                <div class="card-body">
                    <form id="budgetForm" class="form-material form-horizontal bg-custom"
                        action="{{ route('budgets.update', $budget) }}" method="POST"
                        enctype="multipart/form-data" data-parsley-validate>
                        @csrf
                        @method('PUT')

                        <div class="row col-12 p-0 m-0">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                <div class="form-group">
                                    <label for="month">@lang('Month')</label>
                                    <select id="month" name="month" class="form-control select2">
                                        <option value="" disabled {{ empty($budget->month) ? 'selected' : '' }}>@lang('Select Month')</option>
                                        <option value="01" {{ $budget->month == '01' ? 'selected' : '' }}>@lang('January')</option>
                                        <option value="02" {{ $budget->month == '02' ? 'selected' : '' }}>@lang('February')</option>
                                        <option value="03" {{ $budget->month == '03' ? 'selected' : '' }}>@lang('March')</option>
                                        <option value="04" {{ $budget->month == '04' ? 'selected' : '' }}>@lang('April')</option>
                                        <option value="05" {{ $budget->month == '05' ? 'selected' : '' }}>@lang('May')</option>
                                        <option value="06" {{ $budget->month == '06' ? 'selected' : '' }}>@lang('June')</option>
                                        <option value="07" {{ $budget->month == '07' ? 'selected' : '' }}>@lang('July')</option>
                                        <option value="08" {{ $budget->month == '08' ? 'selected' : '' }}>@lang('August')</option>
                                        <option value="09" {{ $budget->month == '09' ? 'selected' : '' }}>@lang('September')</option>
                                        <option value="10" {{ $budget->month == '10' ? 'selected' : '' }}>@lang('October')</option>
                                        <option value="11" {{ $budget->month == '11' ? 'selected' : '' }}>@lang('November')</option>
                                        <option value="12" {{ $budget->month == '12' ? 'selected' : '' }}>@lang('December')</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                <div class="form-group">
                                    <label for="expense_category_id">@lang('Expense Category')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-list-alt"></i></span>
                                        </div>
                                        <select name="expense_category_id" id="expense_category_id" class="form-control select2" required>
                                            <option value="" disabled>@lang('Select Category')</option>
                                            @foreach ($expenseCategories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $category->id == $budget->expense_category_id ? 'selected' : '' }}>
                                                    {{ $category->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('expense_category_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="amount">@lang('Amount')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        <input type="number" id="amount" name="amount"
                                            value="{{ old('amount', $budget->amount) }}"
                                            class="form-control @error('amount') is-invalid @enderror"
                                            placeholder="@lang('1000.0')" step="0.01" min="0" required>
                                        @error('amount')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row col-12 p-0 m-0">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <div class="form-group">
                                    <label for="description">@lang('Description')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                                        </div>
                                        <textarea name="description" id="description"
                                            class="form-control @error('description') is-invalid @enderror" rows="2"
                                            placeholder="@lang('description')">{{ old('description', $budget->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row col-12 p-0 m-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 col-form-label"></label>
                                    <div class="col-md-8">
                                        <input type="submit" value="{{ __('Update') }}"
                                            class="btn btn-outline btn-info btn-md" />
                                        <a href="{{ route('budgets.index') }}"
                                            class="btn btn-outline btn-warning btn-md">{{ __('Cancel') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
