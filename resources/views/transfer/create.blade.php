@extends('layouts.layout')

@section('content')
<style>
    .errors-ul {
    margin: 40px 0 0;
}

#parsley-id-19, #parsley-id-21, #parsley-id-23 {
    margin-top: 2.5rem;
}
</style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
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
                            <a href="{{ route('transfers.index') }}">@lang('Transfers ')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Add Transfer ')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Add Transfer ')</h3>
                </div>
                <div class="card-body">
                    <form id="transferForm" data-parsley-validate class="form-material form-horizontal"
                        action="{{ route('transfers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row  col-12 p-0 m-0">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="from_account_id">@lang('From')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <select name="from_account_id" id="from_account_id" class="form-control select2"  required data-parsley-required-message="Please Select a Account">
                                            <option value="" disabled {{ old('from_account_id') ? '' : 'selected' }}>Select Account</option>
                                            @foreach ($fromAccounts as $account)
                                                <option value="{{ $account->id }}" {{ old('from_account_id') == $account->id ? 'selected' : '' }}>
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
                                    <label for="to_account_id">@lang('To')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <select name="to_account_id" id="to_account_id" class="form-control select2"  required data-parsley-required-message="Please Select a Account">
                                            <option value="" disabled {{ old('to_account_id') ? '' : 'selected' }}>Select Account</option>
                                            @foreach ($toAccounts as $account)
                                                <option value="{{ $account->id }}" {{ old('to_account_id') == $account->id ? 'selected' : '' }}>
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
                                        <input type="number" id="transfer_amount" name="transfer_amount" value="{{ old('transfer_amount') }}"
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
                                        <input type="date" id="transfer_date" name="transfer_date" value="{{ old('transfer_date', now()->format('Y-m-d')) }}" 
                                        class="form-control @error('transfer_date') is-invalid @enderror" required>
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
                                        placeholder="@lang('Description')">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row  col-12 p-0 m-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 col-form-label"></label>
                                    <div class="col-md-8">
                                        <input type="submit" value="{{ __('Submit') }}"
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
    <script>
        var extraUlClass="errors-ul";
    </script>
@endsection
