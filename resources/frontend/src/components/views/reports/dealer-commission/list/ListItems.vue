<template>
    <section class="panel-featured page-list-items">
        <header class="panel-heading clearfix">
            <h2 class="panel-title">{{ title }}</h2>
        </header>

        <div class="panel-body overlayable">
            <data-process :process="dataProcess" :with_loader="true"></data-process>

            <div v-show="dataIsReady">
                <div class="row" v-if="dataIsReady">
                    <div class="col-xs-4">
                        <h5>Generate Commissions for Dates Prior to:</h5>
                        <date id="report_date"
                              :format="'MM/DD/YYYY'"
                              :width="'100%'"
                              :value="selectedDate"
                              ref="reportDate"/>
                    </div>
                    <div class="col-xs-7">
                        <h5>
                            Dealers
                            <a @click="selectAllDealers" class="pointer small">
                                <strong>Select all</strong>
                            </a>
                        </h5>
                        <form-search-select :label="'businessName'"
                                            :title="'businessName'"
                                            :datas="dealers"
                                            :value.sync="selectedDealers"/>
                    </div>
                </div>

                <div class="row mt-sm" v-if="dataIsReady">
                    <div class="col-md-6 col-md-offset-4">
                        <div class="btn-group">
                            <button id="generate_preview"
                                    name="generate_preview"
                                    class="btn btn-primary mtb-sm"
                                    @click="startGeneration"
                                    :disabled="!selectedDate || !selectedDealers.length">
                                Generate Preview
                            </button>

                            <template v-if="generationInProgress">
                                <button @click="modify"
                                        :disabled="!availableData"
                                        id="modify"
                                        name="modify"
                                        class="btn btn-default mtb-sm"
                                        :class="{'active': isModifyEnabled}">
                                    Modify
                                </button>
                                <button :disabled="!availableData"
                                        @click="process"
                                        id="process"
                                        name="process"
                                        class="btn btn-success mtb-sm"
                                        :href="exportUrl">
                                    Process
                                </button>
                            </template>
                        </div>
                        <div v-if="generationInProgress" class="btn-group">
                            <button @click="toNextDealer" v-if="nextDealer"
                                    id="skip"
                                    name="skip"
                                    class="btn btn-sm btn-default mtb-sm">
                                Skip <i class="fa fa-arrow-right" aria-hidden="true"></i>
                            </button>
                            <button @click="cancel"
                                    id="cancel"
                                    name="cancel"
                                    class="btn btn-sm btn-danger mtb-sm">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>

                <div v-if="generationInProgress">
                    <div style="margin-top: 0">
                        <h4>Preview</h4>
                        Dealer {{ currentQueueNumber }} of {{ selectedDealers.length }}: <strong> {{ currentDealer.businessName}}</strong>
                    </div>

                    <div v-if="currentDealer && availableData" class="table-responsive list">
                        <list ref="table"
                              :current-list-data="currentListData" @update:current-list-data="updateCurrentListData"
                              :is-modify-enabled="isModifyEnabled"/>
                    </div>

                    <div v-if="currentDealer && !availableData && !dataProcess.error" class="alert alert-warning text-center">
                        <h5>There are no outstanding commissions for this dealer</h5>
                        <button @click="toNextDealer" v-if="nextDealer"
                                class="btn btn-sm btn-default mb-sm"
                                type="button">
                            Next Dealer <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script type="text/babel">
    /*global swal*/
    import List from './table/List.vue'
    import Date from 'src/components/ui/Date.vue'
    import FormSearchSelect from 'src/components/ui/Select.vue'
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import OrderStatusLabel from 'src/components/views/partials/OrderStatusLabel.vue'

    import apiOrders from 'src/api/orders'
    import apiDealers from 'src/api/dealers'
    import apiDealerCommission from 'src/api/dealer-commission'

    import qs from 'qs'
    import snakeCaseObjectKeys from 'src/helpers/snake-case-converter'

    export default {
        extends: BaseDataItem,
        name: 'dealer-commission',
        components: {
            List,
            Date,
            FormSearchSelect,
            OrderStatusLabel
        },
        data() {
            return {
                generationInProgress: false,
                dealers: [],
                selectedDate: moment().format('MM/DD/YYYY'),
                selectedDealers: [],
                currentDealer: null,
                currentListData: null,
                isModifyEnabled: false
            }
        },
        props: ['title'],
        mounted() {
            this.fetchData()
        },
        created() {
            this.$on('update-date', this.updateDate)
        },
        computed: {
            availableData() {
                return (this.currentListData && this.currentListData.length > 0)
            },
            currentIndex() {
                return _.findIndex(this.selectedDealers, {id: this.currentDealer.id})
            },
            // index array started from 0, readable value is +1
            currentQueueNumber() {
                return this.currentIndex + 1
            },
            nextDealer() {
                if (this.selectedDealers[this.currentIndex + 1]) {
                    return this.selectedDealers[this.currentIndex + 1]
                }
                return null
            }
        },
        methods: {
            updateCurrentListData(newList) {
                this.currentListData = newList
            },
            fetchData() {
                const datas = [
                    apiOrders.statuses({}),
                    apiDealers.get({
                        query: {
                            fields: ['id', 'business_name'],
                            per_page: 9999,
                            where: {
                                is_active: 'yes'
                            }
                        }
                    })
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.ordersStatuses = response[0].data.data
                        this.dealers = response[1].data.data
                        this.$emit('data-ready')
                        return response
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            },
            isDisabledForEdit(id) {
                return this.disableForEdit.includes(id)
            },
            selectAllDealers() {
                this.selectedDealers = this.dealers
            },
            updateDate(dateValue) {
                this.selectedDate = dateValue
            },
            startGeneration() {
                this.generationInProgress = true
                this.currentDealer = _.head(this.selectedDealers)
                this.getDataForDealer()
            },
            getQueryParamsOnly(currentQuery) {
                return currentQuery.substring(currentQuery.indexOf('?') + 1)
            },
            skip() {
                if (this.selectedDealers.length === this.currentIndex) {
                    swal({
                        title: 'Completed',
                        text: 'Dealer Commission Report has been completed.',
                        type: 'success',
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Close'
                    })
                } else {
                    this.currentDealer = this.selectedDealers[this.currentIndex + 1]
                    this.getDataForDealer()
                }
            },
            cancel() {
                this.generationInProgress = false
                swal({
                    title: 'Cancelled',
                    text: 'The remaining dealer commissions have not been processed.',
                    type: 'error',
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Close'
                })
            },
            process() {
                console.log(this.exportUrl())
                if (!_.isEmpty(this.currentListData)) {
                    window.open(this.exportUrl(), '_self')
                }
                // this.toNextDealer()
            },
            toNextDealer() {
                this.currentListData = []
                if (this.selectedDealers.length === this.currentIndex) {
                    this.currentDealer = null
                    this.generationInProgress = false
                    swal({
                        title: 'Completed',
                        text: 'Dealer Commission Report has been completed.',
                        type: 'success',
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Close'
                    })
                } else {
                    this.currentDealer = this.selectedDealers[this.currentIndex + 1]
                    this.getDataForDealer()
                }
            },
            modify() {
                this.isModifyEnabled = !this.isModifyEnabled
            },
            getDataForDealer() {
                this.run({text: 'Fetching data..', type: 'form'})

                let query = _.cloneDeep(this.queryParam())
                return apiDealerCommission.get({query}).then(response => {
                    this.currentListData = response.data.data
                    this.$emit('data-ready')
                    return response
                }).catch(response => {
                    this.$emit('data-failed', response)
                })
            },
            queryParam() {
                return {
                    per_page: 99999,
                    include: {
                        'order.building': true,
                        'dealer': true,
                        'order.order_reference': true
                    },
                    where: {
                        'dealer_id': this.currentDealer.id,
                        'order.date_submitted': {
                            lte: moment(this.selectedDate, 'MM/DD/YYYY').format('YYYY-MM-DD')
                        },
                        'status': {
                            inq: ['pending', 'cancelled']
                        }
                    }
                }
            },
            exportUrl() {
                return '/api/dealer-commission/process-export?' + qs.stringify(snakeCaseObjectKeys(this.queryParam()))
            }
        }
    }
</script>
<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .close {
        font-size: 12px;
    }

    .multiselect-block {
        margin-top: 0px !important;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    #report_date {
        width: 130px;
        font-size: 12px;
        padding: 8px 10px
    }

    .main-content {
        background-color: #fdfdfd;
        padding: 5px;
    }

    .btn-action {
        width: 150px;
        margin-top: 21px;
    }

    .btn-action-sm {
        margin-top: 21px;
    }

    .unsortable {
        white-space: nowrap;
        font-weight: 500;
        background: #f6f6f6;
    }
</style>