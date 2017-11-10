import {required, maxLength} from 'vuelidate/lib/validators'
// import accepted from 'src/validators/accepted'

export default function () {
    let rules = {
        buildingCondition: {required},
        inventoryBuilding: {
            serial: {}
        }
    }

    if (this.buildingCondition === 'new') {
        rules.saleType = {required}
    }

    if (this.saleType === 'custom-order') {
        rules.buildingStyle = {required}
        rules.buildingDimension = {required}
        rules.currentRequiredCategories = {
            maxLength: maxLength(0)
        }
    }

    if (this.saleType === 'dealer-inventory') {
        rules.inventoryBuilding.serial = {required}
    }

    return rules
}
