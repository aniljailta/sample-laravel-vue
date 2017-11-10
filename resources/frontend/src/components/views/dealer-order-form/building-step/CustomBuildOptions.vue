<template>

    <div>

        <div class="col-xs-12 col-md-6" style="margin-bottom: 0.5em">
            <label class="control-label">Available Options</label>
            <option-picker v-if="buildingDimension"
                           ref="optionPicker"
                           v-bind:building-model="buildingDimension"
                           v-bind:building-options="buildingOptions"
                           v-bind:options="options"
                           v-bind:option-categories="optionCategories">
            </option-picker>
            <div v-else>Select building model to view options list.</div>
        </div>

        <div class="col-xs-12 col-md-6">
            <label class="control-label">Selected Options</label>
            <option-manager ref="optionManager"
                            v-bind:validation="getValidation()"
                            v-bind:options="options"
                            v-bind:building-options="buildingOptions"
                            v-bind:building-model="buildingDimension"
                            v-bind:building-package="buildingPackage"
                            v-bind:editable="false">
            </option-manager>
        </div>

    </div>

</template>

<script type="text/babel">
    import {mapActions, mapGetters} from 'vuex'

    import OptionPicker from './option-picker/Optionpicker.vue'
    import OptionManager from './option-picker/OptionManager.vue'
    // import manageOptionPickerDataMixin from './option-picker/manage-data-mixin-vue.js'
    import manageOptionPickerDataMixin from 'src/components/ui/Optionpicker/manage-data-mixin.js'
    import customBuildOptionsValidation from 'src/validations/dealer-order-form/custom-build-options.js'

    export default {
        mixins: [manageOptionPickerDataMixin],
        data() {
            return {
                buildingOptions: [],
                alias: {
                    buildingOptions: 'buildingOptions'
                }
            }
        },
        components: {
            OptionPicker,
            OptionManager
        },
        methods: {
            getValidation() {
                return customBuildOptionsValidation
            },
            selectBuildingPackage(buildingPackage) {
                this.updateOrderBuilding({'buildingPackage': buildingPackage})
                return
            },
            validate(options) {
                let validOptions = this.$refs.optionManager.validate()
                let validCategories = (this.$refs.optionPicker.currentRequiredCategories.length === 0)
                return (validOptions && validCategories)
            },
            ...mapActions({
                updateOrderBuilding: 'dealerOrderForm/updateOrderBuilding'
            })
        },
        computed: {
            ...mapGetters({
                options: 'dealerOrderForm/options/list',
                buildingPackage: 'dealerOrderForm/orderBuildingPackage',
                buildingDimension: 'dealerOrderForm/orderBuildingDimension',
                customBuildOptions: 'dealerOrderForm/orderCustomBuildOptions',
                optionCategories: 'dealerOrderForm/options/categories'
            }),
            syncBuildingPackage() {
                return this.$parent.$parent.$parent.syncBuildingPackage
            }
        },
        created() {
            this.buildingOptions = _.cloneDeep(this.customBuildOptions)
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

</style>