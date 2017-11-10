/*global swal*/

const setReactive = (vm, alias, value) => {
    let arr = _.split(alias, '.')
    if (arr.length > 1) {
        let key = _.takeRight(arr, arr.length - 1)
        let obj = _.take(arr, arr.length - 1)
        key = key.join('.')
        obj = obj.join('.')
        vm.$set(vm[obj], key, value)
        return vm[obj][key]
    }

    vm[alias] = value
    return vm[alias]
}

export default {
    /*
     * Required data: this.data.alias
     * Required props: 'this.data.alias'
     */
    data() {
        return {
            optionForceConstraints: false
        }
    },
    methods: {
        addOption(option, extraProps) {
            let buildingOptions = _.get(this, this.alias.buildingOptions)
            var selectedBuildingOption = _.find(buildingOptions, function (el) {
                return el.optionId === option.id
            }, this)

            if (typeof selectedBuildingOption === 'undefined') {
                if (buildingOptions.length >= 24) {
                    swal('Error', 'There is a limit of 24 custom options per building.', 'error')
                    return
                }

                option = _.cloneDeep(option)

                let buildingOption = _.assign({
                    'optionId': option.id
                }, {
                    'option': option,
                    'color': option.color || null,
                    'quantity': 1,
                    'minQuantity': option.minQuantity || null,
                    'parentOptions': option.parentOptions || [],
                    'unitPrice': option.unitPrice,
                    'totalPrice': option.unitPrice * 1,
                    'category': {'name': option.category.name}
                }, extraProps)

                buildingOptions.push(buildingOption)
            } else {
                selectedBuildingOption.quantity = selectedBuildingOption.quantity + 1
                selectedBuildingOption.totalPrice = selectedBuildingOption.unitPrice * selectedBuildingOption.quantity
            }
        },
        removeOption(buildingOption) {
            let buildingOptions = _.get(this, this.alias.buildingOptions)
            if (buildingOptions.length > 0) {
                var options = _.filter(buildingOptions, function (item) {
                    return item.optionId !== buildingOption.optionId
                }, this)
                setReactive(this, this.alias.buildingOptions, options)
            }
        },
        removeBeforeAddingOption(category, canvasoptions, extraProps = {}) {
            this.updateByCategory(category)
            _.each(canvasoptions, (canvaopt) => {
                let result = _.find(this.options, function(option) {
                    if (option['3dModel']) {
                        return _.lowerCase(option['3dModel'].dataId) === _.lowerCase(canvaopt.type)
                    }
                })
                if (result.forceQuantity && result.forceQuantity === 'building_length') {
                    extraProps.minQuantity = this.buildingModel.length
                    extraProps.quantity = this.buildingModel.length
                }
                this.addOption(result, extraProps)
            })
        },
        updateByCategory(category) {
            let buildingOptions = _.get(this, this.alias.buildingOptions)
            var options = _.filter(buildingOptions, function (item) {
                    return item.category.name !== category
                }, this)
            setReactive(this, this.alias.buildingOptions, options)
        },
        increaseOption(buildingOption) {
            let buildingOptions = _.get(this, this.alias.buildingOptions)
            var selectedOption = _.find(buildingOptions, function (el) {
                return el.optionId === buildingOption.optionId
            }, this)

            if (typeof selectedOption !== 'undefined') {
                selectedOption.quantity = selectedOption.quantity + 1
                selectedOption.total = selectedOption.unitPrice * selectedOption.quantity
            }
        },
        decreaseOption(buildingOption) {
            let buildingOptions = _.get(this, this.alias.buildingOptions)

            var selectedOption = _.find(buildingOptions, function (el) {
                return el.optionId === buildingOption.optionId
            }, this)

            if (typeof selectedOption !== 'undefined') {
                if (this.optionForceConstraints) {
                    if (selectedOption.option.constraintType === 'less_than' && selectedOption.minQuantity < selectedOption.quantity) return
                    if (selectedOption.option.constraintType === 'equal_to' && selectedOption.minQuantity === selectedOption.quantity) return
                }

                selectedOption.quantity = selectedOption.quantity - 1
                selectedOption.total = selectedOption.unitPrice * selectedOption.quantity
            }
        },
        updateOption(buildingOption, params) {
            let buildingOptions = _.get(this, this.alias.buildingOptions)
            let buildingOptionIndex = buildingOptions.findIndex(item => item.optionId === buildingOption.optionId)
            if (buildingOptionIndex === -1) return

            let selectedOption = buildingOptions[buildingOptionIndex]
            let newObject = _.extend({}, selectedOption)

            // color
            if (params.color) {
                newObject.color = _.assign({}, selectedOption.color, params.color)
                delete params['color']
            }

            // parent options
            if (params.parentOptions) {
                newObject.parentOptions = params.parentOptions
                delete params['parentOptions']
            }

            newObject = _.merge(newObject, params)
            // force min quantity
            if (newObject.option.constraintType && this.optionForceConstraints) {
                if (newObject.option.constraintType === 'less_than' && newObject.minQuantity < newObject.quantity) {
                    newObject.quantity = newObject.minQuantity
                }
                if (newObject.option.constraintType === 'equal_to') {
                    newObject.quantity = newObject.minQuantity
                }
            }
            if (newObject.quantity < 0) {
                newObject.quantity = 0
            }

            newObject.total = newObject.unitPrice * newObject.quantity
            this.$set(_.get(this, this.alias.buildingOptions), buildingOptionIndex, newObject)
        }
    }
}
