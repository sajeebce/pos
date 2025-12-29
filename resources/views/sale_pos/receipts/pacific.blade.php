<!-- PACIFIC ASSOCIATES LTD. Style Invoice Template -->
<style>
    .pacific-invoice {
        font-family: Arial, sans-serif;
        color: #000 !important;
        font-size: 12px;
        max-width: 800px;
        margin: 0 auto;
    }
    .pacific-header {
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
        margin-bottom: 10px;
    }
    .pacific-header-left {
        float: left;
        width: 65%;
    }
    .pacific-header-right {
        float: right;
        width: 35%;
        text-align: right;
    }
    .pacific-company-name {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .pacific-company-details {
        font-size: 10px;
        line-height: 1.4;
    }
    .pacific-bill-title {
        font-size: 24px;
        font-weight: bold;
        border: 2px solid #000;
        padding: 5px 20px;
        display: inline-block;
        margin-bottom: 10px;
    }
    .pacific-bill-info {
        font-size: 12px;
    }
    .pacific-customer-section {
        margin: 15px 0;
        padding: 10px 0;
        border-bottom: 1px solid #000;
    }
    .pacific-customer-name {
        font-weight: bold;
        font-size: 14px;
    }
    .pacific-table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0 5px 0;
    }
    .pacific-table th {
        border: 1px solid #000;
        padding: 8px;
        text-align: center;
        background-color: #f5f5f5;
        font-weight: bold;
    }
    .pacific-table td {
        border: 1px solid #000;
        padding: 8px;
    }
    .pacific-table .text-center {
        text-align: center;
    }
    .pacific-table .text-right {
        text-align: right;
    }
    .pacific-totals {
        width: 100%;
        margin-top: 10px;
    }
    .pacific-totals-row {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 5px;
    }
    .pacific-totals-table {
        width: 40%;
        margin-left: auto;
        margin-right: 0;
        margin-top: 0;
        border-collapse: collapse;
    }
    .pacific-totals-table td {
        padding: 3px 8px;
        border: 1px solid #000;
    }
    .pacific-totals-table .label {
        text-align: left;
        font-weight: bold;
    }
    .pacific-totals-table .value {
        text-align: right;
    }
    .pacific-amount-words {
        margin: 20px 0;
        font-weight: bold;
    }
    .pacific-footer {
        margin-top: 30px;
        display: flex;
        justify-content: space-between;
    }
    .pacific-challan {
        font-size: 12px;
    }
    .pacific-signature {
        text-align: right;
    }
    .pacific-signature-line {
        border-top: 1px solid #000;
        width: 200px;
        margin-left: auto;
        padding-top: 5px;
        text-align: center;
    }
    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }
</style>

