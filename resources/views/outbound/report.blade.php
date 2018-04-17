<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body, html {
            width: 100%;
            height: 100%;
        }
        .width-half {
            width: 50%;
        }
        .clear-both {
            clear: both;
        }
        .s-text-box, .md-text-box {
            padding: 10px;
            width: 300px;
            border: 1px black solid;
        }
        .s-text-box {
            height: 80px;
        }
        .md-text-box {
            height: 100px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .pull-left {
            float: left;
        }
        .pull-right {
            float: right;
        }
        .pt-1 {
            padding-top: 1em;
        }
        .pt-2 {
            padding-top: 2em;
        }
        .d-inline-block {
            display: inline-block;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .item-table th, td {
            border: 1px black solid;
        }
        .item-table td {
            padding: 5px;
        }
        .item-table th:nth-child(1) {
            width: 10%;
        }
        .item-table th:nth-child(3) {
            width: 18%;
        }
        .item-table th:nth-child(4) {
            width: 20%;
        }
        .barcode {
            padding-top: 25px;
            margin-left: -40px;
        }
        .width-quarter {
            width: 25%;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    @if($outbound->invoice_slip)
        <?php 
            $mime = Storage::mimeType($outbound->invoice_slip);
            $extension = explode("/", $mime);
            $path = $path . str_replace('public/', '/app/public/', $outbound->invoice_slip); 
        ?>
        @if($mime == "image/png" || $mime == "image/jpeg")
            <img src="{{ $path }}" width="500px">
            <div class="page-break"></div>
        @endif
    @endif
    <div class="pull-left width-half">
        <h2>Parcel HUB</h2>
    </div>
    <div class="pull-left width-quarter">
        
        <p>Ref No : <u>{{ $outbound->PREFIX() }}{{ $outbound->id }}</u></p>
        <p>Date : <u>{{ $outbound->created_at->toDateString() }}</u></p>
    </div>

    <div class="pull-left width-quarter barcode">
        {!! DNS1D::getBarcodeHTML( $outbound->PREFIX() . $outbound->id , "C128",2, 44,"black", true) !!}
    </div>

    <div class="clear-both"></div>

    <div class="pull-left width-half">
        <p>Sender</p>
        <div class="md-text-box">
            {{ $outbound->user->name }} <br>
            {{ $outbound->user->address }}, <br>
            @if( !empty( $outbound->user->address_2 ) )
                {{ $outbound->user->address_2 }}, <br>
            @endif
            {{ $outbound->user->postcode }}, {{ $outbound->user->state }}, {{ $outbound->user->country }} <br>
            <b>Tel:</b> {{ $outbound->user->phone }}
        </div>
    </div>
    <div class="pull-right width-half">
        <p>Receiver</p>
        <div class="md-text-box">
            {{ $outbound->recipient_name }} <br>
            {{ $outbound->recipient_address }}, <br>
            @if( !empty( $outbound->recipient_address_2) )
                {{ $outbound->recipient_address_2 }}, <br>
            @endif
            {{ $outbound->recipient_postcode }}, {{ $outbound->recipient_state }}, {{ $outbound->recipient_country }} <br>
            <b>Tel:</b> {{ $outbound->recipient_phone }}
        </div>
    </div>

    <div class="clear-both"></div>

    <div class="pt-2 pull-left width-half">
        <div class="s-text-box">
            Courier Service : <br>{{ $outbound->courier->name }}
        </div>
    </div>
    <div class="pt-2 pull-right width-half">
        <div class="s-text-box">
            <span>Tracking Number : </span>
        </div>
        <div class="d-inline-block width-half pt-1">Actual Weight :</div>
        <div class="d-inline-block width-half pt-1">Chargeable Weight :</div>
    </div>

    <div class="clear-both"></div>

    <div class="pull-left" style="padding-left: 20%">
        <p>Insurance</p>
    </div>
    <div class="pull-right width-half">
        <p>
            <strong>MYR</strong> : {{ $outbound->amount_insured }}
        </p>
    </div>

    <div class="clear-both"></div>

    <table class="item-table">
        <thead>
        <tr>
            <th>No</th>
            <th>Item</th>
            <th class="text-center">Qty<br>(pcs/ ctn / dozen)</th>
            <th>Value (MYR)</th>
            <th>Remarks</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($outbound->products as $key => $product)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $product->sku }} - {{ $product->name }}</td>
                <td class="text-center">{{ $product->pivot->quantity }}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>