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
                        <a href="{{ route('expenses.create') }}" class="btn btn-outline btn-info">
                            + @lang('Add Expense')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                    <h3>
                        <a href="{{ route('expenses.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('expenses.index') }}">@lang('Expenses')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Edit Expense')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Edit Expense') ({{ $expense->account->account_title ?? '-' }})</h3>
                </div>
                <div class="card-body">
                    <form id="expenseForm" class="form-material form-horizontal bg-custom"
                        action="{{ route('expenses.update', $expense) }}" method="POST"
                        enctype="multipart/form-data" data-parsley-validate>
                        @csrf
                        @method('PUT')

                        <div class="row col-12 p-0 m-0">
                            <!-- Account Selection -->
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="account_id">@lang('Account')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-university"></i></span>
                                        </div>
                                        <select name="account_id" id="account_id" class="form-control select2" required>
                                            <option value="" disabled>@lang('Select Account')</option>
                                            <option value="{{ $expense->account_id }}" selected> {{ $expense->account->account_title ?? "-" }}</option>
                                        </select>
                                        @error('account_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
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
                                                    {{ $category->id == $expense->expense_category_id ? 'selected' : '' }}>
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
                            <!-- Income Date -->
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="expense_date">@lang('Expense Date')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="date" id="expense_date" name="expense_date"
                                            value="{{ old('expense_date', $expense->expense_date) }}"
                                            class="form-control @error('expense_date') is-invalid @enderror" required>
                                        @error('expense_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- Income Amount -->
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="amount">@lang('Amount')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        <input type="number" id="amount" name="amount"
                                            value="{{ old('amount', $expense->amount) }}"
                                            class="form-control @error('amount') is-invalid @enderror"
                                            placeholder="@lang('1000.0')" step="0.01" min="1" required>
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
                                            placeholder="@lang('Description')">{{ old('description', $expense->description) }}</textarea>
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
                                        <a href="{{ route('expenses.index') }}"
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
