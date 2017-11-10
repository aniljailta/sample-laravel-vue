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
    import OptionPicker from './Optionpicker.vue'
    import OptionManager from './OptionManager.vue'
    import manageOptionPickerDataMixin from 'src/components/ui/Optionpicker/manage-data-mixin.js'
    import customBuildOptionsValidation from 'src/validations/customer-order-form/custom-build-options.js'

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
        props: {
            options: {
                default() { return {} }
            },
            buildingPackage: {
                default() { return {} }
            },
            buildingDimension: {
                default() { return {} }
            },
            customBuildOptions: {
                default() { return {} }
            },
            optionCategories: {
                default() { return {} }
            }
        },
        methods: {
            getValidation() {
                return customBuildOptionsValidation
            },
            selectBuildingPackage(buildingPackage) {
                this.$emit('select-building-package', buildingPackage)
                return
            },
            validate() {
                let validOptions = this.$refs.optionManager.validate()
                let validCategories = (this.$refs.optionPicker.currentRequiredCategories.length === 0)
                return (validOptions && validCategories)
            }
        },
        computed: {
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