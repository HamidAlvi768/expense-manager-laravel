@extends('layouts.layout')

@section('content')
<style>
    .parsley-errors-list{
        top: 100%;
    }
</style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="col-sm-6 d-flex">
                        <h3 class="mr-2">
                            <a href="{{ route('keywords.create') }}" class="btn btn-outline btn-info">
                                + @lang('Add Keyword')
                            </a>
                            <span class="pull-right"></span>
                        </h3>
                        <h3>
                            <a href="{{ route('keywords.index') }}" class="btn btn-outline btn-info"> @lang('View Keywords')</a>
                        </h3>
                    </div>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('keywords.index') }}">@lang('Keywords')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Edit Keyword')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="card-title d-inline">@lang('Edit Keyword') ({{ $keyword->title }})</h3>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form id="keywordForm" class="form-material form-horizontal bg-custom" action="{{ route('keywords.update', $keyword) }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
                        @csrf
                        @method('PUT')

                        <div class="row col-12 m-0 p-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">@lang('Title') <b class="text-danger">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input type="text" id="title" name="title" value="{{ old('title', $keyword->title) }}" class="form-control @error('title') is-invalid @enderror" placeholder="@lang('Title')" required data-parsley-required="true" data-parsley-required-message="Please enter keyword title." data-parsley-trigger="change focusout">
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
                                    <label for="type">@lang('Type')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-exchange-alt"></i></span>
                                        </div>
                                        <select name="type" id="type" class="form-control select2 @error('type') is-invalid @enderror">
                                            <option value="" disabled>Select Type</option>
                                            <option value="expense" {{ old('type', $keyword->type) == 'expense' ? 'selected' : '' }}>@lang('Expenses')</option>
                                            <option value="income" {{ old('type', $keyword->type) == 'income' ? 'selected' : '' }}>@lang('Incomes')</option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category_id">@lang('Category')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-list-alt"></i></span>
                                        </div>
                                        <select name="category_id" id="category_id" class="form-control select2 @error('category_id') is-invalid @enderror">
                                            <option value="" disabled>@lang('Select Category')</option>
                                            <option value="{{ $keyword->category_id }}"  selected>{{ $keyword->category->title }}</option>
                                            <!-- Categories are populated dynamically, using the old input and current data -->
                                        </select>
                                        @error('category_id')
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
<<<<<<< HEAD
                                        <button type="submit" class="btn btn-outline btn-info btn-md">{{ __('Update') }}</button>
                                        <a href="{{ route('keywords.index') }}" class="btn btn-outline btn-warning btn-md">{{ __('Cancel') }}</a>
=======
                                        <button type="submit" class="btn btn-outline btn-info btn-lg">{{ __('Update') }}</button>
                                        <a href="{{ route('keywords.index') }}" class="btn btn-outline btn-warning btn-lg">{{ __('Cancel') }}</a>
>>>>>>> 59200bb (Initial commit with expense manager code)
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
        $(document).ready(function() {
            $('#type').on('change', function() {
                var type = $(this).val();
                if (type) {
                    $('#category_id').empty(); // Clear previous options
                    $('#category_id').append('<option value="" disabled selected>Select a new Category</option>'); // Add default option
                    $.ajax({
                        url: '{{ route('keywords.getCategories') }}', // The route to fetch categories
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            type: type,
                        },
                        success: function(data) {
                            $.each(data, function(index, category) {
                                $('#category_id').append('<option value="' + category.id + '">' + category.title + '</option>');
                            });
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText); // Log any errors for debugging
                        }
                    });
                } else {
                    $('#category_id').prop('disabled', false).empty(); // Disable and clear the category dropdown if no type is selected
                    $('#category_id').append('<option value="" disabled selected>Select Category</option>'); // Add default option
                }
            });
        });
    </script>
@endsection
