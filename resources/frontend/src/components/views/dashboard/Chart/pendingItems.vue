<template>
<div class="row push-col-md-2">
	<div class="col-md-6">
		<section class="panel-featured page-list-items">
	        <header class="panel-heading clearfix text-center">
	            <h2 class="panel-title">Items Pending</h2>
	        </header>

	        <div class="panel-body overlayable">
	        	<data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>
	        	<div v-if="dataIsReady" class="table-responsive list">
	                <table class="table table-hover table-bordered table-condensed no-footer">
	                	<thead>
							<tr>
								<th class="text-center unsortable">&nbsp;</th>
								<th class="text-center unsortable">Quantity</th>
								<th class="text-center unsortable">Total Retail</th>
								<th class="text-center unsortable"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-center">Orders to be Processed:</td>
								<td class="text-center">{{ data.needingRewiewOrdersCount }}</td>
								<td class="text-center">{{ data.ordersTotalRetail !== undefined ? filters.money(data.ordersTotalRetail) : '' }}</td>
								<td class="text-center nowrap">
									<a class="pointer btn btn-primary btn-xs" v-on:click="openOrdersModal"><i class="fa fa-file"></i></a>
								</td>
							</tr>
							<tr>
								<td class="text-center">Sales to be Processed:</td>
								<td class="text-center">{{ data.needingRewiewSalesCount }}</td>
								<td class="text-center">{{ data.salesTotalRetail !== undefined ? filters.money(data.salesTotalRetail) : '' }}</td>
								<td class="text-center nowrap">
									<a class="pointer btn btn-primary btn-xs" v-on:click="openSalesModal"><i class="fa fa-file"></i></a>
								</td>
							</tr>
							<tr>
								<td class="text-center">Pending Buildings:</td>
								<td class="text-center">NA</td>
								<td class="text-center">NA</td>
								<td class="text-center nowrap"></td>
							</tr>
							<tr>
								<td class="text-center">Buildings in production:</td>
								<td class="text-center">NA</td>
								<td class="text-center">NA</td>
								<td class="text-center nowrap"></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
	    </section>
	</div>
	<div class="col-md-6">
		<section class="panel-featured page-list-items">
	        <header class="panel-heading clearfix text-center">
	            <h2 class="panel-title">{{ data.date }} (To Date)</h2>
	        </header>

	        <div class="panel-body overlayable">
	        	<data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>
	        	<div v-if="dataIsReady" class="table-responsive list">
	                <table class="table table-hover table-bordered table-condensed no-footer">
	                	<thead>
							<tr>
								<th class="text-center unsortable">&nbsp;</th>
								<th class="text-center unsortable">Quantity</th>
								<th class="text-center unsortable">Total Retail</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-center">Quotes generated:</td>
								<td class="text-center">{{ data.quotesCount }}</td>
								<td class="text-center">{{ data.quotesTotalRetail !== undefined ? filters.money(data.quotesTotalRetail) : '' }}</td>
							</tr>
							<tr>
								<td class="text-center">Invoiced Sales:</td>
								<td class="text-center">{{ data.lastMonthInvoisedSalesCount }}</td>
								<td class="text-center">{{ data.lastMonthSalesTotalRetail !== undefined ? filters.money(data.lastMonthSalesTotalRetail) : '' }}</td>
							</tr>
							<tr>
								<td class="text-center">Buildings Produced:</td>
								<td class="text-center">{{ data.buildingProducedCount }}</td>
								<td class="text-center">{{ data.buildingProducedTotalRetail !== undefined ? filters.money(data.buildingProducedTotalRetail) : '' }}</td>
							</tr>
							<tr>
								<td class="text-center">Buildings Moved:</td>
								<td class="text-center">{{ data.buildingMovedCount }}</td>
								<td class="text-center">{{ data.buildingMovedTotalRetail !== undefined ? filters.money(data.buildingMovedTotalRetail) : '' }}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
	    </section>
	</div>
</div>
</template>

<script type="text/babel">
	import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
	import apiDashboard from 'src/api/dashboard'
	
	export default {
		extends: BaseDataItem,
		name: 'counts-list-page',
		data() {
			return {
                data: {}
            }
		},
		components: {},
		computed: {},
		methods: {
			initData() {
				this.run({text: 'Loading..'})
				apiDashboard.getData().then(response => {
					this.data = _.cloneDeep(response.data)
					this.$emit('data-ready')
				}).catch(response => {
					this.$emit('data-failed', response)
				})
			},
			openOrdersModal() {
				this.$parent.modal = {mode: 'orders'}
			},
			openSalesModal() {
				this.$parent.modal = {mode: 'sales'}
			}
		}
	}
</script>
<style type="text/sass" lang="scss" rel="stylesheet/scss" scoped>
</style>