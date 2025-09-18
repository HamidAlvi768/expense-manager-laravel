@extends('layouts.layout')

@section('content')
<style>
    .errors-ul {
        margin: 0.01rem 0 0;
        
    }

    .table>tbody>tr>td:last-child, .table>thead>tr>th:last-child{
        text-align: center;
    }
    
    /* Desktop styles (min-width to ensure it doesn't affect mobile) */
    @media (min-width: 768px) {
        .account-date-container {
            display: flex;
            gap: 20px;
        }

        .account-date-container .form-group {
            flex: 1;
        }

        /* Ensure select2 doesn't overflow */
        .account-date-container .select2-container {
            width: 100% !important;
        }
    }
    
    @media (max-width: 767.98px) {
        
        /* Mobile styles for Account and Income Date fields */
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

        .account-date-container {
            display: flex;
            gap: 10px;
            margin-bottom: 1rem;
        }

        .account-date-container .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .account-date-container label {
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }

        .account-date-container .select2-container {
            width: 100% !important;
        }

        /* Rest of your existing mobile styles */
        #incomeItemsTable td::before {
            display: none;
        }

        #incomeItemsTable thead {
            display: none;
        }

        #incomeItemsTable,
        #incomeItemsTable tbody,
        #incomeItemsTable tr {
            display: block;
            background: none;
            border: none;
        }

        #incomeItemsTable tr {
            background: #ebe6e6;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
            padding: 1rem;
            position: relative;
        }

        #incomeItemsTable td {
            display: block;
            border: none;
            padding: 0.5rem;
        }

        /* Adjust select container width */
        #incomeItemsTable td:nth-child(1) {
            width: 50%;
            float: left;
            padding-right: 5px;
            margin-bottom: 10px;
        }

        /* Style select input */
        #incomeItemsTable td:nth-child(1) select.form-control {
            width: 105%;
            padding-right: 25px;
            /* Space for the dropdown arrow */
            text-overflow: ellipsis;
            font-size: 0.8rem;
        }

        /* Amount input styling */
        #incomeItemsTable td:nth-child(2) {
            width: 50%;
            float: left;
            padding-left: 5px;
            padding-right: 0;
            margin-bottom: 10px;
        }

        #incomeItemsTable td:nth-child(2) input {
            width: 100%;
        }

        /* Description field */
        #incomeItemsTable td:nth-child(3) {
            width: 104%;
            clear: both;
        }

        /* Action buttons */
        #incomeItemsTable td:nth-child(4) {
            width: 100%;
            clear: both;
            text-align: center;
            border-top: 1px solid #eee;
            margin-top: 0.5rem;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }

        /* Select2 adjustments for mobile */
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
        <div class="row">
            <div class="col-sm-6">
                <h3>
                    <a href="{{ route('incomes.index') }}" class="btn btn-outline btn-info">
                        <i class="fas fa-eye"></i> @lang('View All')
                    </a>
                    <span class="pull-right"></span>
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('incomes.index') }}">@lang('Income')</a>
                    </li>
                    <li class="breadcrumb-item active">@lang('Add Income')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">@lang('Add Income')</h3>
            </div>
            <div class="card-body">
                <form id="incomeForm" data-parsley-validate class="form-material form-horizontal" action="{{ route('incomes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="account-date-container">
                        <div class="form-group">
                            <label for="account_id">@lang('Account')</label>
                            <select name="account_id" id="account_id" class="form-control select2" required
                                data-parsley-required-message="Please select an account">
                                <option value="" disabled selected>Select Account</option>
                                @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->account_title }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="account_id_error"></span>
                        </div>
                        <div class="form-group" style="margin-right: -6.5%;">
                            <label for="income_date">@lang('Income Date')</label>
                            <input type="date" id="income_date" name="income_date" value="{{ old('expense_date', now()->format('Y-m-d')) }}" class="form-control" required
                                data-parsley-required-message="Please select a date" style="width: 87%;">
                            <span class="text-danger" id="income_date_error"></span>
                        </div>
                    </div>

                    <hr>

                    <!-- Income Items Section -->
                    <div class="table-responsive">
                        <table class="table table-bordered" id="incomeItemsTable">
                            <thead>
                                <tr>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Description')</th>
                                    <th style="text-align: center;">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="@lang('Category')">
                                        <select name="income_items[0][income_category_id]" class="form-control mobile-select" required
                                            data-parsley-required-message="Please select a category">
                                            <option value="" disabled selected>Category</option>
                                            @foreach ($incomeCategories as $category)
                                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td data-label="@lang('Amount')">
                                        <input type="number" name="income_items[0][amount]" class="form-control amount"
                                            placeholder="Amount"
                                            step="1" min="1" required
                                            data-parsley-required-message="Please enter an amount"
                                            data-parsley-min="1" data-parsley-min-message="Amount must be greater or equal to 1">
                                        <span class="text-danger amount_error"></span>
                                    </td>
                                    <td data-label="@lang('Description')">
                                        <input type="text" name="income_items[0][description]" class="form-control"
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
                        <a href="{{ route('incomes.index') }}" class="btn btn-warning">@lang('Cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Your existing script remains the same

        // Add this to handle mobile select placeholder
        if (window.innerWidth <= 767.98) {
            $('.mobile-select option[value=""]').text('Category');
        }
    });

    $(document).on('click', '.add-row', function() {
        let newRow = $('#incomeItemsTable tbody tr:first').clone();

        // Clear values but maintain placeholders
        newRow.find('input[type="number"]').attr('placeholder', 'Amount').val('');
        newRow.find('input[type="text"]').attr('placeholder', 'Description').val('');
        newRow.find('select option[value=""]').text('Category');
        newRow.find('select').val('');

        // Rest of your existing add-row code remains the same
        newRow.find('.parsley-error').removeClass('parsley-error');
        newRow.find('.is-invalid').removeClass('is-invalid');
        newRow.find('.parsley-errors-list').remove();
        newRow.find('[aria-invalid]').removeAttr('aria-invalid');
        newRow.find('[aria-describedby]').removeAttr('aria-describedby');
        newRow.find('[data-parsley-id]').removeAttr('data-parsley-id');

        let rowCount = $('#incomeItemsTable tbody tr').length;
        newRow.find('select, input').each(function() {
            let name = $(this).attr('name');
            if (name) {
                name = name.replace(/\[\d+\]/, `[${rowCount}]`);
                $(this).attr('name', name);
            }
        });

        $('#incomeItemsTable tbody').append(newRow);
    });

    $(document).on('click', '.remove-row', function() {
        // Ensure at least one row remains in the table
        if ($('#incomeItemsTable tbody tr').length > 1) {
            let confirmed = confirm('Are you sure you want to remove this row?');
            if (confirmed) {
                $(this).closest('tr').remove();
            }
        } else {
            alert('At least one income item is required.');
        }
    });
    var extraUlClass = "errors-ul";
    // var errorsLiClass = "errors-li"
</script>

@endsection