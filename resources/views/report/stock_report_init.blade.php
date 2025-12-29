@extends('layouts.app')
@section('title', 'Stock Initialization Report')

@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Stock Initialization Report</h3>
            </div>

            <div class="box-body">
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-3">
                        {!! Form::label('location_id', __('business.location') . ':') !!}
                        {!! Form::select('location_id', $business_locations, null, [
                            'class' => 'form-control select2',
                            'id' => 'location_id',
                            'style' => 'width:100%',
                        ]) !!}
                    </div>

                    <div class="col-md-4">
                        {!! Form::label('date_range', __('report.date_range') . ':') !!}
                        {!! Form::text('date_range', null, [
                            'class' => 'form-control',
                            'id' => 'date_range',
                            'readonly',
                        ]) !!}
                    </div>

                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button id="filterBtn" class="btn btn-primary btn-block">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="stock_init_table">
                        <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th class="text-right">New Products</th>
                                <th class="text-right">Total Products</th>
                                <th class="text-right">Opening Stock (Value)</th>
                                <th class="text-right">Closing Stock (Value)</th>
                            </tr>
                        </thead>
                        {{-- <tfoot class="bg-light">
                            <tr>
                                <th class="text-right">Total:</th>
                                <th id="total_new" class="text-right"></th>
                                <th id="total_products" class="text-right"></th>
                                <th id="total_opening" class="text-right"></th>
                                <th id="total_closing" class="text-right"></th>
                            </tr>
                        </tfoot> --}}
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
            let start = moment(),
                end = moment();

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
                    'Last Month': [moment().subtract(1, 'month').startOf('month'),
                        moment().subtract(1, 'month').endOf('month')
                    ]
                }
            }, function(start, end) {
                $('#date_range').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
            });

            $('#date_range').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));

            let table = $('#stock_init_table').DataTable({
                processing: true,
                serverSide: false,
                searching: false,
                ajax: {
                    url: '{{ route('report.stock_init_report') }}',
                    data: function(d) {
                        let dates = $('#date_range').val().split(' - ');
                        d.start_date = $.trim(dates[0]);
                        d.end_date = $.trim(dates[1]);
                        d.location_id = $('#location_id').val();
                    },
                    dataSrc: 'data' // already reversed from backend
                },
                columns: [{
                        data: 'date'
                    },
                    {
                        data: 'new_products',
                        className: 'text-right'
                    },
                    {
                        data: 'total_products',
                        className: 'text-right'
                    },
                    {
                        data: 'opening_stock',
                        className: 'text-right',
                        render: data => parseFloat(data).toFixed(2)
                    },
                    {
                        data: 'closing_stock',
                        className: 'text-right',
                        render: data => parseFloat(data).toFixed(2)
                    },
                ],
                order: [] // disable front-end ordering to keep backend order
            });


            $('#filterBtn').click(() => table.ajax.reload());
        });
    </script>
@endsection
