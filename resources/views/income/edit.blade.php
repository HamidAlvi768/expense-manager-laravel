@extends('layouts.layout')
@section('content')
<style>
    .parsley-errors-list{
        top: 100%;
    }
</style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 d-flex">
                    <h3 class="mr-2">
                        <a href="{{ route('incomes.create') }}" class="btn btn-outline btn-info">
                            + @lang('Add Income')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                    <h3>
                        <a href="{{ route('incomes.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('incomes.index') }}">@lang('Income')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Edit Income')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Edit Income') ({{ $income->account->account_title }})</h3>
                </div>
                <div class="card-body">
                    <form id="incomeForm" class="form-material form-horizontal bg-custom"
                        action="{{ route('incomes.update', $income) }}" method="POST"
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
                                            <option value="{{ $income->account_id }}" selected> {{ $income->account->account_title }}</option>


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
                                    <label for="income_category_id">@lang('Income Category')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-list-alt"></i></span>
                                        </div>
                                        <select name="income_category_id" id="income_category_id" class="form-control select2" required>
                                            <option value="" disabled>@lang('Select Category')</option>
                                            @foreach ($incomeCategories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $category->id == $income->income_category_id ? 'selected' : '' }}>
                                                    {{ $category->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('income_category_id')
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
                                    <label for="income_date">@lang('Income Date')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="date" id="income_date" name="income_date"
                                            value="{{ old('income_date', $income->income_date) }}"
                                            class="form-control @error('income_date') is-invalid @enderror" required>
                                        @error('income_date')
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
                                            value="{{ old('amount', $income->amount) }}"
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
                                            placeholder="@lang('description')">{{ old('description', $income->description) }}</textarea>
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
                                        <a href="{{ route('incomes.index') }}"
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
