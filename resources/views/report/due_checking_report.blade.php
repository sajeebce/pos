@extends('layouts.app')
@section('title', 'Due Checking Report')

@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Due Checking Report</h3>
            </div>

            <div class="box-body">

                {{-- Filter Form --}}
                <form method="GET" action="{{ route('report.due_checking_report') }}">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            {!! Form::label('location_id', 'Location:') !!}
                            {!! Form::select('location_id', $business_locations, $location_id ?? null, [
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                            ]) !!}
                        </div>
                        <div class="col-md-4">
                            {!! Form::label('date_range', 'Date Range:') !!}
                            {!! Form::text('date_range', $start_date && $end_date ? $start_date . ' - ' . $end_date : null, [
                                'class' => 'form-control',
                                'id' => 'date_range',
                                'readonly',
                            ]) !!}
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button class="btn btn-primary btn-block">Filter</button>
                        </div>
                    </div>
                </form>

                {{-- Report Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Invoice No</th>
                                <th>Sale Date</th>
                                <th>Paid Amount</th>
                                <th>Due Amount</th>
                                <th>Payment Method</th>
                                <th>Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $t)
                                @php
                                    $paid = $t->payment_lines->sum('amount');
                                    $due = max($t->final_total - $paid, 0);
                                    $first_payment = $t->payment_lines->first();
                                @endphp
                                <tr>
                                    <td>{{ $t->contact?->supplier_business_name ?? '' }}</td>
                                    <td>{{ $t->invoice_no }}</td>
                                    <td>{{ optional($t->transaction_date)->format('Y-m-d') }}</td>
                                    <td>{{ number_format($paid, 2) }}</td>
                                    <td>{{ number_format($due, 2) }}</td>
                                    <td>{{ optional($first_payment)->method ?? '-' }}</td>
                                    <td>{{ optional($first_payment)->payment_ref_no ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-danger">No records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <th colspan="3" class="text-right">Totals:</th>
                                <th>{{ number_format($total_paid, 2) }}</th>
                                <th>{{ number_format($total_due, 2) }}</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}" />

    <script>
        $(function() {
            // Fix NaN by defaulting to today if start_date or end_date is empty
            let start = "{{ $start_date }}" ? moment("{{ $start_date }}") : moment();
            let end = "{{ $end_date }}" ? moment("{{ $end_date }}") : moment();

            $('#date_range').daterangepicker({
                startDate: start,
                endDate: end,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                opens: 'left',
                autoUpdateInput: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, function(start, end) {
                $('#date_range').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
            });
        });
    </script>
@endsection
