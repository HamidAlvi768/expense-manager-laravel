@extends('layouts.layout')
@section('content')
<style>
    .errors-ul {
    margin: 2.5rem 2rem 0;
    
}
</style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3>
                        <a href="{{ route('bank-statements.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('bank-statements.index') }}">@lang('All Uploaded Statements')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Upload New Bank Statement')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Upload New Bank Statement')</h3>
                </div>
                <div class="card-body">
                    <form id="bankStatementForm" class="form-material form-horizontal bg-custom"
                        action="{{ route('bank-statements.store') }}" method="POST" enctype="multipart/form-data"
                        data-parsley-validate>
                        @csrf
                        <div class="row col-12 m-0 p-0">

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="account_id">@lang('Account')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <select name="account_id" id="account_id" class="form-control select2"  required data-parsley-required-message="Please Select a Account">
                                            <option value="" disabled {{ old('account_id') ? '' : 'selected' }}>Select Account</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                                    {{ $account->account_title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('account_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="file">@lang('File')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-file"></i></span>
                                        </div>
                                        <input type="file" class="form-control" id="file" name="file" required data-parsley-required-message="Please Upload a Csv File">
                                        @error('file')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="bank_type">@lang('Bank')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <select name="bank_type" id="bank_type" class="form-control select2" required data-parsley-required-message="Please Select a Bank">
                                            <option value="" disabled {{ old('bank_type') ? '' : 'selected' }}>Select Bank</option>
                                            <option value="meezan" {{ old('bank_type') == 'meezan' ? 'selected' : '' }}>Meezan</option>
                                            <option value="hbl" {{ old('bank_type') == 'hbl' ? 'selected' : '' }}>HBL</option>
                                            <option value="habib_metro" {{ old('bank_type') == 'habib_metro' ? 'selected' : '' }}>Habib Metro</option>
                                            <option value="allied" {{ old('bank_type') == 'allied' ? 'selected' : '' }}>Allied Bank</option>
                                        </select>
                                        @error('bank_type')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <div class="row col-12 m-0 p-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-8 pt-2">
                                        <input type="submit" value="{{ __('Upload') }}"
                                            class="btn btn-outline btn-info btn-md" />
                                        <a href="{{ route('bank-statements.index') }}"
                                            class="btn btn-outline btn-warning btn-md">
                                            {{ __('Cancel') }}
                                        </a>
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
