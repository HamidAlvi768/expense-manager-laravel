@extends('layouts.layout')

@section('content')
<style>
    .errors-ul {
        margin: 2.5rem 0 0;
    
}
</style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>
                        <a href="{{ route('keywords.index') }}" class="btn btn-outline btn-info">
                           <i class="fas fa-eye"></i> @lang('View Keywords')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('keywords.index') }}">@lang('Keywords')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Add Keyword')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Add Keyword')</h3>
                </div>
                <div class="card-body">
                    <form id="keywordForm" class="form-material form-horizontal bg-custom" action="{{ route('keywords.store') }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
                        @csrf
                        <div class="row col-12 m-0 p-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">@lang('Title') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input type="text" id="title" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" placeholder="@lang('Title')" required data-parsley-required="true"
                                        data-parsley-required-message="Please enter keyword title." data-parsley-trigger="change focusout">
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
                                    <label for="type">@lang('Type') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-exchange-alt"></i></span>
                                        </div>
                                        <select name="type" id="type" class="form-control select2 @error('type') is-invalid @enderror" required data-parsley-required="true" data-parsley-required-message="@lang('Please select type')" data-parsley-trigger="change focusout">
                                            <option value="">@lang('Select Type')</option>
                                            <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>@lang('Expenses')</option>
                                            <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>@lang('Incomes')</option>
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
                                    <label for="category_id">@lang('Category') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-list-alt"></i></span>
                                        </div>
                                        <select name="category_id" id="category_id" class="form-control select2 @error('category_id') is-invalid @enderror" required data-parsley-required="true" data-parsley-required-message="@lang('Please select category')" data-parsley-trigger="change focusout">
                                            <option value="">@lang('Select Category')</option>
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
                        <div class="form-group">
                            <label class="col-md-3 col-form-label"></label>
                            <div class="col-md-8">
                                <input type="submit" value="{{ __('Submit') }}" class="btn btn-outline btn-info btn-md" />
                                <a href="{{ route('keywords.index') }}" class="btn btn-outline btn-warning btn-md">{{ __('Cancel') }}</a>
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
                        url: '{{route('keywords.getCategories') }}', // The route you created
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
                    $('#category_id').prop('disabled', false).empty(); // Disable and clear the service dropdown if no category is selected
                    $('#category_id').append('<option value="" disabled selected>Select Category</option>'); // Add default option

                }
            });
        });
        var extraUlClass="errors-ul";
    </script>
@endsection


