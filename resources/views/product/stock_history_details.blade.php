@php
	$common_settings = session()->get('business.common_settings');
@endphp
<div class="row">
	<div class="col-md-12">
		<h4>{{$stock_details['variation']}}</h4>
	</div>
	<div class="col-md-4 col-xs-4">
		<strong>@lang('lang_v1.quantities_in')</strong>
		<table class="table table-condensed">
			<tr>
				<th>@lang('report.total_purchase')</th>
				<td>
					<span class="display_currency" data-is_quantity="true">{{$stock_details['total_purchase']}}</span> {{$stock_details['unit']}}
				</td>
			</tr>
			<tr>
				<th>@lang('lang_v1.opening_stock')</th>
				<td>
					<span class="display_currency" data-is_quantity="true">{{$stock_details['total_opening_stock']}}</span> {{$stock_details['unit']}}
				</td>
			</tr>
			<tr>
				<th>@lang('lang_v1.total_sell_return')</th>
				<td>
					<span class="display_currency" data-is_quantity="true">{{$stock_details['total_sell_return']}}</span> {{$stock_details['unit']}}
				</td>
			</tr>
			<tr>
				<th>@lang('lang_v1.stock_transfers') (@lang('lang_v1.in'))</th>
				<td>
					<span class="display_currency" data-is_quantity="true">{{$stock_details['total_purchase_transfer']}}</span> {{$stock_details['unit']}}
				</td>
			</tr>
		</table>
	</div>
	<div class="col-md-4 col-xs-4">
		<strong>@lang('lang_v1.quantities_out')</strong>
		<table class="table table-condensed">
			<tr>
				<th>@lang('lang_v1.total_sold')</th>
				<td>
					<span class="display_currency" data-is_quantity="true">{{$stock_details['total_sold']}}</span> {{$stock_details['unit']}}
				</td>
			</tr>
			<tr>
				<th>@lang('report.total_stock_adjustment')</th>
				<td>
					<span class="display_currency" data-is_quantity="true">{{$stock_details['total_adjusted']}}</span> {{$stock_details['unit']}}
				</td>
			</tr>
			<tr>
				<th>@lang('lang_v1.total_purchase_return')</th>
				<td>
					<span class="display_currency" data-is_quantity="true">{{$stock_details['total_purchase_return']}}</span> {{$stock_details['unit']}}
				</td>
			</tr>

			<tr>
				<th>@lang('lang_v1.stock_transfers') (@lang('lang_v1.out'))</th>
				<td>
					<span class="display_currency" data-is_quantity="true">{{$stock_details['total_sell_transfer']}}</span> {{$stock_details['unit']}}
				</td>
			</tr>
		</table>
	</div>

	<div class="col-md-4 col-xs-4">
		<strong>@lang('lang_v1.totals')</strong>
		<table class="table table-condensed">
			<tr>
				<th>@lang('report.current_stock')</th>
				<td>
					<span class="display_currency" data-is_quantity="true">{{$stock_details['current_stock']}}</span> {{$stock_details['unit']}}
				</td>
			</tr>
		</table>

		{{-- Available IMEI Dropdown --}}
		@if($available_serials_count > 0)
			<div class="form-group">
				<label><i class="fa fa-barcode" style="color: #27ae60;"></i> <span style="color: #27ae60; font-weight: bold;">@lang('lang_v1.imei_available') ({{$available_serials_count}})</span></label>
				<select class="form-control imei_dropdown_select2"
					id="available_imei_dropdown"
					data-variation-id="{{$id}}"
					data-location-id="{{$location_id}}"
					data-status="available"
					data-placeholder="@lang('lang_v1.search_imei')...">
				</select>
			</div>
		@endif

		{{-- Sold IMEI Dropdown --}}
		@if($sold_serials_count > 0)
			<div class="form-group">
				<label><i class="fa fa-barcode" style="color: #e74c3c;"></i> <span style="color: #e74c3c; font-weight: bold;">@lang('lang_v1.imei_sold') ({{$sold_serials_count}})</span></label>
				<select class="form-control imei_dropdown_select2"
					id="sold_imei_dropdown"
					data-variation-id="{{$id}}"
					data-location-id="{{$location_id}}"
					data-status="sold"
					data-placeholder="@lang('lang_v1.search_imei')...">
				</select>
				<div id="sold_imei_details" class="well well-sm" style="display:none; margin-top:10px;">
					<p><strong>@lang('lang_v1.imei_serial_number'):</strong> <code id="sold_imei_number"></code></p>
					<p><strong>@lang('sale.customer_name'):</strong> <span id="sold_imei_customer"></span></p>
					<p><strong>@lang('sale.invoice_no'):</strong> <span id="sold_imei_invoice"></span></p>
					<p><strong>@lang('messages.date'):</strong> <span id="sold_imei_date"></span></p>
				</div>
			</div>
		@endif

		{{-- Returned to Supplier IMEI Dropdown --}}
		@if($returned_to_supplier_count > 0)
			<div class="form-group">
				<label><i class="fa fa-barcode" style="color: #f39c12;"></i> <span style="color: #f39c12; font-weight: bold;">@lang('lang_v1.imei_returned_to_supplier') ({{$returned_to_supplier_count}})</span></label>
				<select class="form-control imei_dropdown_select2"
					id="returned_supplier_imei_dropdown"
					data-variation-id="{{$id}}"
					data-location-id="{{$location_id}}"
					data-status="returned_to_supplier"
					data-placeholder="@lang('lang_v1.search_imei')...">
				</select>
			</div>
		@endif
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<hr>
		<table class="table table-slim" id="stock_history_table">
			<thead>
			<tr>
				<th>@lang('lang_v1.type')</th>
				<th>@lang('lang_v1.quantity_change')</th>
				@if(!empty($common_settings['enable_secondary_unit']))
					<th>@lang('lang_v1.quantity_change') (@lang('lang_v1.secondary_unit'))</th>
				@endif
				<th>@lang('lang_v1.new_quantity')</th>
				@if(!empty($common_settings['enable_secondary_unit']))
					<th>@lang('lang_v1.new_quantity') (@lang('lang_v1.secondary_unit'))</th>
				@endif
				<th>@lang('lang_v1.date')</th>
				<th>@lang('purchase.ref_no')</th>
				<th>@lang('lang_v1.customer_supplier_info')</th>
			</tr>
			</thead>
			<tbody>
			@forelse($stock_history as $history)
				<tr>
					<td>{{$history['type_label']}}</td>
					@if($history['quantity_change'] > 0 )
						<td class="text-success"> +<span class="display_currency" data-is_quantity="true">{{$history['quantity_change']}}</span>
						</td>
					@else
						<td class="text-danger"><span class="display_currency text-danger" data-is_quantity="true">{{$history['quantity_change']}}</span>
						</td>
					@endif

					@if(!empty($common_settings['enable_secondary_unit']))
						@if($history['quantity_change'] > 0 )
							<td class="text-success">
								@if(!empty($history['purchase_secondary_unit_quantity']))
									+<span class="display_currency" data-is_quantity="true">{{$history['purchase_secondary_unit_quantity']}}</span> {{$stock_details['second_unit']}}
								@endif
							</td>
						@else
							<td class="text-danger">
								@if(!empty($history['sell_secondary_unit_quantity']))
									-<span class="display_currency" data-is_quantity="true">{{$history['sell_secondary_unit_quantity']}}</span> {{$stock_details['second_unit']}}
								@endif
							</td>
						@endif
					@endif
					<td>
						<span class="display_currency" data-is_quantity="true">{{$history['stock']}}</span>
					</td>
					@if(!empty($common_settings['enable_secondary_unit']))
						<td>
							@if(!empty($stock_details['second_unit']))
								<span class="display_currency" data-is_quantity="true">{{$history['stock_in_second_unit']}}</span> {{$stock_details['second_unit']}}
							@endif
						</td>
					@endif
					<td>{{@format_datetime($history['date'])}}</td>
					<td>
						{{$history['ref_no']}}

						@if(!empty($history['additional_notes']))
							@if(!empty($history['ref_no']))
							<br>
							@endif
							{{$history['additional_notes']}}

						@endif
					</td>
					<td>
						{{$history['contact_name'] ?? '--'}}
						@if(!empty($history['supplier_business_name']))
						 - {{$history['supplier_business_name']}}
						@endif
					</td>
				</tr>
			@empty
				<tr><td colspan="5" class="text-center">
					@lang('lang_v1.no_stock_history_found')
				</td></tr>
			@endforelse
			</tbody>
		</table>
	</div>
