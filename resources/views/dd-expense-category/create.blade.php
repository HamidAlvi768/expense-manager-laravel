@extends('layouts.layout')
@section('content')
<style>
    .errors-ul {
        margin: 2.4rem 0 0;
    
}
</style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3>
                        <a href="{{ route('dd-expense-category.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dd-expense-category.index') }}">@lang('All Expense Categories')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Add New Expense Category')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Add New Expense Category')</h3>
                </div>
                <div class="card-body">
                    <form id="departmentForm" class="form-material form-horizontal bg-custom"
                        action="{{ route('dd-expense-category.store') }}" method="POST" enctype="multipart/form-data"
                        data-parsley-validate>
                        @csrf
                        <div class="row col-12 m-0 p-0">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="title">@lang('Title') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                                            class="form-control @error('title') is-invalid @enderror"
                                            placeholder="@lang('Title')" required data-parsley-required="true"
                                            data-parsley-required-message="Please enter Expense Category title."
                                            data-parsley-pattern="^[a-zA-Z\s]+$" data-parsley-trigger="focusout">
                                        @error('title')
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
                                        <input type="submit" value="{{ __('Submit') }}"
                                            class="btn btn-outline btn-info btn-md" />
                                        <a href="{{ route('dd-expense-category.index') }}"
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
