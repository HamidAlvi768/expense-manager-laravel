@component('mail::message')
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .email-header {
        background-color: #b38f40;
        color: white;
        padding: 20px;
        text-align: center;
    }

    .email-body {
        background-color: white;
        color: #333;
        padding: 20px;
    }

    .email-footer {
        background-color: #b38f40;
        color: white;
        text-align: center;
        padding: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid #000;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }

    .button {
        background-color: #8d7132;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
    }

    .button:hover {
        background-color: #fff;
        color: #8d7132;
    }
</style>
<center><img src="{{ asset('assets/images/logo.png') }}" alt="Alshifa Dental Specialists" style="height: 100px;"></center>
<div class="email-header">
    <h1>{{ $recipientType === 'admin' ? 'New Invoice Created' : 'Invoice Generated Successfully' }}</h1>
</div>

<div class="email-body">
    @if ($recipientType === 'admin')
        <p style="font-family: Arial, sans-serif;">A new invoice has been created for Patient Named {{ $userName }} with Doctor {{ $doctorName }}. Invoice Number: {{ $invoiceNumber }}.</p>
    @else
        <p style="font-family: Arial, sans-serif;">Hi {{ $userName }},</p>
        <p style="font-family: Arial, sans-serif;">Your invoice has been generated with Invoice Number: {{ $invoiceNumber }} against the Treatment Plan Number: {{ $treatmentPlanNumber }} formulated by Dr. {{ $doctorName }}. Your Grand Total is: {{ $grandTotal }}, dated as: {{ $invoiceDate }}.</p>
    @endif

    <h3 style="font-family: Arial, sans-serif;">Invoice Details:</h3>
    <table>
        <thead>
            <tr>
                <th>Sr.NO</th>
                <th>Procedure Title</th>
                <th>Procedure Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoiceItems as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item['title'] }}</td>
                    <td style="text-align: right;">{{ $item['price'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="font-family: Arial, sans-serif;"><strong>Total:</strong> {{ $grandTotal }}</p>

    <h3 style="font-family: Arial, sans-serif;">Payment Details:</h3>
    <table>
        <thead>
            <tr>
                <th>Sr.NO</th>
                <th>Paid Amount</th>
                <th>Due Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">1</td>
                <td style="text-align: right;">{{ $paidAmount }}</td>
                <td style="text-align: right;">{{ $dueAmount }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="email-footer">
    <p>Thank you for your business!</p>
</div>
@endcomponent
