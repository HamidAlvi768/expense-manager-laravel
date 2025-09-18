@extends('layouts.layout')

@section('content')
<<<<<<< HEAD
<style>
    .errors-ul {
        margin: 2.3rem 0 0;
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

        #parsley-id-25 {
        left: 25%;
        font-size: 0.7rem;
        margin-top: initial;

        
    }

    #parsley-id-23 {
        left: 2%;
        font-size: 0.7rem;
        margin-top: initial;
    }

    }
    
    
    @media (max-width: 767.98px) {
        /* Keep all existing mobile styles unchanged */
        /* Mobile styles for Account and Expense Date fields */
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
            margin-top: initial;
        }
        
        #parsley-id-25 {
            left: 50%;
            font-size: 0.7rem;
            margin-top: initial;
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

        /* Table mobile styles */
        #expenseItemsTable td::before {
            display: none;
        }

        #expenseItemsTable thead {
            display: none;
        }

        #expenseItemsTable,
        #expenseItemsTable tbody,
        #expenseItemsTable tr {
            display: block;
            background: none;
            border: none;
        }

        #expenseItemsTable tr {
            background: #ebe6e6;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
            padding: 1rem;
            position: relative;
        }

        #expenseItemsTable td {
            display: math;
            border: none;
            padding: 0.5rem;
        }

        /* Adjust select container width */
        #expenseItemsTable td:nth-child(1) {
            width: 50%;
            float: left;
            padding-right: 5px;
            margin-bottom: 10px;
        }

        /* Style select input */
        #expenseItemsTable td:nth-child(1) select.form-control {
            width: 105%;
            padding-right: 25px;
            text-overflow: ellipsis;
            font-size: 0.8rem;
            right: 6%;
            position: relative;
        }

        /* Amount input styling */
        #expenseItemsTable td:nth-child(2) {
            width: 50%;
            float: left;
            padding-left: 5px;
            padding-right: 0;
        }

        #expenseItemsTable td:nth-child(2) input {
            width: 100%;
        }

        /* Description field */
        #expenseItemsTable td:nth-child(3) {
            width: 104%;
            clear: both;
        }

        /* Action buttons */
        #expenseItemsTable td:nth-child(4) {
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
                    <a href="{{ route('expenses.index') }}" class="btn btn-outline btn-info">
                        <i class="fas fa-eye"></i> @lang('View All')
                    </a>
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('expenses.index') }}">@lang('Expenses ')</a>
                    </li>
                    <li class="breadcrumb-item active">@lang('Add Expense ')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">@lang('Add Expense ')</h3>
            </div>
            <div class="card-body">
                <form id="expenseForm" data-parsley-validate class="form-material form-horizontal"
                    action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="account-date-container">
                        <div class="form-group">
                            <label for="account_id">@lang('Account')</label>
                            <select name="account_id" id="account_id" class="form-control select2" required
                                data-parsley-required-message="Please select an account">
                                <option value="" disabled selected>@lang('Select Account')</option>
                                @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->account_title }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="account_id_error"></span>
                        </div>
                        <div class="form-group" style="margin-right: -6.5%;">
                            <label for="expense_date">@lang('Expense Date')</label>
                            <input type="date" id="expense_date" name="expense_date" value="{{ old('expense_date', now()->format('Y-m-d')) }}" class="form-control" required
                                data-parsley-required-message="Please select a date" style="width: 87%;">
                            <span class="text-danger" id="expense_date_error"></span>
                        </div>
                    </div>

                    <hr>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="expenseItemsTable">
                            <thead>
                                <tr>
                                    <th>@lang('Expense Category')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Description')</th>
                                    <th>@lang('Reason')</th> <!-- New Reason Column -->
                                    <th style="text-align: center;">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="@lang('Category')">
                                        <select name="expense_items[0][expense_category_id]" class="form-control mobile-select" required
                                            data-parsley-required-message="Please select a category">
                                            <option value="" disabled selected>Category</option>
                                            @foreach ($expenseCategories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->title }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td data-label="@lang('Amount')">
                                        <input type="number" name="expense_items[0][amount]" class="form-control amount"
                                            placeholder="Amount"
                                            step="1" min="1" required
                                            data-parsley-required-message="Please enter an amount"
                                            data-parsley-min="1" data-parsley-min-message="Amount must be greater or equal to 1">
                                        <span class="text-danger amount_error"></span>
                                    </td>
                                    <td data-label="@lang('Description')">
                                        <input type="text" name="expense_items[0][description]" class="form-control"
                                            placeholder="Description" style=" @media (max-width: 767.98px) { margin-left: 2%;margin-top: -4%; }">
                                    </td>
                                    <td>
                                        <input type="text" name="expense_items[0][reason]" class="form-control reason-input" style="display: none;"> <!-- Reason Input -->
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
                        <a href="{{ route('expenses.index') }}" class="btn btn-warning">@lang('Cancel')</a>
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
        let newRow = $('#expenseItemsTable tbody tr:first').clone();

        // Clear values but maintain placeholders
        newRow.find('input[type="number"]').attr('placeholder', 'Amount').val('');
        newRow.find('input[type="text"]').attr('placeholder', 'Description').val('');
        newRow.find('select option[value=""]').text('Category');
        newRow.find('select').val('');
        newRow.find('input[name$="[reason]"]').val('').hide(); // Hide the reason input


        // Remove validation and error classes
        newRow.find('.parsley-error').removeClass('parsley-error');
        newRow.find('.is-invalid').removeClass('is-invalid');
        newRow.find('.parsley-errors-list').remove();
        newRow.find('[aria-invalid]').removeAttr('aria-invalid');
        newRow.find('[aria-describedby]').removeAttr('aria-describedby');
        newRow.find('[data-parsley-id]').removeAttr('data-parsley-id');

        // Update names for dynamic rows
        let rowCount = $('#expenseItemsTable tbody tr').length;
        newRow.find('select, input').each(function() {
            let name = $(this).attr('name');
            if (name) {
                name = name.replace(/\[\d+\]/, `[${rowCount}]`);
                $(this).attr('name', name);
            }
        });

        // Append new row
        $('#expenseItemsTable tbody').append(newRow);
    });

    $(document).on('click', '.remove-row', function() {
        if ($('#expenseItemsTable tbody tr').length > 1) {
            let confirmed = confirm('Are you sure you want to remove this row?');
            if (confirmed) {
                $(this).closest('tr').remove();
            }
        } else {
            alert('At least one expense item is required.');
        }
    });

    var extraUlClass = "errors-ul";

    $('#expenseForm').on('submit', function(e) {
        let hasError = false;
        let confirmationMessage = '';
        const submitButton = $(this).find('button[type="submit"]'); // Get the submit button

        $('#expenseItemsTable tbody tr').each(function() {
            const categorySelect = $(this).find('select[name^="expense_items"]');
            const amountInput = $(this).find('input[name^="expense_items"][name$="[amount]"]');
            const reasonInput = $(this).find('input[name^="expense_items"][name$="[reason]"]');
            const categoryId = categorySelect.val();
            const amount = parseFloat(amountInput.val());
            const thresholds = @json($thresholds);

            if (categoryId && thresholds[categoryId]) {
                const thresholdAmount = parseFloat(thresholds[categoryId].threshold_amount);
                // Check if the amount is different from the threshold and no reason is provided
                if (amount !== thresholdAmount && !reasonInput.val()) {
                    confirmationMessage += `For category "${categorySelect.find('option:selected').text()}":\n`;
                    confirmationMessage += `Threshold Amount: ${thresholdAmount}\n`;
                    confirmationMessage += `Your Entered Amount: ${amount}\n`;
                    confirmationMessage += `You are getting ${amount > thresholdAmount ? 'above' : 'below'} the set amount threshold.\n`;
                    confirmationMessage += `Please provide a reason for this change:\n`;
                    hasError = true;

                    // Enable the reason input for this row
                    reasonInput.show();
                }
            }
        });

        if (hasError) {
            // Show the confirmation message
            alert(confirmationMessage);
            setTimeout(function() {
                submitButton.prop('disabled', false); // Re-enable the submit button
            }, 2000); // Delay of 2 seconds (2000 milliseconds)

        } else {
            // If no errors, allow form submission
            return true;
        }

        // Prevent form submission if there are errors
        e.preventDefault();
    });
