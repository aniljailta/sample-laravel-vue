<template>

	<div>
		<data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>
		<form v-if="dataIsReady">
			<div class="form-group">
				<div class="row col-xs-12" v-if="curItem.id">
					<div class="col-xs-12 col-md-3">
						<label for="date_created" class="control-label">Date Created</label>
						<div id="date_created">
							{{ filters.moment(curItem.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
						</div>
					</div>
					<div class="col-xs-12 col-md-3">
						<label for="date_updated" class="control-label">Date Updated</label>
						<div id="date_updated">
							{{ filters.moment(curItem.updatedAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
						</div>
					</div>
				</div>

				<!-- common -->
				<div class="row col-xs-12 col-sm-12">
					<div class="col-xs-12 col-md-6">
						<label for="invoice_id" class="control-label">Invoice ID</label>
						<select id="invoice_id"
								name="invoice_id"
								class="form-control"
								v-model="curItem.invoiceId"
								initial="off"
								v-on:change="setPaymentPayee($event.target.value)">
							<option value="null">Select Invoice ID</option>
							<option v-for="invoice in invoices"
									v-bind:value="invoice.id"
									v-bind:selected="curItem.value && curItem.value == invoice.id">
								{{ invoice.id }}
							</option>
						</select>
					</div>

					<div class="col-xs-12 col-md-6">
						<label for="url" class="control-label">Transaction ID</label>
						<input id="url" placeholder="Transaction ID" type="text" class="form-control" v-model="curItem.transactionId">
					</div>
				</div>
					
				<div class="row col-xs-12 col-sm-12">
					<div class="col-xs-12 col-md-6">
						<label for="url" class="control-label">Amount</label>
						<input id="url" placeholder="Amount" type="text" class="form-control" v-model="curItem.amount">
					</div>

					<div class="col-xs-12 col-md-6">
						<label for="payment_status" class="control-label">Payment Status</label>
						<select id="payment_status"
								class="form-control"
								v-model="curItem.status"
								initial="off">
							<option value="null">Select Payment Status</option>
							<option v-for="status in statuses"
									v-bind:value="status.id"
									v-bind:selected="curItem.status && curItem.status == status.id">
								{{ status.title }}
							</option>
						</select>
					</div>
				</div>

				<div class="row col-xs-12 col-sm-12">
					<div class="col-xs-12 col-md-6">
						<label for="payment_method" class="control-label">Payment Method</label>
						<select id="payment_method"
								class="form-control"
								v-model="curItem.paymentMethod"
								initial="off">
							<option value="null">Select Payment Method</option>
							<option v-for="paymentMethod in paymentMethods"
									v-bind:value="paymentMethod.id"
									v-bind:selected="curItem.paymentMethod && curItem.paymentMethod == paymentMethod.id">
								{{ paymentMethod.title }}
							</option>
						</select>
					</div>

					<div class="col-xs-12 col-md-6">
						<div class="col-md-12 no-padding">
							<label class="control-label">Payee</label>
						</div>
						<div class="col-md-4 no-padding">
							<label>
								<input type="radio"
									   v-on:click="selectPayeeType('user')"
									   name="type"
									   :checked="curItem.paymentableType == 'user'"> User
							</label> <br>
							<label>
								<input type="radio"
									   v-on:click="selectPayeeType('rto-company')"
									   name="type"
									   :checked="curItem.paymentableType == 'rto-company'"> RTO company
							</label>
						</div>

						<div class="col-md-8 no-padding" v-if="curItem.paymentableType == 'user'">
							<select class="form-control"
									v-model="curItem.paymentableId"
									initial="off">
								<option value="null">Select User</option>
								<option v-for="user in users"
										v-bind:value="user.id"
										v-bind:selected="curItem.value && curItem.value == user.id">
									{{ user.firstName }} {{ user.lastName }}
								</option>
							</select>
						</div>

						<div class="col-md-8 no-padding" v-if="curItem.paymentableType == 'rto-company'">
							<select class="form-control"
									v-model="curItem.paymentableId"
									initial="off">
								<option value="null">Select RTO company</option>
								<option v-for="rtoCompany in rtoCompanies"
										v-bind:value="rtoCompany.id"
										v-bind:selected="curItem.value && curItem.value == rtoCompany.id">
									{{ rtoCompany.name }} - {{ rtoCompany.email }}
								</option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>

</template>

<script type="text/babel">
	import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'

	import objectToFormData from 'src/helpers/object-to-form-data'
	import convertKeys from 'convert-keys'

	import 'bootstrap-multiselect'
	import 'bootstrap-multiselect/dist/js/bootstrap-multiselect-collapsible-groups.js'
	import '!style!css!less!bootstrap-multiselect/dist/css/bootstrap-multiselect.css'

	import apiPayments from 'src/api/payments'
	import apiInvoices from 'src/api/invoices'
	import apiUsers from 'src/api/users'
	import apiRtoCompanies from 'src/api/rto-companies'

	export default {
		name: 'style-form-item',
		extends: BaseDataItem,
		data() {
			return {
				invoices: {},
				users: {},
				rtoCompanies: {},
				paymentMethods: [
					{ id: 'cash', title: 'Cash' },
					{ id: 'check', title: 'Check' },
					{ id: 'credit_card', title: 'Credit Card' },
					{ id: 'ach', title: 'ACH' },
					{ id: 'wire_transfer', title: 'Wire Transfer' }
				],
				statuses: [
					{ id: 'pending', title: 'Pending' },
					{ id: 'complete', title: 'Complete' },
					{ id: 'cancelled', title: 'Cancelled' }
				],
				curItem: {
					id: null,
					invoiceId: null,
					paymentableId: null,
					paymentableType: null,
					amount: null,
					paymentMethod: null,
					transactionId: null,
					status: null
				}
			}
		},
		computed: {
			id() {
				if (!_.isUndefined(this.item.id)) {
					return this.item.id
				}
				return null
			}
		},
		methods: {
			save({ item, data }) {
				return apiPayments.save({ item, data }).then(response => response.data)
			},
			submit() {
				let self = this
				let item = _.merge({}, {
					invoiceId: this.curItem.invoiceId,
					paymentableId: this.curItem.paymentableId,
					paymentableType: this.curItem.paymentableType,
					amount: this.curItem.amount,
					paymentMethod: this.curItem.paymentMethod,
					transactionId: this.curItem.transactionId,
					status: this.curItem.status
				})

				if (this.curItem.id) item.id = this.curItem.id

				let form = objectToFormData(convertKeys.toSnake(item))

				this.run({text: 'Saving..', type: 'form'})
				return this.save({ item: item, data: form })
					.then(data => {
						self.$emit('data-process-update', {
							running: false,
							success: data
						})
						self.$emit('item-saved')
					})
					.catch(response => {
						self.$emit('data-failed', response)
					})
			},
			initData() {
				if (this.id) {
					apiPayments.get({
						id: this.id
					})
						.then(response => {
							return this.initDependencies().then(() => {
								return response
							})
						})
						.then(response => {
							let item = response.data
							this.curItem = _.cloneDeep(item)
						})
						.then(() => {
							this.$emit('data-ready')
						})
						.catch(response => {
							this.$emit('data-failed', response)
						})
				} else {
					this.initDependencies()
						.then(response => { this.$emit('data-ready') })
						.catch(response => { this.$emit('data-failed', response) })
				}
			},
			initDependencies() {
				const datas = [
					apiInvoices.get({
						query: {
							per_page: 99999
						}
					}),
					apiUsers.get({
						query: {
							per_page: 99999
						}
					}),
					apiRtoCompanies.get({
						query: {
							per_page: 99999
						}
					}),
					apiPayments.get({
						query: {
							per_page: 99999
						}
					})
				]

				return Promise.all(datas)
					.then(response => {
						this.invoices = response[0].data.data
						this.users = response[1].data.data
						this.rtoCompanies = response[2].data.data
						return response
					})
			},
			selectPayeeType(type) {
				this.curItem.paymentableId = null
				this.curItem.paymentableType = type
			},
			setPaymentPayee(payeeId) {
				let result = $.grep(this.invoices, function(invoice) {
					return invoice.id === parseInt(payeeId)
				})
				this.curItem.paymentableId = result.length !== 0 ? result[0].invoiceableId : null
				this.curItem.paymentableType = result.length !== 0 ? result[0].invoiceableType : null
			}
		}
	}
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

</style>