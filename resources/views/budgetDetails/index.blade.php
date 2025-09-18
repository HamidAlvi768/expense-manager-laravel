@extends('layouts.layout')
@section('content')
    <style>
        body {
            overscroll-x: hidden;
        }
    </style>

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                        <h3>
                            <a href="{{ route('budgetDetails.create') }}" class="btn btn-outline btn-info">
                                + @lang('Add Budget Details')
                            </a>
                            <span class="pull-right"></span>
                        </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('Budget Details List')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Budget Details List')</h3>
                    <div class="card-tools">
                        <a class="btn btn-primary float-right" target="_blank"
                            href="{{ route('budgetDetails.index') }}?export=1">
                            <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                        </a>
                        <button class="btn btn-default" data-toggle="collapse" href="#filter">
                            <i class="fas fa-filter"></i> @lang('Filter')
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if (request()->isFilterActive) show @endif">
                        <div class="card-body border">
                            <form action="" method="get" role="form" autocomplete="off">
                                <input type="hidden" name="isFilterActive" value="true">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Category')</label>
                                            <input type="text" name="category_id" class="form-control"
                                                value="{{ request()->category_id }}" placeholder="@lang('Category')">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Balance')</label>
                                            <input type="text" name="balance" class="form-control"
                                                value="{{ request()->balance }}" placeholder="@lang('Balance')">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 align-content-center">
                                        <button type="submit" class="btn btn-info mt-4">@lang('Submit')</button>
                                        @if (request()->isFilterActive)
                                            <a href="{{ route('budgetDetails.index') }}"
                                                class="btn btn-secondary mt-4">@lang('Clear')</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table class="table table-striped" id="laravel_datatable">
                        <thead>
                            <tr>
                                <th>@lang('Income Category')</th>
                                <th>@lang('Balance')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($budgetDetails as $account)
                                <tr>
                                    <td>{{ $account->incomeCategory->title ?? '-' }}</td>
                                    <td>{{ $account->balance ?? '-' }}</td>
                                    <td>{{ $account->status ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('budgetDetails.show', $account) }}"
                                            class="btn btn-info btn-outline btn-circle btn-lg"
                                            data-toggle="tooltip" title="@lang('View')">
                                            <i class="fa fa-eye ambitious-padding-btn"></i>
                                        </a>
                                       
                                            <a href="{{ route('budgetDetails.edit', $account) }}"
                                                class="btn btn-info btn-outline btn-circle btn-lg"
                                                data-toggle="tooltip" title="@lang('Edit')">
                                                <i class="fa fa-edit ambitious-padding-btn"></i>
                                            </a>
                                      
                                            <a href="#"
                                                data-href="{{ route('budgetDetails.destroy', $account) }}"
                                                class="btn btn-info btn-outline btn-circle btn-lg"
                                                data-toggle="modal" data-target="#myModal" title="@lang('Delete')">
                                                <i class="fa fa-trash ambitious-padding-btn"></i>
                                            </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $budgetDetails->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Optional: add any necessary JavaScript for interactive features
        });
    </script>

    @include('layouts.delete_modal')
@endsection
