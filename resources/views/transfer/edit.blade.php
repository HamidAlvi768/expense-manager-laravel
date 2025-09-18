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
                        <a href="{{ route('transfers.create') }}" class="btn btn-outline btn-info">
                            + @lang('Add Transfer')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                    <h3>
                        <a href="{{ route('transfers.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('transfers.index') }}">@lang('Transfers')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Edit Transfer')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">Edit Transfer ({{ $transfer->user->name }}) </h3>
                </div>
                <div class="card-body">
                    <form id="accountForm" class="form-material form-horizontal bg-custom"
                        action="{{ route('transfers.update', $transfer) }}" method="POST"
                        enctype="multipart/form-data" data-parsley-validate>
                        @csrf
                        @method('PUT')
                        <div class="row col-12 p-0 m-0">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="from_account_id">@lang('From Account')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <select name="from_account_id" id="from_account_id" class="form-control select2"  required data-parsley-required-message="Please Select a Account">
                                            <option value="" disabled>Select Account</option>
                                            @foreach ($fromAccounts as $account)
                                                <option value="{{ $account->id }}" {{ $transfer->from_account_id == $account->id ? 'selected' : '' }}>
                                                    {{ $account->account_title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('from_account_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="to_account_id">@lang('To Account')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <select name="to_account_id" id="to_account_id" class="form-control select2"  required data-parsley-required-message="Please Select a Account">
                                            <option value="" disabled>Select Account</option>
                                            @foreach ($toAccounts as $account)
                                                <option value="{{ $account->id }}" {{ $transfer->to_account_id == $account->id ? 'selected' : '' }}>
                                                    {{ $account->account_title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('to_account_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="transfer_amount">@lang('Amount')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        <input type="number" id="transfer_amount" name="transfer_amount" value="{{ old('transfer_amount', $transfer->transfer_amount) }}"
                                            class="form-control @error('transfer_amount') is-invalid @enderror"
                                            placeholder="@lang('1000.0')" step="1" min="0" required>
                                        @error('transfer_amount')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="transfer_date">@lang('Transfer Date')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="date" id="transfer_date" name="transfer_date" value="{{ old('transfer_date', $transfer->transfer_date) }}" 
                                        class="form-control flatpickr @error('transfer_date') is-invalid @enderror" required>
                                        @error('transfer_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row  col-12 p-0 m-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">@lang('Description')</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4"
                                        placeholder="@lang('Description')">{{ old('description', $transfer->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
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
                                        <a href="{{ route('transfers.index') }}"
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
