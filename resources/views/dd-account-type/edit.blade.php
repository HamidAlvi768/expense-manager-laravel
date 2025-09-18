@extends('layouts.layout')
@section('content')
<style>
    .parsley-errors-list{
        top: 100%;
    }
</style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 d-flex">
                    <h3 class="mr-2">
                        <a href="{{ route('dd-account-type.create') }}" class="btn btn-outline btn-info">
                            + @lang('Add New Account Type')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                    <h3>
                        <a href="{{ route('dd-account-type.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dd-account-type.index') }}">@lang('Account Types')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Edit Account Type')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">Edit Account Type ({{ $DdAccountType->title }})</h3>
                </div>
                <div class="card-body">
                    <form id="departmentForm" class="form-material form-horizontal bg-custom"
      action="{{ route('dd-account-type.update', $DdAccountType) }}"
      method="POST" enctype="multipart/form-data" data-parsley-validate>
    @csrf
    @method('PUT')

    <div class="row col-12 m-0 p-0">
        <div class="col-md-4">
            <div class="form-group">
                <label for="title">@lang('Title') <b class="ambitious-crimson">*</b></label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-signature"></i></span>
                    </div>
                    <input type="text" id="title" name="title"
                           value="{{ old('title', $DdAccountType->title) }}"
                           class="form-control @error('title') is-invalid @enderror"
                           placeholder="@lang('Title')" required
                           data-parsley-required-message="Please Enter a Title for Account Type."
                           data-parsley-trigger="change">
                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="status">@lang('Status') <b class="ambitious-crimson">*</b></label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-bell"></i></span>
                    </div>
                    <select class="form-control ambitious-form-loading @error('status') is-invalid @enderror"
                            required name="status" id="status"
                            data-parsley-trigger="change">
                        <option value="1" {{ old('status', $DdAccountType->status) === '1' ? 'selected' : '' }}>
                            @lang('Active')
                        </option>
                        <option value="0" {{ old('status', $DdAccountType->status) === '0' ? 'selected' : '' }}>
                            @lang('Inactive')
                        </option>
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

    <div class="row col-12 m-0 p-0">
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-md-3 col-form-label"></label>
                <div class="col-md-8">
                    <input type="submit" value="{{ __('Update') }}"
<<<<<<< HEAD
                           class="btn btn-outline btn-info btn-md">
                    <a href="{{ route('dd-account-type.index') }}"
                       class="btn btn-outline btn-warning btn-md">
=======
                           class="btn btn-outline btn-info btn-lg">
                    <a href="{{ route('dd-account-type.index') }}"
                       class="btn btn-outline btn-warning btn-lg">
>>>>>>> 59200bb (Initial commit with expense manager code)
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


    <script src="{{ asset('assets/js/custom/doctor-detail.js') }}"></script>
@endsection