</script>
=======
    <style>
        .errors-ul {
            margin: 2.3rem 0 0;
        }

        .table>tbody>tr>td:last-child,
        .table>thead>tr>th:last-child {
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

            #parsley-id-25 {
                left: 25%;
                font-size: 0.7rem;
                margin-top: initial;


            }

            #parsley-id-23 {
                left: 2%;
                font-size: 0.7rem;
                margin-top: initial;
            }

        }


        @media (max-width: 767.98px) {

            /* Keep all existing mobile styles unchanged */
            /* Mobile styles for Account and Expense Date fields */
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
                margin-top: initial;
            }

            #parsley-id-25 {
                left: 50%;
                font-size: 0.7rem;
                margin-top: initial;
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

            /* Table mobile styles */
            #expenseItemsTable td::before {
                display: none;
            }

            #expenseItemsTable thead {
                display: none;
            }

            #expenseItemsTable,
            #expenseItemsTable tbody,
            #expenseItemsTable tr {
                display: block;
                background: none;
                border: none;
            }

            #expenseItemsTable tr {
                background: #ebe6e6;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                margin-bottom: 1rem;
                padding: 1rem;
                position: relative;
            }

            #expenseItemsTable td {
                display: math;
                border: none;
                padding: 0.5rem;
            }

            /* Adjust select container width */
            #expenseItemsTable td:nth-child(1) {
                width: 50%;
                float: left;
                padding-right: 5px;
                margin-bottom: 10px;
            }

            /* Style select input */
            #expenseItemsTable td:nth-child(1) select.form-control {
                width: 105%;
                padding-right: 25px;
                text-overflow: ellipsis;
                font-size: 0.8rem;
                right: 6%;
                position: relative;
            }

            /* Amount input styling */
            #expenseItemsTable td:nth-child(2) {
                width: 50%;
                float: left;
                padding-left: 5px;
                padding-right: 0;
            }

            #expenseItemsTable td:nth-child(2) input {
                width: 100%;
            }

            /* Description field */
            #expenseItemsTable td:nth-child(3) {
                width: 104%;
                clear: both;
            }

            /* Action buttons */
            #expenseItemsTable td:nth-child(4) {
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
                        <a href="{{ route('expenses.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')
                        </a>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('expenses.index') }}">@lang('Expenses ')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Add Expense ')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Add Expense ')</h3>
                </div>
                <div class="card-body">
                    <form id="expenseForm" data-parsley-validate class="form-material form-horizontal"
                        action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="account-date-container">
                            <div class="form-group">
                                <label for="account_id">@lang('Account')</label>
                                <select name="account_id" id="account_id" class="form-control select2" required
                                    data-parsley-required-message="Please select an account">
                                    <option value="" disabled selected>@lang('Select Account')</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->account_title }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="account_id_error"></span>
                            </div>
                            <div class="form-group" style="margin-right: -6.5%;">
                                <label for="expense_date">@lang('Expense Date')</label>
                                <input type="date" id="expense_date" name="expense_date"
                                    value="{{ old('expense_date', now()->format('Y-m-d')) }}" class="form-control" required
                                    data-parsley-required-message="Please select a date" style="width: 87%;">
                                <span class="text-danger" id="expense_date_error"></span>
                            </div>
                        </div>

                        <hr>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="expenseItemsTable">
                                <thead>
                                    <tr>
                                        <th>@lang('Expense Category')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Description')</th>
                                        <th>@lang('Reason')</th> <!-- New Reason Column -->
                                        <th style="text-align: center;">@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td data-label="@lang('Category')">
                                            <select name="expense_items[0][expense_category_id]"
                                                class="form-control mobile-select" required
                                                data-parsley-required-message="Please select a category">
                                                <option value="" disabled selected>Category</option>
                                                @foreach ($expenseCategories as $category)
                                                    <option value="{{ $category->id }}">
                                                        {{ $category->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td data-label="@lang('Amount')">
                                            <input type="number" name="expense_items[0][amount]" class="form-control amount"
                                                placeholder="Amount" step="1" min="1" required
                                                data-parsley-required-message="Please enter an amount" data-parsley-min="1"
                                                data-parsley-min-message="Amount must be greater or equal to 1">
                                            <span class="text-danger amount_error"></span>
                                        </td>
                                        <td data-label="@lang('Description')">
                                            <input type="text" name="expense_items[0][description]" class="form-control"
                                                placeholder="Description"
                                                style=" @media (max-width: 767.98px) { margin-left: 2%;margin-top: -4%; }">
                                        </td>
                                        <td>
                                            <input type="text" name="expense_items[0][reason]"
                                                class="form-control reason-input" style="display: none;">
                                            <!-- Reason Input -->
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
                            <a href="{{ route('expenses.index') }}" class="btn btn-warning">@lang('Cancel')</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Handle mobile select placeholder
            if (window.innerWidth <= 767.98) {
                $('.mobile-select option[value=""]').text('Category');
            }
        });

        $(document).on('click', '.add-row', function () {
            let newRow = $('#expenseItemsTable tbody tr:first').clone();

            // Clear values but maintain placeholders
            newRow.find('input[type="number"]').attr('placeholder', 'Amount').val('');
            newRow.find('input[type="text"]').attr('placeholder', 'Description').val('');
            newRow.find('select option[value=""]').text('Category');
            newRow.find('select').val('');
            newRow.find('input[name$="[reason]"]').val('').hide(); // Hide the reason input


            // Remove validation and error classes
            newRow.find('.parsley-error').removeClass('parsley-error');
            newRow.find('.is-invalid').removeClass('is-invalid');
            newRow.find('.parsley-errors-list').remove();
            newRow.find('[aria-invalid]').removeAttr('aria-invalid');
            newRow.find('[aria-describedby]').removeAttr('aria-describedby');
            newRow.find('[data-parsley-id]').removeAttr('data-parsley-id');

            // Update names for dynamic rows
            let rowCount = $('#expenseItemsTable tbody tr').length;
            newRow.find('select, input').each(function () {
                let name = $(this).attr('name');
                if (name) {
                    name = name.replace(/\[\d+\]/, `[${rowCount}]`);
                    $(this).attr('name', name);
                }
            });

            // Append new row
            $('#expenseItemsTable tbody').append(newRow);
        });

        $(document).on('click', '.remove-row', function () {
            if ($('#expenseItemsTable tbody tr').length > 1) {
                let confirmed = confirm('Are you sure you want to remove this row?');
                if (confirmed) {
                    $(this).closest('tr').remove();
                }
            } else {
                alert('At least one expense item is required.');
            }
        });

        var extraUlClass = "errors-ul";

        $('#expenseForm').on('submit', function (e) {
            let hasError = false;
            let confirmationMessage = '';
            const submitButton = $(this).find('button[type="submit"]'); // Get the submit button

            $('#expenseItemsTable tbody tr').each(function () {
                const categorySelect = $(this).find('select[name^="expense_items"]');
                const amountInput = $(this).find('input[name^="expense_items"][name$="[amount]"]');
                const reasonInput = $(this).find('input[name^="expense_items"][name$="[reason]"]');
                const categoryId = categorySelect.val();
                const amount = parseFloat(amountInput.val());
                const thresholds = @json($thresholds);

                if (categoryId && thresholds[categoryId]) {
                    const thresholdAmount = parseFloat(thresholds[categoryId].threshold_amount);
                    // Check if the amount is different from the threshold and no reason is provided
                    if (amount !== thresholdAmount && !reasonInput.val()) {
                        confirmationMessage += `For category "${categorySelect.find('option:selected').text()}":\n`;
                        confirmationMessage += `Threshold Amount: ${thresholdAmount}\n`;
                        confirmationMessage += `Your Entered Amount: ${amount}\n`;
                        confirmationMessage += `You are getting ${amount > thresholdAmount ? 'above' : 'below'} the set amount threshold.\n`;
                        confirmationMessage += `Please provide a reason for this change:\n`;
                        hasError = true;

                        // Enable the reason input for this row
                        reasonInput.show();
                    }
                }
            });

            if (hasError) {
                // Show the confirmation message
                alert(confirmationMessage);
                setTimeout(function () {
                    submitButton.prop('disabled', false); // Re-enable the submit button
                }, 2000); // Delay of 2 seconds (2000 milliseconds)

            } else {
                // If no errors, allow form submission
                return true;
            }

            // Prevent form submission if there are errors
            e.preventDefault();
        });
    </script>
>>>>>>> 59200bb (Initial commit with expense manager code)
@endsection