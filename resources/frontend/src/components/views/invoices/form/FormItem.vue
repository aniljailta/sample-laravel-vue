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
                    <div class="col-xs-12 col-md-4">
                        <label for="url" class="control-label">Amount</label>
                        <input id="url" placeholder="Amount" type="text" class="form-control" v-model="curItem.amount">
                    </div>

                    <div class="col-xs-12 col-md-4">
                        <label for="url" class="control-label">Invoice Number</label>
                        <input id="url" placeholder="Invoice Number" type="text" class="form-control" v-model="curItem.invoiceNumber">
                    </div>

                    <div class="col-xs-12 col-md-4">
                        <label for="status_id" class="control-label">Status</label>
                        <select id="status_id"
                                class="form-control"
                                initial="off"
                                v-model="curItem.status">
                            <option v-for="status in statuses"
                                    v-bind:value="status.id"
                                    v-bind:selected="curItem.status == status.id">
                                {{ status.title }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-12">
                        <div class="col-md-12 no-padding">
                            <label class="control-label">Customer</label>
                        </div>
                        <div class="col-md-4 no-padding">
                            <label>
                                <input type="radio"
                                       v-on:click="selectCustomerType('user')"
                                       name="type"
                                       :checked="curItem.invoiceableType == 'user'"> User
                            </label> <br>
                            <label>
                                <input type="radio"
                                       v-on:click="selectCustomerType('rto-company')"
                                       name="type"
                                       :checked="curItem.invoiceableType == 'rto-company'"> RTO company
                            </label>
                        </div>

                        <div class="col-md-8 no-padding" v-if="curItem.invoiceableType == 'user'">
                            <select class="form-control"
                                    v-model="curItem.invoiceableId"
                                    initial="off">
                                <option value="null">Select User</option>
                                <option v-for="user in users"
                                        v-bind:value="user.id"
                                        v-bind:selected="curItem.value && curItem.value == user.id">
                                    {{ user.firstName }} {{ user.lastName }}
                                </option>
                            </select>
                        </div>

                        <div class="col-md-8 no-padding" v-if="curItem.invoiceableType == 'rto-company'">
                            <select class="form-control"
                                    v-model="curItem.invoiceableId"
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

    import apiInvoices from 'src/api/invoices'
    import apiUsers from 'src/api/users'
    import apiRtoCompanies from 'src/api/rto-companies'

    export default {
        name: 'style-form-item',
        extends: BaseDataItem,
        data() {
            return {
                users: {},
                rtoCompanies: {},
                statuses: [
                    { id: 'open', title: 'Open' },
                    { id: 'closed', title: 'Closed' }
                ],
                curItem: {
                    id: null,
                    saleId: null,
                    invoiceableId: null,
                    invoiceableType: 'user',
                    amount: null,
                    invoiceNumber: '',
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
                return apiInvoices.save({ item, data }).then(response => response.data)
            },
            submit() {
                let self = this
                let item = _.merge({}, {
                    saleId: this.curItem.saleId,
                    invoiceableId: this.curItem.invoiceableId,
                    invoiceableType: this.curItem.invoiceableType,
                    amount: this.curItem.amount,
                    invoiceNumber: this.curItem.invoiceNumber,
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
                    apiInvoices.get({
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
                    apiInvoices.get({
                        query: {
                            per_page: 99999
                        }
                    })
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.users = response[0].data.data
                        this.rtoCompanies = response[1].data.data
                        return response
                    })
            },
            selectCustomerType(type) {
                this.curItem.invoiceableId = null
                this.curItem.invoiceableType = type
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .no-padding {
        padding: 0;
    }
</style>