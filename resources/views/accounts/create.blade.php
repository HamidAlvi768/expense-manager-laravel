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
                        <a href="{{ route('accounts.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('accounts.index') }}">@lang('Account ')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Add Account ')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Add Account ')</h3>
                </div>
                <div class="card-body">
                    <form id="accountForm" data-parsley-validate class="form-material form-horizontal"
                        action="{{ route('accounts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row col-12 p-0 m-0">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="account_title">@lang('Account Title') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input type="text" id="account_title" name="account_title" value="{{ old('account_title') }}"
                                            class="form-control @error('account_title') is-invalid @enderror"
                                            placeholder="@lang('ABC Account')" required
                                            data-parsley-required-message="Please enter a Account Title">
                                        @error('account_title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="account_type_id">@lang('Account Type')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <select name="account_type_id" id="account_type_id" class="form-control select2"  required data-parsley-required-message="Please Select a Account Type">
                                            <option value="" disabled {{ old('account_type_id') ? '' : 'selected' }}>Select Account Type</option>
                                            @foreach ($accountTypes as $type)
                                                <option value="{{ $type->id }}" {{ old('account_type_id') == $type->id ? 'selected' : '' }}>
                                                    {{ $type->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('account_type_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="balance">@lang('Balance')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        <input type="number" id="balance" name="balance" value="{{ old('balance') }}"
                                            class="form-control @error('balance') is-invalid @enderror"
                                            placeholder="@lang('Balance')" step="1" min="0" required>
                                        @error('balance')
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
                                    <label for="notes">@lang('Notes')</label>
                                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="4"
                                        placeholder="@lang('Notes')">{{ old('notes') }}</textarea>
                                    @error('notes')
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
                                        <a href="{{ route('accounts.index') }}"
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
