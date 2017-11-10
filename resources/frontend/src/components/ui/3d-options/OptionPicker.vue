<template>
    <div class="option-picker model-3d">
        <div class="col-md-12" v-show="buildingItems.length > 0">
            <h4 style="padding-left:15px;">Total Price of Options: <span style="color: green;">{{ totalOptions | money}}</span></h4>
        </div>
        <div class="col-md-12">
            <option-item-3d v-for="buildingItem in buildingItems"
                        v-if="buildingItem['3dModel']"
                        :key="buildingItem.optionId"
                        :building-item="buildingItem"/>

            <option-item-2d v-for="buildingItem in buildingItems"
                        v-if="!buildingItem['3dModel']"
                        :key="buildingItem.optionId"
                        :validation="validation"
                        :building-model="buildingModel"
                        :building-item="buildingItem"
                        :building-options="buildingOptions"
                        :cart-item="cartItems"
                        :editable="editable"
                        @option-item-update-color="updateOption"
                        @option-item-select-color="updateOption"/>
        </div> 
        <div class="col-md-12" v-show="buildingItems.length == 0">
            <td class="text-center" colspan="4">Select the available options to add them to building.</td>
        </div>
    </div>
    
</template>

<script type="text/babel">
    import OptionItem3d from './OptionItem3d.vue'
    import OptionItem2d from './OptionItem2d.vue'

    export default {
        data() {
            return {
                initBuildingOptions: true
            }
        },
        components: {
            OptionItem2d,
            OptionItem3d
        },
        props: {
            total: {
                default: 0
            },
            options: {
                default() {
                    return []
                }
            },
            buildingOptions: {
                default() {
                    return []
                }
            },
            buildingItem: {
                default() { return {} }
            },
            buildingItems: {
                default() {
                    return []
                }
            },
            cartItems: {
                default() {
                    return []
                }
            },
            buildingModel: {
                default() { return {} }
            },
            buildingPackage: {
                default() { return {} }
            },
            validation: {
                default() { return {} }
            },
            editable: {
                default: false
            }
        },
        // v1 compiled
        mounted() {
            this.$on('option-manager-add-option', this.addOption)
            this.$on('option-manager-update-option', this.updateOption)
            this.$on('option-manager-remove-option', this.removeOption)
            this.$on('option-manager-increase-option', this.increaseOption)
            this.$on('option-manager-increase-children-options', this.increaseChildrenOptions)
            this.$on('option-manager-decrease-option', this.decreaseOption)
            this.$on('option-manager-decrease-children-options', this.decreaseChildrenOptions)
            this.$on('option-manager-initial-building-options', this.initialBuildingOptions)
            this.$on('option-manager-initial-children-options', this.initialChildrenOptions)
            if (this.initBuildingOptions) {
                this.$emit('option-manager-initial-building-options')
                /*
                let unwatchBuildingOptions = this.$watch('buildingOptions', () => {
                    this.$emit('option-manager-initial-building-options')
                    unwatchBuildingOptions()
                })
                */
            }
            this.$on('option-manager-check-building-package', this.checkBuildingPackage)
        },
        watch: {
            totalOptions(newValue) {
                this.$emit('update:total', newValue)
            }
        },
        computed: {
            totalOptions () {
                if (this.buildingOptions.length === 0) {
                    return 0
                }
                return _.reduce(this.buildingOptions, function (memo, option) {
                    return memo + (option.unitPrice * option.quantity)
                }, 0, this)
            },
            requiredOptions() {
                let requiredOptionMap = {}

                _.each(this.buildingOptions, function(buildingOption) {
                    if (buildingOption.parentOptions) {
                        _.each(buildingOption.parentOptions, function(parentOptionID) {
                            requiredOptionMap[parentOptionID] = requiredOptionMap[parentOptionID] || []
                            requiredOptionMap[parentOptionID].push(buildingOption.optionId)
                        })
                    }
                })

                return requiredOptionMap
            }
        },
        methods: {
            initialBuildingOptions() {
                _.each(this.buildingOptions, (buildingOption) => {
                    let extraBuildingOption = {}
                    extraBuildingOption.parentOptions = []

                    if (buildingOption.option.forceQuantity &&
                        buildingOption.option.forceQuantity === 'building_length') {
                        extraBuildingOption.minQuantity = this.buildingModel.length
                    }

                    this.$emit('option-manager-update-option', buildingOption, extraBuildingOption)
                })

                _.each(this.buildingOptions, (buildingOption) => {
                    if (!_.isUndefined(buildingOption.color) && !_.isNull(buildingOption.color) && buildingOption.color['optionId']) {
                        let colorBuildingOption = this.getBuildingOptionByOptionId(buildingOption.color['optionId'])
                        this.$emit('option-manager-initial-children-options', buildingOption, [colorBuildingOption])
                    }
                })
            },
            getOptions() {
                let options = []
                _.each(this.buildingOptions, function(item) {
                    let option = _.pick(item, ['optionId', 'quantity', 'unitPrice', 'totalPrice'])
                    if (item.color) {
                        option.color = _.pick(item.color, ['id', 'type', 'name'])
                    }
                    options.push(option)
                })
                return options
            },
            checkBuildingPackage() {
                if (this.$parent.$parent.syncBuildingPackage) return
                if (_.isEmpty(this.buildingPackage)) return

                let count = _.size(this.buildingPackage.options)
                let existedOptions = _.filter(this.buildingPackage.options, (packageOption) => {
                    return _.findIndex(this.buildingOptions, (buildingOption) => {
                        return packageOption.optionId === buildingOption.optionId
                    })
                })

                if (count !== _.size(existedOptions)) {
                    this.$parent.$parent.selectBuildingPackage({})
                }
            },
            addOption(option, extraProps = {}) {
                if (option.forceQuantity && option.forceQuantity === 'building_length') {
                    extraProps.minQuantity = this.buildingModel.length
                    extraProps.quantity = this.buildingModel.length
                }
                this.$parent.$parent.addOption(option, extraProps)
                this.$emit('option-manager-check-building-package')
            },
            removeOption(buildingOption) {
                this.$parent.$parent.removeOption(buildingOption)
                if (_.isArray(this.requiredOptions[buildingOption.optionId])) {
                    let childrenBuildingOptions = _.filter(this.buildingOptions, (bo) => {
                        return (this.requiredOptions[buildingOption.optionId].indexOf(bo.optionId) !== -1)
                    })

                    if (childrenBuildingOptions) {
                        this.$emit('option-manager-decrease-children-options', buildingOption, childrenBuildingOptions)
                    }
                }
                this.$emit('option-manager-check-building-package')
            },
            increaseOption(buildingOption) {
                this.$parent.$parent.increaseOption(buildingOption)
            },
            decreaseOption(buildingOption) {
                this.$parent.$parent.decreaseOption(buildingOption)
            },
            updateOption(buildingOption, params) {
                this.$parent.$parent.updateOption(buildingOption, params)
            },
            getOptionByOptionId(optionId) {
                let option = _.find(this.options, { id: parseInt(optionId) })
                return _.cloneDeep(option)
            },
            getBuildingOptionByOptionId(optionId) {
                let buildingOption = _.find(this.buildingOptions, { optionId: parseInt(optionId) })
                return _.cloneDeep(buildingOption)
            },
            initialChildrenOptions(parentBuildingOption, childrenBuildingOptions) {
                _.each(childrenBuildingOptions, (childrenBuildingOption) => {
                    let extraBuildingOption = {}
                    if (childrenBuildingOption) {
                        let parentOptions = _.cloneDeep(childrenBuildingOption.parentOptions)
                        parentOptions.push(parentBuildingOption.optionId)

                        extraBuildingOption.minQuantity = _.size(parentOptions)
                        extraBuildingOption.parentOptions = parentOptions
                        this.$emit('option-manager-update-option', childrenBuildingOption, extraBuildingOption)
                    }
                })
            },
            increaseChildrenOptions(parentBuildingOption, childrenBuildingOptions, childrenOption) {
                _.each(childrenBuildingOptions, (childrenBuildingOption) => {
                    let extraBuildingOption = {}
                    if (childrenBuildingOption) {
                        let parentOptions = _.cloneDeep(childrenBuildingOption.parentOptions)
                        parentOptions.push(parentBuildingOption.optionId)

                        extraBuildingOption.minQuantity = childrenBuildingOption.minQuantity ? childrenBuildingOption.minQuantity + 1 : 1
                        extraBuildingOption.parentOptions = parentOptions
                        this.$emit('option-manager-update-option', childrenBuildingOption, extraBuildingOption)
                        this.$emit('option-manager-increase-option', childrenBuildingOption)
                    } else {
                        if (childrenOption) {
                            extraBuildingOption.minQuantity = 1
                            extraBuildingOption.parentOptions = [parentBuildingOption.optionId]
                            this.$emit('option-manager-add-option', childrenOption, extraBuildingOption)
                        }
                    }
                })
            },
            decreaseChildrenOptions(parentBuildingOption, childrenBuildingOptions) {
                _.each(childrenBuildingOptions, (childrenBuildingOption) => {
                    if (childrenBuildingOption.quantity === 1) {
                        this.removeOption(childrenBuildingOption)
                    } else {
                        let parentOptions = _.cloneDeep(childrenBuildingOption.parentOptions)
                        parentOptions = _.filter(parentOptions, function(val) {
                            return (val !== parseInt(parentBuildingOption.optionId))
                        })

                        this.$emit('option-manager-update-option', childrenBuildingOption, {
                            parentOptions: parentOptions,
                            minQuantity: childrenBuildingOption.minQuantity - 1
                        })
                        this.$emit('option-manager-decrease-option', childrenBuildingOption)
                    }
                })
            },
            validate() {
                let resValidateChildren = true
                for (var i = 0; i < this.$children.length; i++) {
                    if (_.isFunction(this.$children[i].validate)) {
                        this.$children[i].$emit('v-validate')
                        let isValid = this.$children[i].validate()
                        if (!isValid) {
                            resValidateChildren = false
                        }
                    }
                }
                return resValidateChildren
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>