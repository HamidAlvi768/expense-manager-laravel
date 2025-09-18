@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('incomes.index') }}">@lang('Incomes')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Income Info')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Incomes Info')</h3>
                    {{-- @can('income-detail-update') --}}
                        <div class="card-tools">
                            <a href="{{ route('incomes.edit', $income) }}" class="btn btn-info">@lang('Edit')</a>
                        </div>
                    {{-- @endcan --}}
                </div>
                <div class="card-body">
                    <div class="bg-custom">
                        <div class="row m-0 p-0">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="user_id">@lang('User')</label>
                                    <p>{{ $income->user->name ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="account_id">@lang('Account')</label>
                                    <p>{{ $income->account->account_title ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="income_category_id">@lang('Type')</label>
                                    <p>{{ $income->incomeCategory->title ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="amount">@lang('Amount')</label>
                                    <p>{{ $income->amount ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="description">@lang('Description')</label>
                                    <p>{{ $income->description ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">@lang('Status')</label>
                                    <p>
                                        @if ($income->status == 1)
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
