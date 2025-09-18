@extends('layouts.layout')
@section('content')
<link href="{{ asset('assets/css/expense/show.css') }}" rel="stylesheet">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('expenses.index') }}">@lang('Expenses')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Expense Info')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Expenses Info')</h3>
                    {{-- @can('expense-detail-update') --}}
                        <div class="card-tools">
                            <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-info">@lang('Edit')</a>
                        </div>
                    {{-- @endcan --}}
                </div>
                <div class="card-body">
                    <div class="bg-custom">
                        <div class="row m-0 p-0">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="user_id">@lang('User')</label>
                                    <p>{{ $expense->user->name ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="account_id">@lang('Account')</label>
                                    <p>{{ $expense->account->account_title ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="expense_category_id">@lang('Type')</label>
                                    <p>{{ $expense->expenseCategory->title ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="amount">@lang('Amount')</label>
                                    <p>{{ $expense->amount ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="description">@lang('Description')</label>
                                    <p>{{ $expense->description ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">@lang('Status')</label>
                                    <p>
                                        @if ($expense->status == 1)
                                            <span class="badge badge-pill badge-success">@lang('Active')</span>
                                        @else
                                            <span class="badge badge-pill badge-danger">@lang('Inactive')</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
