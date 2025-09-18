@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('budgetDetails.index') }}">@lang('budget Detail')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('budget Detail Info')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('budget Detail Info')</h3>
                    @can('account-detail-update')
                        <div class="card-tools">
                            <a href="{{ route('budgetDetails.edit', $budgetDetail) }}" class="btn btn-info">@lang('Edit')</a>
                        </div>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="bg-custom">
                        <div class="row m-0 p-0">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="category_id">@lang('budget Detail Type')</label>
                                    <p>{{ $budgetDetail->category_id ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="balance">@lang('Balance')</label>
                                    <p>{{ $budgetDetail->balance ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">@lang('Status')</label>
                                    <p>
                                        @if ($budgetDetail->status == 1)
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
