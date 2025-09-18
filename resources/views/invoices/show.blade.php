@extends('layouts.layout')

   <style>
    .table td,
    .table th {
        padding: 0.4rem !important;
    }

    h6 {
        font-size: 0.8rem !important;
    }

    th {
        font-size: 16px;
    }

    td {
        font-size: 14px;
    }
    .mt-custom {
        margin-top: 300px !important;
    }
    .ml-custom {
        margin-left: 200px !important;
    }
    h6 {
        font-size: 0.9rem !important;
    }
</style>
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('invoices.index') }}">@lang('Invoice')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Invoice Info')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Invoice Info')</h3>
                    <div class="card-tools">
                        <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-info">@lang('Edit')</a>
                        <button id="doPrint" class="btn btn-default"><i class="fas fa-print"></i> Print</button>
                    </div>
                </div>
                <div id="print-area" class="card-body">
                    <div class="row mt-custom">
                        <div class="col-12">
                            <div class="invoice p-3 mb-3">
                    <div class="row " style="position:relative;">
                        <h4 style="position: absolute; top:30px; left: 45%;">Invoice</h4>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h4 class="pb-3">&nbsp;</h4>
                        </div>
                    </div>
                    <div class="row col-12 m-0 p-0">
                        <div class="col-4">
                            <h6 class="size">&nbsp;</h6>
                        </div>
                        <div class="col-8 text-right">
                            <h6 class="size">@lang('Invoice') no: {{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}<br/>
                            
                               </h6>
                        </div>

                    </div>

                    <div class="row col-12 p-0 m-0">
                        <div class="col-4">
                            <h6 class="size">&nbsp;</h6>
                        </div>
                        <div class="col-8 text-right">
                            <h6 class="size">Patient: {{ $invoice->user->name }}</h6>
                        </div>

                    </div>

                    <div class="row col-12 p-0 m-0">

                        <div class="col-4">
                            <h6 class="size">&nbsp;</h6>
                        </div>
                        <div class="col-8 text-right">
                            <h6 class="size">@lang('Phone'): {{ $invoice->user->phone }}</h6>
                        </div>

                    </div>
                    <div class="row col-12 p-0 m-0">

                        <div class="col-4">
                            <h6 class="size">&nbsp;</h6>
                        </div>
                        <div class="col-8 text-right">
                            <h6 class="size">MRN no: {{ $invoice->user->patientDetails->mrn_number ?? '' }}</h6>
                        </div>

                    </div>
                    <div class="row col-12 p-0 m-0">

                        <div class="col-4">
                            <h6 class="size">&nbsp;</h6>
                        </div>
                        <div class="col-8 text-right">
                            <h6 class="size">
                                @lang('Date'):
                                {{ date($companySettings->date_format ?? 'Y-m-d', strtotime($invoice->invoice_date)) }}
                                <br></h6>
                        </div>

                    </div>

                    <hr class="pb-3">

                    <div class="row print-area ml-custom" style="margin-bottom:60px !important;">
                      <div class="row col-12">
                        <div class="col-12 table-responsive">
                            <table class="table custom-table">
                                <thead>
                                    <tr>
                                        <th scope="col" style="color:black; border:1px solid #17a2b8;" class="col-3">
                                            @lang('#')</th>
                                        <th scope="col" style="color:black; border:1px solid #17a2b8;" class="col-3">
                                            @lang('Procedure')</th>
                                        <th scope="col" style="color:black; border:1px solid #17a2b8;"class="col-5">
                                            @lang('Description')</th>
                                        <th scope="col"style="color:black; border:1px solid #17a2b8;" class="col-1">
                                            @lang('Quantity')</th>
                                        <th scope="col"style="color:black; border:1px solid #17a2b8;" class="col-1">
                                            @lang('Price')</th>
                                        <th scope="col" style="color:black; border:1px solid #17a2b8;"class="col-2">
                                            @lang('Sub Total')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->invoiceItems as $invoiceItem)
                                        <tr>
                                            <td style="border: 1px solid #17a2b8;">
                                            {{ $loop->iteration }}
                                            </td>
                                            @if ($invoiceItem->patient_treatment_plan_procedure_id)
                                                <td style="border: 1px solid #17a2b8;">
                                                    {{ $invoiceItem->patienttreatmentplanprocedures->procedure->title ?? '-' }}
                                                </td>
                                            @else
                                                <td style="border: 1px solid #17a2b8;">{{ $invoiceItem->title }}</td>
                                            @endif
                                            <td style="border: 1px solid #17a2b8;">{{ $invoiceItem->description }}</td>
                                            <td style="border: 1px solid #17a2b8;">{{ $invoiceItem->quantity }}</td>
                                            <td style="border: 1px solid #17a2b8;">{{ number_format($invoiceItem->price) }}</td>
                                            <td style="border: 1px solid #17a2b8;">{{ number_format($invoiceItem->total) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>



                    <div class="row ml-custom" style="margin-bottom:60px !important;">
                    <div class="row col-12">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 offset-6">
                            
                            <div class="table-responsive">
                                <table class="table">
                                    <?php 
                                    if($invoice->user->patientDetails->insurance) {
                                        ?>
                                        <tbody>
                                        <tr>
                                            <th>@lang('Total Amount')</th>
                                            <td>{{ number_format($invoice->total) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Cash</th>
                                            <td>{{ number_format($invoice->paid) }}</td>
                                        </tr>
                                        @if ($invoice->discount_percentage != 0)    
                                        <tr>
                                            <th>@lang('Discount')</th>
                                            <td>{{ number_format($invoice->total_discount) }}</td>
                                        </tr>
                                        @endif
                                        
                                        @if ($invoice->due != 0)
                                        <tr>
                                            <th>Receivable from Corporate Client</th>
                                            <td>{{ number_format($invoice->due) }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                        <?php

                                    } else {
                                        ?>
                                        <tbody>
                                        <tr>
                                            <th>@lang('Total Amount')</th>
                                            <td>{{ number_format($invoice->total) }}</td>
                                        </tr>
                                        @if ($invoice->discount_percentage != 0)    
                                        <tr>
                                            <th>@lang('Discount') ({{ $invoice->discount_percentage }}%)</th>
                                            <td>{{ number_format($invoice->total_discount) }}</td>
                                        </tr>
                                        @endif
                                        
                                        <tr>
                                            <th>@lang('Grand Total')</th>
                                            <td>{{ number_format($invoice->grand_total) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Paid</th>
                                            <td>{{ number_format($invoice->paid) }}</td>
                                        </tr>
                                        @if ($invoice->due != 0)
                                        <tr>
                                            <th>Balance Amount</th>
                                            <td>{{ number_format($invoice->due) }}</td>
                                        </tr>
                                        @endif
                                        
                                    </tbody>
                                        <?php
                                    }
                                    ?>
                                    
                                </table>
                            </div>
                        </div>
                    </div>
                    </div>



                    
                    </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
@endsection
