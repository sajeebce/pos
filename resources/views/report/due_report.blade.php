@extends('layouts.app')
@section('title', 'Due Collection Report')

@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Due Collection Report</h3>
            </div>
            <div class="box-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('location_id', __('business.location') . ':') !!}
                            {!! Form::select('location_id', $business_locations, null, [
                                'class' => 'form-control select2',
                                'id' => 'location_id',
                                'style' => 'width:100%',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('date_range', __('report.date_range') . ':') !!}
                            {!! Form::text('date_range', null, [
                                'class' => 'form-control',
                                'id' => 'date_range',
                                'readonly',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button id="filterBtn" class="btn btn-primary btn-block">Filter Report</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="due_report_table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Invoice No</th>
                                <th>Sale Date</th>
                                <th>Payment Date</th>
                                <th>Paid Amount</th>
                                <th>Method</th>
                                <th>Reference</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="bg-light">
                                <th colspan="4" class="text-right">Total Paid:</th>
                                <th id="total_paid" class="text-right"></th>
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
    <!-- Include daterangepicker -->
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}" />

    <script>
        $(function() {
            // Default date = today
            let start = moment();
            let end = moment();

            // Initialize daterangepicker
            $('#date_range').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                },
                startDate: start,
                endDate: end,
                opens: 'left',
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

            // Set default value on load
            $('#date_range').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));

            // Initialize DataTable
            let table = $('#due_report_table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'csvHtml5',
                        title: function() {
                            return 'Due Collection Report (' + $('#date_range').val() + ')';
                        },
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: function() {
                            return 'Due Collection Report (' + $('#date_range').val() + ')';
                        },
                        exportOptions: {
                            columns: ':visible'
                        },
                        orientation: 'landscape',
                        pageSize: 'A4'
                    }
                ],
                ajax: {
                    url: '{{ route('report.due_report') }}',
                    data: function(d) {
                        let dateRange = $('#date_range').val();
                        if (dateRange) {
                            let dates = dateRange.split(' - ');
                            d.start_date = $.trim(dates[0]);
                            d.end_date = $.trim(dates[1]);
                        }
                        d.location_id = $('#location_id').val();
                    },
                    dataSrc: function(json) {
                        $('#total_paid').text(json.totalPaid);
                        return json.data;
                    }
                },
                columns: [{
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'invoice_no',
                        name: 'invoice_no'
                    },
                    {
                        data: 'sale_date',
                        name: 'sale_date'
                    },
                    {
                        data: 'payment_date',
                        name: 'payment_date'
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount',
                        className: 'text-right'
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method'
                    },
                    {
                        data: 'payment_ref_no',
                        name: 'payment_ref_no'
                    }
                ],
                order: [
                    [3, 'desc']
                ],
                pageLength: 25,
            });

            // Filter button click
            $('#filterBtn').click(function() {
                table.ajax.reload();
            });
        });
    </script>
@endsection
