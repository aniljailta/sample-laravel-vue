// grumpy =(
const orderBuildingCondition = state => {
    return state.building.buildingCondition
}

const orderBuildingStyle = state => {
    return state.building.buildingStyle
}

const orderBuildingDimension = state => {
    return state.building.buildingDimension
}

const orderSaleType = state => {
    return state.building.saleType
}

const orderBuildingPackage = state => {
    return state.building.buildingPackage
}

const orderSerial = state => {
    return state.building.serial
}

const orderInventoryBuilding = state => {
    return state.building.inventoryBuilding
}

const orderCustomBuildOptions = state => {
    return state.building.customBuildOptions
}

const order3dViewer = state => {
    return state.building.threedoptions
}
// ex.Computed
const currentOrderSaleType = state => {
    let saleType = orderSaleType(state)
    if (saleType === null) {
        return 'Not yet selected'
    }

    if (saleType === 'dealer-inventory') {
        return 'Dealer Inventory'
    } else {
        return 'Custom Order'
    }
}

const currentOrderSerialNumber = state => {
    let serial = orderSerial(state)
    if (serial === '') {
        return 'Not yet selected'
    }

    return serial
}

const currentBuilding = state => {
    let building = state.building
    let currentBuilding = {
        shellPrice: 0,
        totalOptions: 0,
        totalPrice: 0
    }

    // Custom Order
    if (building.saleType === 'dealer-inventory') {
        // Get Base Model Price
        if (building.inventoryBuilding.serial !== null) {
            currentBuilding.shellPrice = building.inventoryBuilding.shellPrice
            currentBuilding.totalOptions = building.inventoryBuilding.totalOptions
            currentBuilding.totalPrice = building.inventoryBuilding.price
        }
    }

    // Custom Order
    if (building.saleType === 'custom-order') {
        // Get Base Model Price
        if (building.buildingDimension) {
            currentBuilding.shellPrice += building.buildingDimension.shellPrice
        }

        let totalOptions = 0
        if (building.customBuildOptions.length > 0) {
            totalOptions = _.reduce(building.customBuildOptions, function (total, el) {
                total += (el.unitPrice * el.quantity)
                return total
            }, 0)
        }
        currentBuilding.totalOptions += totalOptions
        currentBuilding.totalPrice = currentBuilding.shellPrice + currentBuilding.totalOptions
    }

    return currentBuilding
}

export default {
    orderBuildingCondition,
    orderBuildingStyle,
    orderBuildingDimension,
    orderSaleType,
    orderBuildingPackage,
    orderSerial,
    orderInventoryBuilding,
    orderCustomBuildOptions,
    currentBuilding,
    currentOrderSaleType,
    currentOrderSerialNumber,
    order3dViewer
}