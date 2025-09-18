@extends('layouts.layout')
@push('header')
    @if (old('account_name') || isset($invoice->invoiceItems))
        <meta name="clear-invoice-html" content="clean">
    @endif
    <meta name="invoice-total" content="{{ old('total', $invoice->total ?? 0) }}">
    <meta name="invoice-grand-total" content="{{ old('grand_total', $invoice->grand_total ?? 0) }}">
    <meta name="base-url" content="{{ config('app.url') }}">

@endpush

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                  <div class="col-sm-6 d-flex">
                    <h3 class="mr-2"><a href="{{ route('invoices.create') }}" class="btn btn-outline btn-info">+ @lang('Invoice')</a>
                        <span class="pull-right"></span>
                    </h3>
                    <h3 class="mr-2">
                        <a href="{{ route('invoices.index') }}" class="btn btn-outline btn-info"><i class="fas fa-eye"></i> @lang('View All')</a>
                        <span class="pull-right"></span>
                    </h3>
                    <h3>
                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-outline btn-info"><i class="fas fa-print"></i> @lang('Print')</a>

                    </h3>
            </div>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('invoices.index') }}">@lang('Invoice')</a></li>
                    <li class="breadcrumb-item active">@lang('Edit Invoice')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">Edit Invoice ({{ $invoice->user->name ?? "-"}})</h3>
            </div>
            <div class="card-body">
                <form class="form-material form-horizontal bg-custom" action="{{ route('invoices.update', $invoice) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @php
                        $isPaid = $invoice->due == 0 && $invoice->paid == $invoice->grand_total;
                    @endphp
                    <div class="row col-12 m-0 p-0">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="patient">@lang('Select Patient') <b class="ambitious-crimson">*</b></label>
                                <label for="user_id">@lang('Patient')</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user-injured"></i></span>
                                    </div>
                                <!-- Hidden input to store the actual value -->
                                <input type="hidden" name="user_id" id="user_id" value="{{ old('user_id', $invoice->user_id) }}">
                                <select id="patient" class="form-control custom-width-100 select2" disabled>
                                    <option value="">--@lang('Select')--</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" @if($patient->id == old('user_id', $invoice->user_id)) selected @endif>{{ $patient->id.'. '.$patient->name }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                            <div class="form-group">
                                <label for="doctor">@lang('Doctor')</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user-md"></i></span>
                                    </div>
                                    <!-- Hidden input to store the actual value -->
                                    <input type="hidden" name="doctor_id" id="doctor_id" value="{{ old('doctor_id', $invoice->doctor_id) }}">
                                    <select id="doctor" class="form-control select2" disabled>
                                        <option value="">Select Doctor</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}"
                                                {{ old('doctor_id', $invoice->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                                {{ $doctor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                            <div class="form-group">
                                <label>@lang('Invoice Date') <b class="ambitious-crimson">*</b></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                <input type="text" name="invoice_date" id="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" placeholder="@lang('Invoice Date')" value="{{ old('invoice_date', $invoice->invoice_date) }}">
                            </div>
                                @error('invoice_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input type="hidden" id="commission_percentage" name="commission_percentage"
                                        value="{{ old('commission_percentage', $invoice->commission_percentage) }}"
                                        class="form-control" placeholder="@lang('0.0')">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="t1" class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="custom-th-width-20">Category<b class="ambitious-crimson">*</b></th>
                                            
                                            <th scope="col" class="custom-th-width-20">@lang('Procedure (CPT)')</th>
                                            <th scope="col" class="custom-th-width-25">@lang('Description')</th>
                                            <th scope="col">@lang('Quantity')</th>
                                            <th scope="col" class="custom-th-width-15">@lang('Price')</th>
                                            <th scope="col" class="custom-th-width-15">@lang('Sub Total')</th>
                                            <th scope="col" class="custom-white-space">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoice-body">
                                        @foreach ($invoice->invoiceItems as $invoiceItem)
                                            <tr class="">
                                                <td >
                                                    <select name="procedure_category_id[]" class="form-control  @error('procedure_category_id') is-invalid @enderror" id="procedure_category_id">
                                                        <option value="" disabled selected>Select Procedure Category</option>
                                                        @foreach ($procedureCategories as $singleCategory)
                                                            <option {{($singleCategory->id == $invoiceItem->procedure_category_id)?'selected':''}} value="{{ $singleCategory->id }}">
                                                                {{ $singleCategory->title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    @if($invoiceItem->patient_treatment_plan_procedure_id)
                                                        <input type="hidden" name="patient_treatment_plan_procedure_id[]" id="patient_treatment_plan_procedure" value="{{ $invoiceItem->patient_treatment_plan_procedure_id }}">
                                                        <input type="hidden" name="account_name[]" id="account_name" value="{{ $accountHeader->name }}">
                                                        <input type="text" name="title[]" class="form-control"  placeholder="@lang('Procedure Title')" value="{{ $invoiceItem->patienttreatmentplanprocedures->procedure->title }}" readonly>
                                                    @else
                                                    <input type="hidden" name="patient_treatment_plan_procedure_id[]" id="patient_treatment_plan_procedure" value="">
                                                    <input type="hidden" name="account_name[]" id="account_name" value="{{ $accountHeader->name }}">
                                                    <?php if($invoiceItem->procedure_id) { ?>
                                                    <select name="procedure_id[]" class="form-control  @error('procedure_id') is-invalid @enderror" id="procedure_id">
                                                        <option  disabled selected>Select Procedure</option>
                                                       
                                                            <option selected value="{{ $invoiceItem->procedure_id }}">
                                                            {{ $invoiceItem->title }}
                                                            </option>
                                                        
                                                    </select>
                                                    <?php } ?>
                                                    <input type="hidden" name="title[]" class="form-control" value="{{ $invoiceItem->title }}"/>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="text" name="description[]" class="form-control" value="{{ $invoiceItem->description }}">
                                                </td>
                                                <td>
                                                    <input type="number" step=".01" name="quantity[]" class="form-control quantity" value="{{ $invoiceItem->quantity }}" placeholder="@lang('Quantity')" readonly>
                                                </td>
                                                <td>
                                                    <input type="number" step=".01" name="price[]" class="form-control price" value="{{ $invoiceItem->price }}" placeholder="@lang('Price')" readonly>
                                                </td>
                                                <td>
                                                    <input type="number" step=".01" name="sub_total[]" class="form-control sub_total" value="{{ $invoiceItem->total }}" placeholder="@lang('Sub Total')" readonly>
                                                </td>
                                                <td>
                                                    <button style="margin-top:5px;" type="button" class="btn btn-info m-add" style="{{ $isPaid ? 'display:none;' : '' }}"><i class="fas fa-plus"></i></button>
                                                    <button style="margin-top:5px;" type="button" class="btn btn-info m-remove" style="{{ $isPaid ? 'display:none;' : '' }}"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tbody class="">
                                        <tr>
                                            <td colspan="3"></td>
                                            <td colspan="2" class="ambitious-right">@lang('Total')</td>
                                            <td>
                                                <input type="number" step=".01" name="total" class="form-control total" value="{{ old('total', $invoice->total) }}" placeholder="@lang('Total')" readonly>
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td class="text-right">@lang('Discount')</td>
                                            <td class="text-right">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <input type="number" step=".01" name="discount_percentage" value="{{ old('discount_percentage', $invoice->discount_percentage) }}" class="form-control discount_percentage" placeholder="%" {{ $totalPaidAmount > 0 ? 'readonly' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" step=".01" name="total_discount" class="form-control discount" value="{{ old('total_discount', $invoice->total_discount) }}" placeholder="@lang('Total Discount')" {{ $totalPaidAmount > 0 ? 'readonly' : '' }}>
                                            </td>
                                            
                                        </tr>
                                        

                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="ambitious-right">@lang('Grand Total')</td>
                                            <td>
                                                <input type="number" step=".01" name="grand_total" class="form-control grand_total" value="{{ old('grand_total', $invoice->grand_total) }}" placeholder="@lang('Grand Total')" readonly>
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="ambitious-right">@lang('Paid')</td>
                                            <td>
                                                <input type="number" step=".01" name="paid" id="paid" class="form-control paid" value="{{ old('paid', $invoice->paid) }}" placeholder="@lang('Paid')" readonly>
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="ambitious-right">@lang('Due')</td>
                                            <td>
                                                <input type="number" step=".01" name="due" id="due" class="form-control due" value="{{ old('due', $invoice->due) }}" placeholder="@lang('Due')" readonly>
                                            </td>
                                            
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 col-form-label"></label>
                                <div class="col-md-8">
<<<<<<< HEAD
                                    <input type="submit" value="{{ __('Update') }}" class="btn btn-outline btn-info btn-md"/>
                                    <a href="{{ route('invoices.index') }}" class="btn btn-outline btn-warning btn-md">{{ __('Cancel') }}</a>
=======
                                    <input type="submit" value="{{ __('Update') }}" class="btn btn-outline btn-info btn-lg"/>
                                    <a href="{{ route('invoices.index') }}" class="btn btn-outline btn-warning btn-lg">{{ __('Cancel') }}</a>
>>>>>>> 59200bb (Initial commit with expense manager code)
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <?php if($patient->patientDetails->insurance) { ?>

                

                <div class="payment-suggestions mt-4 m-0 col-12 py-3 px-3 bg-success">
                    <h5>{{$patient->name}} has insurance with {{ $patient->patientDetails->insurance->name }} having discount {{ $patient->patientDetails->insurance->discount_percentage }}% and co payment  {{ $patient->patientDetails->insurance->co_percentage }}%</h5>
                    
                    <?php 
                    $insuranceDiscount = round(($invoice->total)*(($patient->patientDetails->insurance->discount_percentage)/(100)));
                    $totalMinDiscount = ($invoice->total-$insuranceDiscount);
                    $cashAmount = round(($invoice->total)*(($patient->patientDetails->insurance->co_percentage)/(100)));
                    $receivableFromCorporateClient = $invoice->total-($totalMinDiscount+$cashAmount);

                     
                    ?>
                    <p><b>Total Amount: </b><span id="payment_suggestion_total_amount">{{ old('total', $invoice->total) }}</span></p>
                    <p><b>Cash {{ $patient->patientDetails->insurance->co_percentage }}%: </b><span id="payment_suggestion_cash">{{$cashAmount}}</span></p>
                    
                    <p><b>Discount {{ $patient->patientDetails->insurance->discount_percentage }}%: </b><span id="payment_suggestion_discount">{{$insuranceDiscount}}</span></p>
                    <p><b>Receivable from Corporate Client: </b><span id="payment_suggestion_corporate_client">{{$receivableFromCorporateClient}}</span></p>
                </div>
                <?php } ?>

                    <form id="invoice-payment-form" style="@if ($totalPaidAmount < $grandTotal) display: block; @else display: none; @endif">
                        <h4 class="mt-4 m-0 col-12 py-3 px-3 bg-custom">Invoice Payment</h4>
                        <input type="hidden" id="invoice_id" name="invoice_id" value="{{ $invoice->id }}">
                        <div class="row m-0 col-12 bg-custom">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="payment_type">@lang('Payment Type')</label>
                                    <select name="payment_type" id="payment_type" class="form-control select2">
                                        <option value="cash">@lang('Cash')</option>
                                        <option value="card">@lang('Card')</option>
                                        <option value="bank">@lang('Bank Transfer')</option>
                                        <option value="insurance">@lang('Insurance')</option>
                                        <!-- Add other payment types as needed -->
                                    </select>
                                </div>
                            </div>
                            <div id="insuranceSection" style="display: none;" class="col-md-3">
                                <div class="form-group">
                                    <label for="payment_insurance_name">@lang('Insurance Name')</label>
                                    <input type="text" id="payment_insurance_name" class="form-control" readonly>
                                    <input type="hidden" name="payment_insurance_id" id="payment_insurance_id">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="paid_amount">@lang('Paid Amount')</label>
                                    <input type="number" step=".01" name="paid_amount" id="paid_amount" class="form-control" placeholder="@lang('Paid Amount')">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comments">@lang('Comments')</label>
                                    <textarea name="comments" id="comments" class="form-control" rows="1" placeholder="@lang('Comments')"></textarea>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-center mt-3">
                                <button type="button" class="btn btn-outline btn-info toLoad-btn-custom" id="save-invoice-payment">@lang('Save Payment')</button>
                                <button class="btn btn-outline btn-info d-none loader-btn-custom" type="button" disabled>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        <span class="visually-hidden ">Loading...</span>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="alert alert-success mt-3" id="success-payment-custom" style="@if ($totalPaidAmount == $grandTotal) display: block; @else display: none; @endif">
                        All payments have been made for this invoice.
                    </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card mx-3">
                        <div class="card-header bg-info">
                            <h3 class="card-title">@lang('Invoice Payments')</h3>
                        </div>
                        <div class="card-body bg-custom">
                        <table class="table table-striped custom-table" id="laravel_datatable">
                            <thead>
                                <tr>
                                    <th>@lang('Invoice Number')</th>
                                    <th>@lang('Amount Paid')</th>
                                    <th>@lang('Payment Mode')</th>
                                    <th>@lang('Insurance')</th>
                                    <th>@lang('Dated')</th>
                                    <th>@lang('Comments')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoicePayments as $invoicePayment)
                                    <tr>
                                        <td>{{ $invoicePayment->invoice_id }}</td>
                                        <td>{{ $invoicePayment->paid_amount }}</td>
                                        <td>{{ $invoicePayment->payment_type }}</td>
                                        <td>{{ isset($invoicePayment->insurance_id) ? $invoicePayment->insurance_id : '-' }}</td>
                                        <td>{{ $invoicePayment->created_at }}</td>
                                        <td>{{ $invoicePayment->comments }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection




@push('footer')
    <script src="{{ asset('assets/js/custom/invoice.js') }}"></script>
    <script>
    var remainingBalanceUrl = "{{ route('invoice.remainingBalance', ['invoice' => ':invoice']) }}";
    </script>
    <script>
        $(document).ready(function() {
            fetchPaidAmount();

            var userInsuranceProviderId = "{{ $invoice->user->patientDetails->insurance->id ?? '' }}";
            var userInsuranceProviderName = "{{ $invoice->user->patientDetails->insurance->name ?? '' }}";
            var insuranceVerified = "{{ $invoice->user->patientDetails->insurance_verified ?? '' }}";


            if (!userInsuranceProviderId || insuranceVerified.toLowerCase() !== 'yes') {
                // Remove the insurance option from the dropdown if the user has no insurance
                $('#payment_type option[value="insurance"]').remove();
            }

            $('#payment_type').change(function() {
                var selectedPaymentType = $(this).val();
                if (selectedPaymentType === 'insurance') {
                    $('#insuranceSection').show();
                    selectUserInsuranceProvider();
                } else {
                    $('#insuranceSection').hide();
                    $('#payment_insurance_name').val('');
                    $('#payment_insurance_id').val('');
                }
            });

            document.getElementById('save-invoice-payment').addEventListener('click', function() {
                const invoiceId = document.getElementById('invoice_id').value;
                const paidAmount = parseFloat(document.getElementById('paid_amount').value);

                $.ajax({
                    url: remainingBalanceUrl.replace(':invoice', invoiceId),
                    method: 'GET',
                    success: function(data) {

                    const remainingBalance = data.remaining_balance;
                    if (paidAmount > remainingBalance) {
                        alert('Paid amount exceeds remaining balance.');
                    } else {
                        saveInvoicePayment();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching remaining balance:', error);
                    }
                });
            });

            function saveInvoicePayment() {
                var invoice_id = "{{ $invoice->id }}";
                var insurance_id = $('#payment_insurance_id').val();
                var paid_amount = $('#paid_amount').val();
                var payment_type = $('#payment_type').val();
                var comments = $('#comments').val();
                var status = $('#status').val();
                var created_by = "{{ auth()->user()->id }}";

                // Hide the original button
                document.querySelector('.toLoad-btn-custom').classList.add('d-none');
                // Show the loader button
                document.querySelector('.loader-btn-custom').classList.remove('d-none');
                document.querySelector('.loader-btn-custom').style.display = 'inline-block';
                document.querySelector('.loader-btn-custom').disabled = true; // Optional: disable the button to prevent multiple clicks

                $.ajax({
                    url: "{{ route('invoice_payments.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        invoice_id: invoice_id,
                        insurance_id: insurance_id,
                        paid_amount: paid_amount,
                        payment_type: payment_type,
                        comments: comments
                    },
                    success: function(response) {
                        var data = response.invoicePayment;
                        var newRow = '<tr>' +
                            '<td>' + data.invoice_id + '</td>' +
                            '<td>' + data.paid_amount + '</td>' +
                            '<td>' + data.payment_type + '</td>' +
                            '<td>' + (data.insurance_id ? data.insurance_id : '-') + '</td>' +
                            '<td>' + data.created_at + '</td>' +
                            '<td>' + data.comments + '</td>' +
                            '</tr>';
                        $('#laravel_datatable tbody').append(newRow);

                        // Clearing fields
                        $('#payment_insurance_name').val('');
                        $('#payment_insurance_id').val('');
                        $('#paid_amount').val('');
                        $('#payment_type').val('');
                        $('#comments').val('');
                        alert('Payment saved successfully.');

                        // Hide loader and enable button again
                        document.querySelector('.loader-btn-custom').classList.add('d-none');
                        document.querySelector('.toLoad-btn-custom').classList.remove('d-none');
                        document.querySelector('.toLoad-btn-custom').disabled = false;

                        fetchPaidAmount();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error saving payment:', error);
                        alert('Error saving payment.');
                        // Hide loader and enable button again
                        document.querySelector('.loader-btn-custom').classList.add('d-none');
                        document.querySelector('.toLoad-btn-custom').classList.remove('d-none');
                        document.querySelector('.toLoad-btn-custom').disabled = false;
                    }
                });
            }
function fetchPaidAmount() {
    const invoiceId = "{{ $invoice->id }}";
    fetch(`{{ route('invoices.fetchPaidAmount', $invoice->id) }}`)
        .then(response => response.json())
        .then(data => {
            const paidAmount = data.paid_amount;
            const dueAmount = data.due_amount;
            $('#paid').val(paidAmount);
            $('#due').val(dueAmount);
            if (dueAmount === 0 || paidAmount > 0) {
                $('.discount_percentage').prop('readonly', true);
                $('.discount').prop('readonly', true);
                $('.vat_percentage').prop('readonly', true);
                $('.vat').prop('readonly', true);
            }
            var paymentForm = document.querySelector("#invoice-payment-form");
            var successPaymentMessage = document.querySelector("#success-payment-custom");
                if (dueAmount === 0 && paidAmount > 0) {
                    if (successPaymentMessage) {
                        successPaymentMessage.style.display = "block";
                    }
                    if (paymentForm) {
                        paymentForm.style.display = "none";
                    }
                }
        })
        .catch(error => {
            console.error('Error fetching paid amount:', error);
        });
}


            function selectUserInsuranceProvider() {
                if (userInsuranceProviderId) {
                    $('#payment_insurance_id').val(userInsuranceProviderId);
                    $('#payment_insurance_name').val(userInsuranceProviderName);
                }
            }

            function getBaseUrl() {
                return $('meta[name="base-url"]').attr('content');
            }

            // Trigger change event on page load to handle insurance section visibility
            $('#payment_type').trigger('change');


                 
    var baseUrl = "{{ asset('') }}";
    console.log('baseUrl : '+baseUrl);
  $("body").on("change", 'select[name="procedure_category_id[]"]', function () {
    var procedureCatId = $(this).val();
    var procedureIdDropdown = $(this)
      .closest("tr")
      .find('select[name="procedure_id[]"]');

    procedureIdDropdown.empty();

    $.ajax({
      url: baseUrl + "getprocedurefromcategory/" + procedureCatId,
      type: "GET",
      success: function (data) {
        $.each(data, function (key, value) {
            procedureIdDropdown.append(
            '<option value="' + value.id + '">' + value.procedure_code + "</option>"
          );
        });
        if (data.length > 0) {
            procedureIdDropdown.val(data[0].id).trigger('change');
        }
      },
    });
  });

    $("body").on("change", 'select[name="procedure_id[]"]', function () {
        console.log('dd');
        var procedureId = $(this).val();
        var descriptionField = $(this).closest("tr").find('input[name="description[]"]');
        var priceField = $(this).closest("tr").find('input[name="price[]"]');
        var titleField = $(this).closest("tr").find('input[name="title[]"]');
        descriptionField.val("bbb");
    
        $.ajax({
            url: baseUrl + "getproceduredescription/" + procedureId,
            type: "GET",
            success: function (data) {
                if (data && data.procedure) {
                    descriptionField.val(data.procedure.description);
                    priceField.val(data.procedure.price).trigger('change');
                    titleField.val(data.procedure.title).trigger('change');
                }
            },
        });
    });

            
        });


   
    </script>



@endpush


