@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3>
                        <a href="{{ route('budgetDetails.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('budgetDetails.index') }}">@lang('BudgetDetail')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Add Budget Detail ')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Add Budget Detail ')</h3>
                </div>
                <div class="card-body">
                    <form id="departmentForm" data-parsley-validate class="form-material form-horizontal bg-custom"
                        action="{{ route('budgetDetails.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- New Fields for BudgetDetail Information -->
                            <!-- Income Category ID Field -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="category_id">@lang('Income Category')</label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        <!-- Assuming you have a list of account types to select from -->
                                        @foreach ($incomeCategories as $type)
                                            <option value="{{ $type->id }}" {{ old('category_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Balance Field -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="balance">@lang('Balance')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        <input type="number" id="balance" name="balance" value="{{ old('balance') }}"
                                            class="form-control @error('balance') is-invalid @enderror"
                                            placeholder="@lang('Balance')" step="0.01">
                                        @error('balance')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Status Field -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">@lang('Status')</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>
                                            @lang('Yes')
                                        </option>
                                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>
                                            @lang('No')
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        
                        <!-- Submit Button -->
                        <div class="row  col-12 p-0 m-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 col-form-label"></label>
                                    <div class="col-md-8">
                                        <input type="submit" value="{{ __('Submit') }}"
                                            class="btn btn-outline btn-info btn-md" />
                                        <a href="{{ route('budgetDetails.index') }}"
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
