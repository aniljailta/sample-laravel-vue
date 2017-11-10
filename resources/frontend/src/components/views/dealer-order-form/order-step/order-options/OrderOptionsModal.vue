<template>

    <div>
        <modal v-bind:show="show"
               v-bind:draggable="false"
               v-bind:modal-class="modalClass"
               v-bind:modal-style="modalStyle"
               v-bind:close-modal-method="close">

            <div>
                <div class="panel-heading">
                    <h2 class="panel-title">Order Options</h2>
                </div>
                <div class="panel-body modal-body">
                    <div class="col-xs-12 col-md-6" style="margin-bottom: 0.5em">
                        <label class="control-label">Available Options</label>
                        <div class="option-picker__swatches">
                            <div class="option-picker__swatches__box">
                                <table class="table table-striped table-condensed table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Option</th>
                                        <th class="text-right option-picker__price">Price</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr is="option-picker-item"
                                        v-for="option in orderOptionsList"
                                        v-bind:option="option">
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6">
                        <label class="control-label">Selected Options</label>
                        <div class="option-picker__swatches">
                            <div class="option-picker__swatches__box">
                                <table class="table table-striped table-condensed table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Option</th>
                                        <th class="text-right option-picker__price">Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr is="option-item"
                                        v-for="option in preparedOptions"
                                        v-bind:option="option"
                                    >
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer" style="text-align: center">
                    <button type="button" class="btn btn-default" v-on:click="close">Close</button>
                    <button type="button" class="btn btn-warning" v-on:click="apply">Apply</button>
                </div>
            </div>

        </modal>
    </div>
</template>

<script type="text/babel">
    import {mapGetters, mapActions} from 'vuex'

    import Modal from 'src/components/ui/Modal.vue'
    import OptionPickerItem from 'src/components/views/dealer-order-form/order-step/order-options/OptionPickerItem.vue'
    import OptionItem from 'src/components/views/dealer-order-form/order-step/order-options/OptionItem.vue'

    export default {
        data() {
            return {
                show: false,
                modalClass: 'col-md-8 col-sm-9 col-xs-11',
                modalStyle: {
                    padding: '0',
                    float: 'none'
                },
                preparedOptions: []
            }
        },
        mounted() {
            this.$bus.$on('add-order-option', this.addOption)
            this.$bus.$on('update-order-option', this.updateOption)
            this.$bus.$on('remove-order-option', this.removeOption)
            this.$bus.$on('increase-order-option', this.increaseOption)
            this.$bus.$on('decrease-order-option', this.decreaseOption)
        },
        components: {
            Modal,
            OptionPickerItem,
            OptionItem
        },
        computed: {
            ...mapGetters({
                orderOptionsList: 'dealerOrderForm/options/orderOptionsList',
                orderOptions: 'dealerOrderForm/orderOptions'
            })
        },
        created() {
            this.$on('open-modal', this.openModalMethod)
            this.$on('close-modal', this.closeModalMethod)
        },
        methods: {
            ...mapActions({
                updateOrderOptions: 'dealerOrderForm/updateOrderOptions'
            }),
            openModalMethod() {
                this.preparedOptions = this.orderOptions ? _.cloneDeep(this.orderOptions) : []
                this.show = true
            },
            close() {
                this.show = false
            },
            apply() {
                new Promise(resolve => {
                    this.updateOrderOptions(this.preparedOptions)
                    resolve()
                }).then(() => {
                    this.close()
                })
            },
            addOption(option) {
                option.optionId = option.id
                let index = _.findIndex(this.preparedOptions, (orderOption) => orderOption.optionId === option.optionId)

                if (index !== -1) {
                    this.increaseOption(option)
                } else {
                    let newOption = _.cloneDeep(option)
                    this.preparedOptions.push(newOption)
                }
            },
            removeOption(option) {
                let index = _.findIndex(this.preparedOptions, (orderOption) => orderOption.optionId === option.optionId)

                if (index !== -1) {
                    this.preparedOptions.splice(index, 1)
                }
            },
            updateOption(option, params) {
                _.forEach(this.preparedOptions, function (orderOption) {
                    if (orderOption.optionId === option.optionId) {
                        orderOption.quantity = params.quantity
                        orderOption.total = orderOption.unitPrice * orderOption.quantity
                    }
                })
            },
            increaseOption(option) {
                _.forEach(this.preparedOptions, function (orderOption) {
                    if (orderOption.optionId === option.optionId) {
                        orderOption.quantity = orderOption.quantity + 1
                        orderOption.total = orderOption.unitPrice * orderOption.quantity
                    }
                })
            },
            decreaseOption(option) {
                if (option.quantity >= 1) {
                    _.forEach(this.preparedOptions, function (orderOption) {
                        if (orderOption.optionId === option.optionId) {
                            orderOption.quantity = orderOption.quantity - 1
                            orderOption.total = orderOption.unitPrice * orderOption.quantity
                        }
                    })
                }
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

</style>