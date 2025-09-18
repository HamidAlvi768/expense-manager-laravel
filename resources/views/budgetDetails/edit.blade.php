@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 d-flex">
                    <h3 class="mr-2">
                        <a href="{{ route('budgetDetails.create') }}" class="btn btn-outline btn-info">
                            + @lang('Add budget Detail')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
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
                            <a href="{{ route('budgetDetails.index') }}">@lang('budget Detail')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Edit budget Detail')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">Edit budget Detail ({{ $budgetDetail->user->name }}) </h3>
                </div>
                <div class="card-body">
                    <form id="accountForm" class="form-material form-horizontal bg-custom"
                        action="{{ route('budgetDetails.update', $budgetDetail) }}" method="POST"
                        enctype="multipart/form-data" data-parsley-validate>
                        @csrf
                        @method('PUT')
                        <div class="row col-12 p-0 m-0">
                            <!-- budget Detail Type -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="account_type_id">@lang('budget Detail Type') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                        </div>
                                        <select name="account_type_id" id="account_type_id"
                                            class="form-control @error('account_type_id') is-invalid @enderror"
                                            required>
                                            <option value="">--@lang('Select budget Detail Type')--</option>
                                            @foreach ($incomeCategories as $type)
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

                            <!-- Balance -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="balance">@lang('Balance') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        <input type="number" id="balance" name="balance"
                                               value="{{ old('balance', $budgetDetail->balance) }}"
                                               class="form-control @error('balance') is-invalid @enderror"
                                               placeholder="@lang('Balance')" required
                                               step="0.01" min="0">
                                        @error('balance')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">@lang('Status')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                        </div>
                                        <select name="status" id="status"
                                            class="form-control @error('status') is-invalid @enderror">
                                            <option value="1"
                                                {{ old('status', $budgetDetail->status) == '1' ? 'selected' : '' }}>
                                                @lang('Yes')</option>
                                            <option value="0"
                                                {{ old('status', $budgetDetail->status) == '0' ? 'selected' : '' }}>
                                                @lang('No')</option>
                                        </select>
                                        @error('status')
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

    @if($logs)
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
    @endif

@endsection
