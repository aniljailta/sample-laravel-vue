<template>

    <div>
        <div class="panel-heading">
            <h2 class="panel-title">Inventory Forms</h2>
        </div>

        <div class="panel-body overlayable">
            <div class="form-container">
                <data-process :process="dataProcess" :with_loader="true"></data-process>

                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <label for="inventory-form__serial-number">Serial Number</label>
                                <serial-typeahead ref="serialTypeahead"
                                                  :serial-number.sync="serialNumber"
                                                  :building-id.sync="buildingId"/>
                                <div v-if="$v.serialNumber.$dirty && $v.serialNumber.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                        <div class="text-center" v-if="serialNumber">
                            <a @click="openDocument('inventory')" target="_blank" class="btn btn-danger btn-lg">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Download
                            </a>
                        </div>

                    </div>

            </div>
        </div>

        <div class="panel-footer" style="text-align: center">
            <button type="button" class="btn btn-default" @click="close">Close</button>
        </div>
    </div>

</template>

<script type="text/babel">
    import baseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import {mapActions, mapGetters} from 'vuex'
    import {required} from 'vuelidate/lib/validators'
    import SerialTypeahead from './InventoryFormSerialTypeahead.vue'

    export default {
        extends: baseDataItem,
        components: {SerialTypeahead},
        data() {
            return {
                buildingId: null,
                serialNumber: null,
                dataProcess: {
                    type: 'form',
                    running: false
                }
            }
        },
        computed: {
            ...mapGetters({
                getUiToolsStateInventoryForm: 'dealerOrderForm/uiTools/getUiToolsStateInventoryForm',
                orderDealerID: 'dealerOrderForm/orderDealerID'
            })
        },
        methods: {
            ...mapActions({
                uiToolsSetStateInventoryForm: 'dealerOrderForm/uiTools/uiToolsSetStateInventoryForm'
            }),
            openDocument(document) {
                if (document === 'inventory' && this.orderDealerID) {
                    let url = '/buildings/' + this.buildingId + '/inventory-form?dealer_id=' + this.orderDealerID
                    window.open(url, '_blank')
                }
            },
            close() {
                this.uiToolsSetStateInventoryForm({'show': false})
            }
        },
        validations: {
            serialNumber: {required}
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>