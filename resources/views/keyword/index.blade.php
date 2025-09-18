@extends('layouts.layout')

@section('content')
    <link href="{{ asset('assets/css/index-mediaquery.css') }}" rel="stylesheet">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center">
                        <h3 class="mb-0">
                            <a href="{{ route('keywords.create') }}" class="btn btn-outline btn-info">
                                + @lang('Add Keyword')
                            </a>
                        </h3>
                        <div class="ml-auto d-flex gap-2">
                            <a class="btn btn-sm btn-default" target="_blank" href="{{ route('keywords.index') }}?export=1">
                                <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                            </a>
                            <button class="btn btn-sm btn-default" data-toggle="collapse" href="#filter">
                                <i class="fas fa-filter"></i> @lang('Filter')
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('Keywords List')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title pt-2">@lang('Keywords List')</h3>
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if (request()->has('isFilterActive')) show @endif">
                        <div class="card-body border">
                            <form action="{{ route('keywords.index') }}" method="get" role="form" autocomplete="off">
                                <input type="hidden" name="isFilterActive" value="true">
                                <div class="row">
                                    <!-- Title Filter -->
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Title')</label>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ request()->input('title') }}" placeholder="@lang('Title')">
                                        </div>
                                    </div>
                    
                                    <!-- Type Filter -->
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="type">@lang('Type')</label>
                                            <select name="type" id="type" class="form-control select2 @error('type') is-invalid @enderror">
                                                <option value="" disabled {{ request()->input('type') ? '' : 'selected' }}>@lang('Select Type')</option>
                                                <option value="expense" {{ request()->input('type') == 'expense' ? 'selected' : '' }}>@lang('Expenses')</option>
                                                <option value="income" {{ request()->input('type') == 'income' ? 'selected' : '' }}>@lang('Incomes')</option>
                                            </select>
                                        </div>
                                    </div>
                    
                                    <!-- Category Filter -->
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="category_id">@lang('Category')</label>
                                            <select name="category_id" id="category_id" class="form-control select2 @error('category_id') is-invalid @enderror">
                                                <option value="" disabled {{ request()->input('category_id') ? '' : 'selected' }}>@lang('Select Category')</option>
                                                <!-- JS will populate options here -->
                                            </select>
                                        </div>
                                    </div>
                    
                                    <!-- Submit & Clear Buttons -->
                                    <div class="col-sm-4 align-content-center">
                                        <button type="submit" class="btn btn-info mt-4">@lang('Submit')</button>
                                        @if (request()->has('isFilterActive'))
                                            <a href="{{ route('keywords.index') }}" class="btn btn-secondary mt-4">@lang('Clear')</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <table class="table table-striped" id="keyword_table">
                        <thead>
                            <tr>
                                <th>@lang('Title')</th>
                                <th>@lang('Category')</th>
                                <th>@lang('Type')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($keywords as $keyword)
                                <tr>
                                    <td><span style="text-wrap:nowrap;">{{ $keyword->title ?? '-' }}</span></td>
                                    <td>{{ $keyword->category->title ?? '-' }}</td>
                                    <td>{{ ucfirst($keyword->type) }}</td>
                                    <td>
                                        @if ($keyword->status == '1')
                                            <span class="badge badge-pill badge-success">@lang('Active')</span>
                                        @else
                                            <span class="badge badge-pill badge-danger">@lang('Inactive')</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('keywords.show', $keyword) }}"
                                            class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip"
                                            title="@lang('View')"><i class="fa fa-eye ambitious-padding-btn"></i></a>
                                        {{-- @can('keyword-update') --}}
                                            <a href="{{ route('keywords.edit', $keyword) }}"
                                                class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip"
                                                title="@lang('Edit')"><i class="fa fa-edit ambitious-padding-btn"></i></a>
                                        {{-- @endcan --}}
                                        {{-- @can('keyword-delete') --}}
                                        @if (($keyword->type === 'income' && !\App\Models\Income::where('income_category_id', $keyword->category_id)->exists()) ||
                                                ($keyword->type === 'expense' && !\App\Models\Expense::where('expense_category_id', $keyword->category_id)->exists()))
                                            <a href="#" data-href="{{ route('keywords.destroy', $keyword) }}"
                                                class="btn btn-info btn-outline btn-circle btn-lg" 
                                                data-toggle="modal" data-target="#myModal" 
                                                title="@lang('Delete')">
                                                <i class="fa fa-trash ambitious-padding-btn"></i>
                                            </a>
                                        @endif
                                        {{-- @endcan --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $keywords->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('layouts.delete_modal')
    <script>
$(document).ready(function () {
    function populateCategories(type, selectedCategoryId = null) {
        if (type) {
            $('#category_id').empty(); // Clear previous options
            $('#category_id').append('<option value="" disabled>Select a Category</option>'); // Add default option

            $.ajax({
                url: '{{ route('keywords.getCategories') }}', // The route you created
                type: 'GET',
                dataType: 'json',
                data: {
                    type: type,
                },
                success: function (data) {
                    $.each(data, function (index, category) {
                        $('#category_id').append(
                            '<option value="' + category.id + '"' +
                            (category.id == selectedCategoryId ? ' selected' : '') + // Select previously chosen category
                            '>' + category.title + '</option>'
                        );
                    });
                },
                error: function (xhr) {
                    console.error(xhr.responseText); // Log any errors for debugging
                },
            });
        } else {
            $('#category_id').empty(); // Clear if no type is selected
            $('#category_id').append('<option value="" disabled>Select a Category</option>'); // Add default option
        }
    }

    // Trigger on type dropdown change
    $('#type').on('change', function () {
        const type = $(this).val();
        populateCategories(type);
    });

    // Populate categories on page load if `type` and `category_id` exist
    const selectedType = $('#type').val(); // Get the currently selected type
    const selectedCategoryId = "{{ request()->input('category_id') }}"; // Get category_id from request

    if (selectedType) {
        populateCategories(selectedType, selectedCategoryId);
    }
});

</script>
@endsection
