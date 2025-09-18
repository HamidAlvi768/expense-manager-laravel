@extends('layouts.layout')
@section('content')

<<<<<<< HEAD
<style>
    .parsley-errors-list{
        top: 100%;
    }
</style>
=======
    <style>
        .parsley-errors-list {
            top: 100%;
        }
    </style>
>>>>>>> 59200bb (Initial commit with expense manager code)
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 d-flex">
                    <h3 class="mr-2">
                        <a href="{{ route('accounts.create') }}" class="btn btn-outline btn-info">
                            + @lang('Add Account')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
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
                            <a href="{{ route('accounts.index') }}">@lang('Account')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Edit Account')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">Edit Account ({{ $account->user->name }}) </h3>
                </div>
                <div class="card-body">
                    <form id="accountForm" class="form-material form-horizontal bg-custom"
<<<<<<< HEAD
                        action="{{ route('accounts.update', $account) }}" method="POST"
                        enctype="multipart/form-data" data-parsley-validate>
=======
                        action="{{ route('accounts.update', $account) }}" method="POST" enctype="multipart/form-data"
                        data-parsley-validate>
>>>>>>> 59200bb (Initial commit with expense manager code)
                        @csrf
                        @method('PUT')
                        <div class="row col-12 p-0 m-0">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <div class="form-group">
<<<<<<< HEAD
                                    <label for="account_title">@lang('Account Title') <b class="ambitious-crimson">*</b></label>
=======
                                    <label for="account_title">@lang('Account Title') <b
                                            class="ambitious-crimson">*</b></label>
>>>>>>> 59200bb (Initial commit with expense manager code)
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
<<<<<<< HEAD
                                        <input type="text" id="account_title" name="account_title" value="{{ old('account_title', $account->account_title) }}"
=======
                                        <input type="text" id="account_title" name="account_title"
                                            value="{{ old('account_title', $account->account_title) }}"
>>>>>>> 59200bb (Initial commit with expense manager code)
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
<<<<<<< HEAD
                                        <select name="account_type_id" id="account_type_id" class="form-control select2"  required data-parsley-required-message="Please Select a Account Type">
=======
                                        <select name="account_type_id" id="account_type_id" class="form-control select2"
                                            required data-parsley-required-message="Please Select a Account Type">
>>>>>>> 59200bb (Initial commit with expense manager code)
                                            <option value="" disabled>Select Account Type</option>
                                            @foreach ($accountTypes as $type)
                                                <option value="{{ $type->id }}" {{ $account->account_type_id == $type->id ? 'selected' : '' }}>
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
<<<<<<< HEAD
                                        <input type="number" id="balance" name="balance" value="{{ old('balance', $account->balance) }}"
=======
                                        <input type="number" id="balance" name="balance"
                                            value="{{ old('balance', $account->balance) }}"
>>>>>>> 59200bb (Initial commit with expense manager code)
                                            class="form-control @error('balance') is-invalid @enderror"
                                            placeholder="@lang('Balance')" step="1" min="0" disabled>
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
<<<<<<< HEAD
                                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="4"
=======
                                    <textarea name="notes" id="notes"
                                        class="form-control @error('notes') is-invalid @enderror" rows="4"
>>>>>>> 59200bb (Initial commit with expense manager code)
                                        placeholder="@lang('Notes')">{{ old('notes', $account->notes) }}</textarea>
                                    @error('notes')
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

    {{-- Commented Logs --}}
    {{-- @if($logs)
<<<<<<< HEAD
        <div class="container mt-2">
            @canany(['userlog-read'])
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">User Logs</h3>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>Table</th>
                                <th>Column</th>
                                <th>Old Value</th>
                                <th>New Value</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->user->name }}</td>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->table_name }}</td>
                                    <td>{{ $log->column_name }}</td>
                                    <td>{{ $log->old_value }}</td>
                                    <td>{{ $log->new_value }}</td>
                                    <td>{{ $log->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endcanany
        </div>
    @endif --}}

@endsection
=======
    <div class="container mt-2">
        @canany(['userlog-read'])
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User Logs</h3>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Table</th>
                        <th>Column</th>
                        <th>Old Value</th>
                        <th>New Value</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->user->name }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->table_name }}</td>
                        <td>{{ $log->column_name }}</td>
                        <td>{{ $log->old_value }}</td>
                        <td>{{ $log->new_value }}</td>
                        <td>{{ $log->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endcanany
    </div>
    @endif --}}

@endsection
>>>>>>> 59200bb (Initial commit with expense manager code)