</div>

<script>
$(document).ready(function() {
	// Initialize IMEI dropdowns with Select2 and lazy loading
	$('.imei_dropdown_select2').each(function() {
		var $select = $(this);
		var variationId = $select.data('variation-id');
		var locationId = $select.data('location-id');
		var status = $select.data('status');
		var placeholder = $select.data('placeholder');

		$select.select2({
			placeholder: placeholder,
			allowClear: true,
			ajax: {
				url: '/products/stock-history-serials',
				dataType: 'json',
				delay: 250,
				data: function(params) {
					return {
						variation_id: variationId,
						location_id: locationId,
						status: status,
						search: params.term || '',
						page: params.page || 1
					};
				},
				processResults: function(data, params) {
					params.page = params.page || 1;

					var results = data.data.map(function(item) {
						var text = item.serial_number;
						if (status === 'sold' && item.customer_name) {
							text += ' - ' + item.customer_name;
						}
						return {
							id: item.id,
							text: text,
							serial_number: item.serial_number,
							customer_name: item.customer_name || null,
							invoice_no: item.invoice_no || null,
							sold_date: item.sold_date || null
						};
					});

					return {
						results: results,
						pagination: {
							more: data.has_more
						}
					};
				},
				cache: true
			},
			minimumInputLength: 0,
			templateResult: function(item) {
				if (item.loading) {
					return item.text;
				}

				var $container = $('<div class="select2-result-imei"></div>');
				$container.append('<code>' + item.serial_number + '</code>');

				if (status === 'sold' && item.customer_name) {
					$container.append('<br><small class="text-muted">' + item.customer_name + ' | ' + item.invoice_no + '</small>');
				}

				return $container;
			},
			templateSelection: function(item) {
				return item.serial_number || item.text;
			}
		});

		// Show sold IMEI details when selected
		if (status === 'sold') {
			$select.on('select2:select', function(e) {
				var data = e.params.data;
				$('#sold_imei_number').text(data.serial_number);
				$('#sold_imei_customer').text(data.customer_name || '--');
				$('#sold_imei_invoice').text(data.invoice_no || '--');
				$('#sold_imei_date').text(data.sold_date || '--');
				$('#sold_imei_details').slideDown();
			});

			$select.on('select2:clear', function() {
				$('#sold_imei_details').slideUp();
			});
		}
	});
});
</script>
