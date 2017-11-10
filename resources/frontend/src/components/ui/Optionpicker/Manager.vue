<template>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-condensed">
            <thead>
            <tr>
                <th>Option</th>
                <th>Price</th>
                <th>Quantity</th>
                <th class="text-right">Total</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="buildingOption in buildingOptions"
                v-show="checkBuildingOptionInOptions(buildingOption.optionId)"
                is="option-item"
                v-bind:validation="validation"
                v-bind:building-model="buildingModel"
                v-bind:building-option="buildingOption"
                v-bind:editable="editable"
                @option-item-update-color="updateOption"
                @option-item-select-color="updateOption"
                :key="buildingOption.optionId">
            </tr>
            <tr v-show="buildingOptions.length == 0">
                <td class="text-center" colspan="4">Select the available options to add them to building.</td>
            </tr>

            <tr v-show="buildingOptions.length > 0 && showTotalOptions">
                <th colspan="3" class="text-right">Total Price of Options:</th>
                <th class="text-right nowrap">{{ totalOptions | money }}</th>
            </tr>
            </tbody>
        </table>
    </div>
    
</template>

<script type="text/babel">
    import OptionItem from './OptionItem.vue'

    export default {
        data() {
            return {
                initBuildingOptions: true
            }
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
            },
            forceConstraints: {
                default: true
            },
            showOnlyOption: {
                default() {
                    return false
                }
            },
            showTotalOptions: {
                default() {
                    return true
                }
            }
        },
        components: {
            OptionItem
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

                    if (buildingOption.option.forceQuantity && this.forceConstraints) {
                        switch (buildingOption.option.forceQuantity) {
                            case 'building_length':
                                extraBuildingOption.minQuantity = _.parseInt(this.buildingModel.length)
                                break
                            case 'wall_area':
                                extraBuildingOption.minQuantity = (_.parseInt(this.buildingModel.length) + _.parseInt(this.buildingModel.width)) * _.parseInt(this.buildingModel.wallHeight)
                                break
                            case 'floor_area':
                                extraBuildingOption.minQuantity = _.parseInt(this.buildingModel.length) * _.parseInt(this.buildingModel.width)
                                break
                        }
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
                if (this.$parent.syncBuildingPackage) return
                if (_.isEmpty(this.buildingPackage)) return

                let count = _.size(this.buildingPackage.options)
                let existedOptions = _.filter(this.buildingPackage.options, (packageOption) => {
                    return _.findIndex(this.buildingOptions, (buildingOption) => {
                        return packageOption.optionId === buildingOption.optionId
                    })
                })

                if (count !== _.size(existedOptions)) {
                    this.$parent.selectBuildingPackage({})
                }
            },
            addOption(option, extraProps = {}) {
                if (option.forceQuantity && this.forceConstraints) {
                    switch (option.forceQuantity) {
                        case 'building_length':
                            extraProps.minQuantity = _.parseInt(this.buildingModel.length)
                            break
                        case 'wall_area':
                            extraProps.minQuantity = (_.parseInt(this.buildingModel.length) + _.parseInt(this.buildingModel.width)) * _.parseInt(this.buildingModel.wallHeight)
                            break
                        case 'floor_area':
                            extraProps.minQuantity = _.parseInt(this.buildingModel.length) * _.parseInt(this.buildingModel.width)
                            break
                    }
                    extraProps.quantity = extraProps.minQuantity
                }
                if (option.defaultColorId && !_.isNull(option.defaultColorId)) {
                    extraProps.defaultColor = _.find(option.allowableColors, {id: _.parseInt(option.defaultColorId)})
                }
                if (_.isUndefined(option.color)) {
                    option.color = _.clone(extraProps.defaultColor)
                }

                this.$parent.addOption(option, extraProps)

                this.$emit('option-manager-check-building-package')
            },
            removeOption(buildingOption) {
                this.$parent.removeOption(buildingOption)

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
                this.$parent.increaseOption(buildingOption)
            },
            decreaseOption(buildingOption) {
                this.$parent.decreaseOption(buildingOption)
            },
            updateOption(buildingOption, params) {
                this.$parent.updateOption(buildingOption, params)
            },
            getOptionByOptionId(optionId) {
                let option = _.find(this.options, { id: parseInt(optionId) })
                return _.cloneDeep(option)
            },
            getBuildingOptionByOptionId(optionId) {
                let buildingOption = _.find(this.buildingOptions, { optionId: parseInt(optionId) })
                return _.cloneDeep(buildingOption)
            },
            checkBuildingOptionInOptions(optionId) {
                return _.find(this.options, { id: parseInt(optionId) })
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
<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .nowrap {
        white-space: nowrap;
    }
</style>