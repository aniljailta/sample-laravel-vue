/*global swal*/

export default {
  /*
   * Required data: this.data.alias
   * Required props: 'this.data.alias'
   */
  methods: {
    addOption(option, extraProps) {
      let buildingOptions = _.get(this, this.alias.buildingOptions)
      var selectedBuildingOption = _.find(buildingOptions, function(el) {
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
          'unitPrice': option.unitPrice
        }, extraProps)

        buildingOption.totalPrice = buildingOption.unitPrice * buildingOption.quantity

        this.addOrderBuildingCustomOption(buildingOption)
      } else {
        let optionIndex = _.findIndex(buildingOptions, (el) => el.optionId === option.id)

        extraProps.quantity = selectedBuildingOption.quantity + 1
        extraProps.totalPrice = selectedBuildingOption.unitPrice * selectedBuildingOption.quantity
        this.increaseOrderBuildingCustomOption(optionIndex, extraProps)
      }
    },
    removeOption(buildingOption) {
      let buildingOptions = _.get(this, this.alias.buildingOptions)
      if (buildingOptions.length > 0) {
        var options = _.filter(buildingOptions, function(item) {
          return item.optionId !== buildingOption.optionId
        }, this)

        this.updateOrderBuilding({ customBuildOptions: options })
      }
    },
    increaseOption(buildingOption) {
      let buildingOptions = _.get(this, this.alias.buildingOptions)
      var optionIndex = _.findIndex(buildingOptions, function(el) {
        return el.optionId === buildingOption.optionId
      }, this)

      if (optionIndex !== -1) {
        this.increaseOrderBuildingCustomOption(optionIndex)
      }
    },
    decreaseOption(buildingOption) {
      let buildingOptions = _.get(this, this.alias.buildingOptions)

      var selectedOption = _.find(buildingOptions, function(el) {
        return el.optionId === buildingOption.optionId
      }, this)

      if (typeof selectedOption !== 'undefined') {
        if (selectedOption.minQuantity >= selectedOption.quantity) return

        var optionIndex = _.findIndex(buildingOptions, function(el) {
          return el.optionId === buildingOption.optionId
        }, this)

        this.decreaseOrderBuildingCustomOption(optionIndex, {
          quantity: selectedOption.quantity - 1,
          totalPrice: selectedOption.unitPrice * selectedOption.quantity
        })
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
      if (newObject.minQuantity > newObject.quantity) {
        newObject.quantity = newObject.minQuantity
      }

      newObject.totalPrice = newObject.unitPrice * newObject.quantity
      this.updateOrderBuildingCustomOption(buildingOptionIndex, newObject)
    }
  }
}
