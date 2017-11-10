<template>

    <div class="form">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Estimated Delivery Period</label>
                    <input type="number"
                           class="form-control"
                           :class="{'invalid': $v.settings.estimatedDeliveryPeriod.$error}"
                           :value="settings.estimatedDeliveryPeriod"
                           @input="update('estimatedDeliveryPeriod', $event.target.value)"
                           @blur="$v.settings.estimatedDeliveryPeriod.$touch"
                           placeholder="Estimated Delivery Period">
                    <div v-if="$v.settings.estimatedDeliveryPeriod.$dirty && $v.settings.estimatedDeliveryPeriod.required === false" class="alert alert-danger" role="alert">The first name is required.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Lead time</label>
                    <input type="number"
                           class="form-control"
                           :class="{'invalid': $v.settings.leadTime.$error}"
                           :value="settings.leadTime"
                           @input="update('leadTime', $event.target.value)"
                           @blur="$v.settings.leadTime.$touch"
                           placeholder="Lead time">
                    <div v-if="$v.settings.leadTime.$dirty && $v.settings.leadTime.required === false" class="alert alert-danger" role="alert">The first name is required.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Initial Contact Eligibility</label>
                    <input type="number"
                           class="form-control"
                           :class="{'invalid': $v.settings.initialContactEligibility.$error}"
                           :value="settings.initialContactEligibility"
                           @input="update('initialContactEligibility', $event.target.value)"
                           @blur="$v.settings.initialContactEligibility.$touch"
                           placeholder="Initial Contact Eligibility">
                    <div v-if="$v.settings.initialContactEligibility.$dirty && $v.settings.initialContactEligibility.required === false" class="alert alert-danger" role="alert">The first name is required.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Change Order Fee</label>
                    <div class="btn-group">
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input @change="update('changeOrderFee', filters.currency($event.target.value))"
                                   type="number"
                                   :class="{'invalid': $v.settings.changeOrderFee.$error}" class="form-control"
                                   :value="settings.changeOrderFee | currency"/>
                        </div>
                    </div>
                    <div v-if="$v.settings.changeOrderFee.$dirty && $v.settings.changeOrderFee.required === false" class="alert alert-danger" role="alert">The first name is required.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Include 3D in dealer-order-form</label><br>
                    <div class="btn-group btn-toggle input-buttons btn-group-sm">
                        <label :class="{'active': settings.include3d}" class="btn btn-default">
                            <input @click="update('include3d', true)"
                                   type="radio"
                                   name="include3d"
                                   :checked="settings.include3d"> Yes
                        </label>
                        <label :class="{'active': !settings.include3d}" class="btn btn-default">
                            <input @click="update('include3d', false)"
                                   type="radio"
                                   name="include3d"
                                   :checked="!settings.include3d"> No
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <label>Footnote on order form</label>
                <textarea class="form-control foot-note"
                          placeholder="Use the foot note to specify special notes for an order, including but not limited to NSF fees, cancellation period, cancellation fees, permitting requirements, delivery distance, mileage fees, etc."
                          :value="settings.footnote"
                          @change="update('footnote', $event.target.value)"></textarea>
            </div>
        </div>

        <h5>Inventory Form Options</h5>
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label>Footer Graphic 1</label>
                <footer-graphic class="footer-graphic__container"
                                :id="'inventoryFormFooter1'"
                                :attachment="inventoryFormFooter1"
                                @add-attachment="updateInventoryFormGraphic"
                                @remove-attachemnt="updateInventoryFormGraphic"/>
            </div>
            <div class="col-xs-12 col-sm-3">
                <label>Footer Graphic 2</label>
                <footer-graphic class="footer-graphic__container"
                                :id="'inventoryFormFooter2'"
                                :attachment="inventoryFormFooter2"
                                @add-attachment="updateInventoryFormGraphic"
                                @remove-attachemnt="updateInventoryFormGraphic"/>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    import vuelidateAnyerror from 'src/mixins/vuelidate/anyerror'
    import validation from './validations/order-info'
    import FooterGraphic from './order-info/FooterGraphic.vue'

    export default {
        name: 'settings-order-info',
        mixins: [vuelidateAnyerror],
        validations: validation,
        components: {FooterGraphic},
        props: {
            settings: {
                type: Object,
                default() {
                    return {}
                }
            }
        },
        data() {
            return {
            }
        },
        created() {
            this.$watch('$anyerror', (value) => {
                this.$emit('update:validOrder', !this.$anyerror)
            })
        },
        computed: {
            inventoryFormFooter1() {
                let attachment = {
                    canDelete: true,
                    canUpload: true,
                    categoryId: 'inventory_form_footer_graphic_1',
                    files: []
                }

                if (this.settings.inventoryFormFooter1) {
                    attachment.files = [this.settings.inventoryFormFooter1]
                }

                return attachment
            },
            inventoryFormFooter2() {
                let attachment = {
                    canDelete: true,
                    canUpload: true,
                    categoryId: 'inventory_form_footer_graphic_2',
                    files: []
                }

                if (this.settings.inventoryFormFooter2) {
                    attachment.files = [this.settings.inventoryFormFooter2]
                }

                return attachment
            }
        },
        methods: {
            update(path, val) {
                let settings = _.cloneDeep(this.settings)
                this.$emit('update:settings', _.set(settings, path, val))
                if (this.$v.settings[path]) {
                    this.$v.settings[path].$touch()
                }
            },
            updateInventoryFormGraphic(item) {
                this.update(item.id, item.item)
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .foot-note {
        margin-bottom: 10px;
    }
</style>