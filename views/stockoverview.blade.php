@extends('layout.default')

@section('title', $L('Stock overview'))
@section('activeNav', 'stockoverview')
@section('viewJsName', 'stockoverview')

@section('content')
<h1 class="page-header">{{ $L('Stock overview') }} <span class="text-muted small">{{ $L('#1 products with #2 units in stock', count($currentStock), SumArrayValue($currentStock, 'amount')) }}</span></h1>

<div class="container-fluid">
	<div class="row">
		<p class="btn btn-lg btn-warning no-real-button">{{ $L('#1 products expiring within the next #2 days', $countExpiringNextXDays, $nextXDays) }}</p>
		<p class="btn btn-lg btn-danger no-real-button">{{ $L('#1 products are already expired', $countAlreadyExpired) }}</p>
		<p class="btn btn-lg btn-info no-real-button">{{ $L('#1 products are below defined min. stock amount', count($missingProducts)) }}</p>
	</div>
</div>

<div class="discrete-content-separator-2x"></div>

<div class="table-responsive">
	<table id="stock-overview-table" class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th>{{ $L('Product') }}</th>
				<th>{{ $L('Amount') }}</th>
				<th>{{ $L('Next best before date') }}</th>
			</tr>
		</thead>
		<tbody>
			@foreach($currentStock as $currentStockEntry) 
			<tr id="product-{{ $currentStockEntry->product_id }}-row" class="@if($currentStockEntry->best_before_date < date('Y-m-d', strtotime('-1 days'))) error-bg @elseif($currentStockEntry->best_before_date < date('Y-m-d', strtotime('+5 days'))) warning-bg @elseif (FindObjectInArrayByPropertyValue($missingProducts, 'id', $currentStockEntry->product_id) !== null) info-bg @endif">
				<td class="fit-content">
					<a class="btn btn-danger btn-xs product-consume-button" href="#" title="{{ $L('Consume 1 #1 of #2', FindObjectInArrayByPropertyValue($quantityunits, 'id', FindObjectInArrayByPropertyValue($products, 'id', $currentStockEntry->product_id)->qu_id_stock)->name, FindObjectInArrayByPropertyValue($products, 'id', $currentStockEntry->product_id)->name) }}"
						data-product-id="{{ $currentStockEntry->product_id }}"
						data-product-name="{{ FindObjectInArrayByPropertyValue($products, 'id', $currentStockEntry->product_id)->name }}"
						data-product-qu-name="{{ FindObjectInArrayByPropertyValue($quantityunits, 'id', FindObjectInArrayByPropertyValue($products, 'id', $currentStockEntry->product_id)->qu_id_stock)->name }}">
						<i class="fa fa-cutlery"></i>
					</a>
				</td>
				<td>
					{{ FindObjectInArrayByPropertyValue($products, 'id', $currentStockEntry->product_id)->name }}
				</td>
				<td>
					<span id="product-{{ $currentStockEntry->product_id }}-amount">{{ $currentStockEntry->amount }}</span> {{ FindObjectInArrayByPropertyValue($quantityunits, 'id', FindObjectInArrayByPropertyValue($products, 'id', $currentStockEntry->product_id)->qu_id_stock)->name }}
				</td>
				<td>
					{{ $currentStockEntry->best_before_date }}
					<time class="timeago timeago-contextual" datetime="{{ $currentStockEntry->best_before_date }}"></time>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
@stop
