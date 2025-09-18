@extends('layouts.layout')

@section('content')
<style>
    .errors-ul {
        margin: 0.01rem 0 0;
    }

    .table>tbody>tr>td:last-child, .table>thead>tr>th:last-child {
        text-align: center;
    }
    
    /* Desktop styles */
    @media (min-width: 768px) {
        .month-container {
            display: flex;
            gap: 20px;
            max-width: 400px;
        }

        .month-container .form-group {
            flex: 1;
        }

        /* Ensure select2 doesn't overflow */
        .month-container .select2-container {
            width: 100% !important;
        }

        /* Make the description cell larger */
        #budgetItemsTable td:nth-child(3) {
            width: 30%;
        }
    }
    
    /* Mobile styles */
    @media (max-width: 767.98px) {
        #parsley-id-19 {
            margin-top: 2.3rem;
            right: 0.01rem;
            font-size: 0.7rem;
        }

        #parsley-id-21 {
            left: 55%;
            font-size: 0.7rem;
        }

        #parsley-id-23 {
            left: 0%;
            font-size: 0.7rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .month-container {
            margin-bottom: 1rem;
        }

        .month-container label {
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }

        /* Table styles for mobile */
        #budgetItemsTable td::before {
            display: none;
        }

        #budgetItemsTable thead {
            display: none;
        }

        #budgetItemsTable,
        #budgetItemsTable tbody,
        #budgetItemsTable tr {
            display: block;
            background: none;
            border: none;
        }

        #budgetItemsTable tr {
            background: #ebe6e6;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
            padding: 1rem;
            position: relative;
        }

        #budgetItemsTable td {
            display: block;
            border: none;
            padding: 0.5rem;
        }

        /* Category select */
        #budgetItemsTable td:nth-child(1) {
            width: 50%;
            float: left;
            padding-right: 5px;
            margin-bottom: 10px;
        }

        /* Amount input */
        #budgetItemsTable td:nth-child(2) {
            width: 50%;
            float: left;
            padding-left: 5px;
            padding-right: 0;
            margin-bottom: 10px;
        }

        /* Description field */
        #budgetItemsTable td:nth-child(3) {
            width: 104%;
            clear: both;
        }

        /* Action buttons */
        #budgetItemsTable td:nth-child(4) {
            width: 100%;
            clear: both;
            text-align: center;
            border-top: 1px solid #eee;
            margin-top: 0.5rem;
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }

        /* Select2 mobile adjustments */
        .select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: 38px;
            padding: 8px;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal;
            font-size: 0.8rem;
        }
    }
</style>

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>
                        <a href="{{ route('budgets.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')
                        </a>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('budgets.index') }}">@lang('Budgets')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Add Budget')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Add Budget')</h3>
                </div>
                <div class="card-body">
                    <form id="budgetForm" data-parsley-validate class="form-material form-horizontal"
                        action="{{ route('budgets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="month-container">
                            <div class="form-group">
                                <label for="month">@lang('Month')</label>
                                <select id="month" name="month" class="form-control select2" required
                                    data-parsley-required-message="Please Select a Month">
                                    <option value="" disabled {{ old('month') ? '' : 'selected' }}>@lang('Select Month')</option>
                                    @foreach (range(1, 12) as $month)
                                        <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}"
                                            {{ old('month') == str_pad($month, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                            @lang(date('F', mktime(0, 0, 0, $month, 10)))
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="budgetItemsTable">
                                <thead>
                                    <tr>
                                        <th>@lang('Expense Category')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Description')</th>
                                        <th style="text-align: center;">@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td data-label="@lang('Expense Category')">
                                            <select name="budget_items[0][expense_category_id]" class="form-control mobile-select" required
                                                data-parsley-required-message="Please Select a Category">
                                                <option value="" disabled {{ old('expense_category_id') ? '' : 'selected' }}>
                                                    Select Expense
                                                </option>
                                                @foreach ($expenseCategories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('expense_category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td data-label="@lang('Amount')">
                                            <input type="number" name="budget_items[0][amount]" class="form-control amount"
                                                placeholder="Amount"
                                                step="1" min="1" required
                                                data-parsley-required-message="Please enter an amount"
                                                data-parsley-min="1"
                                                data-parsley-min-message="Amount must be greater or equal to 1">
                                        </td>
                                        <td data-label="@lang('Description')">
                                            <input type="text" name="budget_items[0][description]" class="form-control"
                                                placeholder="Description">
                                        </td>
                                        <td data-label="@lang('Action')" class="action-cell">
                                            <div class="action-buttons">
                                                <button type="button" class="btn btn-success add-row">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger remove-row">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <hr>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-info">@lang('Submit')</button>
                            <a href="{{ route('budgets.index') }}" class="btn btn-warning">@lang('Cancel')</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    $(document).ready(function() {
        // Handle mobile select placeholder
        if (window.innerWidth <= 767.98) {
            $('.mobile-select option[value=""]').text('Category');
        }
    });

    $(document).on('click', '.add-row', function() {
        let newRow = $('#budgetItemsTable tbody tr:first').clone();

        // Clear values but maintain placeholders
        newRow.find('input[type="number"]').attr('placeholder', 'Amount').val('');
        newRow.find('input[type="text"]').attr('placeholder', 'Description').val('');
        newRow.find('select option[value=""]').text('Category');
        newRow.find('select').val('');

        // Clean up validation classes and attributes
        newRow.find('.parsley-error').removeClass('parsley-error');
        newRow.find('.is-invalid').removeClass('is-invalid');
        newRow.find('.parsley-errors-list').remove();
        newRow.find('[aria-invalid]').removeAttr('aria-invalid');
        newRow.find('[aria-describedby]').removeAttr('aria-describedby');
        newRow.find('[data-parsley-id]').removeAttr('data-parsley-id');

        // Update indices
        let rowCount = $('#budgetItemsTable tbody tr').length;
        newRow.find('select, input').each(function() {
            let name = $(this).attr('name');
            if (name) {
                name = name.replace(/\[\d+\]/, `[${rowCount}]`);
                $(this).attr('name', name);
            }
        });

        $('#budgetItemsTable tbody').append(newRow);
    });

    $(document).on('click', '.remove-row', function() {
        if ($('#budgetItemsTable tbody tr').length > 1) {
            let confirmed = confirm('Are you sure you want to remove this row?');
            if (confirmed) {
                $(this).closest('tr').remove();
            }
        } else {
            alert('At least one budget item is required.');
        }
    });

    var extraUlClass = "errors-ul";
</script>
@endsection