<div class="pacific-invoice">
    <!-- Letter Head (if exists, show full width image like classic design) -->
    @if(!empty($receipt_details->letter_head))
        <div style="text-align: center; margin-bottom: 10px;">
            <img style="width: 100%; margin-bottom: 10px;" src="{{$receipt_details->letter_head}}">
        </div>
    @else
        <!-- Header Section (only show if no letter head) -->
        <div class="pacific-header clearfix">
            <div class="pacific-header-left">
                <!-- Logo -->
                @if(!empty($receipt_details->logo))
                    <img src="{{$receipt_details->logo}}" style="max-height: 60px; margin-bottom: 10px;">
                @endif

                <!-- Company Name -->
                <div class="pacific-company-name">
                    @if(!empty($receipt_details->display_name))
                        {{$receipt_details->display_name}}
                    @endif
                </div>

                <!-- Company Details -->
                <div class="pacific-company-details">
                    @if(!empty($receipt_details->address))
                        {!! $receipt_details->address !!}<br>
                    @endif
                    @if(!empty($receipt_details->contact))
                        {!! $receipt_details->contact !!}
                    @endif
                    @if(!empty($receipt_details->website))
                        <br>Website: {{ $receipt_details->website }}
                    @endif
                    @if(!empty($receipt_details->location_custom_fields))
                        <br>{{ $receipt_details->location_custom_fields }}
                    @endif
                </div>
            </div>

            <div class="pacific-header-right">
                <!-- BILL Title -->
                <div class="pacific-bill-title">
                    @if(!empty($receipt_details->invoice_heading))
                        {!! $receipt_details->invoice_heading !!}
                    @else
                        BILL
                    @endif
                </div>

                <!-- Bill Info -->
                <div class="pacific-bill-info">
                    <table style="margin-left: auto;">
                        <tr>
                            <td><b>@if(!empty($receipt_details->invoice_no_prefix)){!! $receipt_details->invoice_no_prefix !!}@else Bill No.: @endif</b></td>
                            <td>{{$receipt_details->invoice_no}}</td>
                        </tr>
                        <tr>
                            <td><b>{{$receipt_details->date_label}}</b></td>
                            <td>{{$receipt_details->invoice_date}}</td>
                        </tr>
                        @if(!empty($receipt_details->due_date_label))
                        <tr>
                            <td><b>{{$receipt_details->due_date_label}}</b></td>
                            <td>{{$receipt_details->due_date ?? ''}}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Invoice Info (only show if letter head exists, since header section has its own) -->
    @if(!empty($receipt_details->letter_head))
    <div style="margin: 10px 0; padding: 10px 0; border-bottom: 1px solid #ccc;" class="clearfix">
        <span style="float: left;">
            <b>@if(!empty($receipt_details->invoice_no_prefix)){!! $receipt_details->invoice_no_prefix !!}@else Bill No.: @endif</b>
            {{$receipt_details->invoice_no}}
        </span>
        <span style="float: right;">
            <b>{{$receipt_details->date_label}}</b> {{$receipt_details->invoice_date}}
            @if(!empty($receipt_details->due_date_label))
                &nbsp; | &nbsp; <b>{{$receipt_details->due_date_label}}</b> {{$receipt_details->due_date ?? ''}}
            @endif
        </span>
    </div>
    @endif

    <!-- Customer Section -->
    <div class="pacific-customer-section">
        @if(!empty($receipt_details->customer_info))
            @php
                // Split customer info - first line is name (bold), rest is address (normal)
                $customer_lines = preg_split('/(<br\s*\/?>|\n)/', $receipt_details->customer_info, 2);
                $customer_name = $customer_lines[0] ?? '';
                $customer_address = $customer_lines[1] ?? '';
            @endphp
            <div><strong>{{ strip_tags($customer_name) }}</strong></div>
            @if(!empty($customer_address))
                <div style="font-weight: normal;">{!! $customer_address !!}</div>
            @endif
        @endif
        @if(!empty($receipt_details->customer_custom_fields))
            <div style="font-weight: normal;">{!! $receipt_details->customer_custom_fields !!}</div>
        @endif
    </div>

    <!-- Products Table -->
    <table class="pacific-table">
        <thead>
            <tr>
                <th style="width: 8%;">@if(!empty($receipt_details->sub_heading_line1)){{$receipt_details->sub_heading_line1}}@else Sl. No.@endif</th>
                <th style="width: 42%;">{{$receipt_details->table_product_label}}</th>
                <th style="width: 15%;">{{$receipt_details->table_qty_label}}</th>
                <th style="width: 15%;">{{$receipt_details->table_unit_price_label}}</th>
                <th style="width: 20%;">{{$receipt_details->table_subtotal_label}}</th>
            </tr>
        </thead>
        <tbody>
            @php $sl = 1; @endphp
            @forelse($receipt_details->lines as $line)
                <tr>
                    <td class="text-center">{{ $sl++ }}</td>
                    <td>
                        <strong>{{$line['name']}}</strong>
                        @if(!empty($line['product_variation']) && $line['product_variation'] != 'DUMMY')
                            {{$line['product_variation']}}
                        @endif
                        @if(!empty($line['variation']) && $line['variation'] != 'DUMMY')
                            {{$line['variation']}}
                        @endif
                        @if(!empty($line['serial_numbers']))
                            ({{$line['serial_numbers']}})
                        @endif
                        @if(!empty($line['sub_sku']))
                            <br><small>{{$line['sub_sku']}}</small>
                        @endif
                        @if(!empty($line['brand']))
                            <br>{{$line['brand']}}
                        @endif
                        @if(!empty($line['serial_number']))
                            <br><small><strong>IMEI:</strong> {{$line['serial_number']}}</small>
                        @endif
                        @if(!empty($line['sell_line_note']))
                            <br><small>{!!$line['sell_line_note']!!}</small>
                        @endif
                    </td>
                    <td class="text-center">{{$line['quantity']}} {{$line['units']}}</td>
                    <td class="text-right">{{$line['unit_price_before_discount']}}</td>
                    <td class="text-right">{{$line['line_total']}}</td>
                </tr>
                @if(!empty($line['modifiers']))
                    @foreach($line['modifiers'] as $modifier)
                        <tr>
                            <td class="text-center">{{ $sl++ }}</td>
                            <td>{{$modifier['name']}} {{$modifier['variation']}}</td>
                            <td class="text-center">{{$modifier['quantity']}} {{$modifier['units']}}</td>
                            <td class="text-right">{{$modifier['unit_price_inc_tax']}}</td>
                            <td class="text-right">{{$modifier['line_total']}}</td>
                        </tr>
                    @endforeach
                @endif
            @empty
                <tr>
                    <td colspan="5" class="text-center">No items</td>
                </tr>
            @endforelse

            <!-- Totals rows inside product table -->
            <!-- Total Row (was Subtotal) -->
            <tr>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td class="text-right" style="border: 1px solid #000;"><strong>Total:</strong></td>
                <td class="text-right" style="border: 1px solid #000;">{{$receipt_details->subtotal}}</td>
            </tr>

            <!-- Discount Row (if exists) -->
            @if(!empty($receipt_details->discount))
            <tr>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td class="text-right" style="border: 1px solid #000;"><strong>{!! $receipt_details->discount_label !!}</strong></td>
                <td class="text-right" style="border: 1px solid #000;">{{$receipt_details->discount}}</td>
            </tr>
            @endif

            <!-- Tax Row (if exists) -->
            @if(!empty($receipt_details->tax))
            <tr>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td class="text-right" style="border: 1px solid #000;"><strong>{!! $receipt_details->tax_label !!}</strong></td>
                <td class="text-right" style="border: 1px solid #000;">{{$receipt_details->tax}}</td>
            </tr>
            @endif

            <!-- Shipping Row (if exists) -->
            @if(!empty($receipt_details->shipping_charges))
            <tr>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td class="text-right" style="border: 1px solid #000;"><strong>{!! $receipt_details->shipping_charges_label !!}</strong></td>
                <td class="text-right" style="border: 1px solid #000;">{{$receipt_details->shipping_charges}}</td>
            </tr>
            @endif

            <!-- Total Payable Row -->
            <tr>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td class="text-right" style="border: 1px solid #000; font-weight: bold; background-color: #f5f5f5;"><strong>Total<br>Payable:</strong></td>
                <td class="text-right" style="border: 1px solid #000; font-weight: bold; background-color: #f5f5f5;">{{$receipt_details->total}}</td>
            </tr>
        </tbody>
    </table>

    <!-- Amount in Words -->
    @if(!empty($receipt_details->total_in_words))
    <div class="pacific-amount-words" style="text-align: center; margin-bottom: 40px;">
        <strong>In-Words:</strong> {{ ucwords($receipt_details->total_in_words) }}
    </div>
    @endif

    <!-- Payment Info - Hidden in Pacific design (data still exists in software) -->
    {{-- Payment details hidden as per design requirement --}}

    <!-- Total Due - Hidden in Pacific design (data still exists in software) -->
    {{-- Due amount hidden as per design requirement --}}

    <!-- Signature Section - All signatures in one row with single line below -->
    <div style="margin-top: 70px;">
        <!-- Signature Labels Row -->
        <table style="width: 100%; text-align: center;">
            <tr>
                <td style="width: 33%; text-align: center;">
                    <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto; padding-top: 5px;">
                        Prepared by
                    </div>
                </td>
                <td style="width: 34%; text-align: center;">
                    <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto; padding-top: 5px;">
                        Marketing Manager
                    </div>
                </td>
                <td style="width: 33%; text-align: center;">
                    <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto; padding-top: 5px;">
                        Authorised Signature
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Challan Info & Additional Notes (if any) -->
    @if(!empty($receipt_details->sub_heading_line2) || !empty($receipt_details->sub_heading_line3) || !empty($receipt_details->additional_notes))
    <div style="margin-top: 20px;">
        @if(!empty($receipt_details->sub_heading_line2))
        <div class="pacific-challan">
            <strong>Challan No.</strong> {{$receipt_details->sub_heading_line2}}
        </div>
        @endif
        @if(!empty($receipt_details->sub_heading_line3))
        <div class="pacific-challan">
            {{$receipt_details->sub_heading_line3}}
        </div>
        @endif
        @if(!empty($receipt_details->additional_notes))
        <div style="margin-top: 10px;">
            {!! nl2br($receipt_details->additional_notes) !!}
        </div>
        @endif
    </div>
    @endif

    <!-- Barcode/QR Code -->
    @if($receipt_details->show_barcode || $receipt_details->show_qr_code)
    <div style="text-align: center; margin-top: 20px;">
        @if($receipt_details->show_barcode)
            <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($receipt_details->invoice_no, 'C128', 2,30,array(39, 48, 54), true)}}">
        @endif

        @if($receipt_details->show_qr_code && !empty($receipt_details->qr_code_text))
            <br>
            <img src="data:image/png;base64,{{DNS2D::getBarcodePNG($receipt_details->qr_code_text, 'QRCODE', 3, 3, [39, 48, 54])}}">
        @endif
    </div>
    @endif

    <!-- Footer Text -->
    @if(!empty($receipt_details->footer_text))
    <div style="text-align: center; margin-top: 20px; border-top: 1px solid #ccc; padding-top: 10px;">
        {!! $receipt_details->footer_text !!}
    </div>
    @endif
</div>
