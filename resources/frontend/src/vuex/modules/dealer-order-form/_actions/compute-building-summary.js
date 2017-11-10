import types from '../types'

import {
    orderBuildingCondition,
    orderSaleType,
    orderSerial,
    orderInventoryBuilding,
    orderBuildingStyle,
    orderBuildingDimension,
    orderCustomBuildOptions,
    currentOrderSaleType,
    order3dViewer
} from '../_getters/order-building'

const computeOrderBuildingSummary = function ({commit, state}) {
    let summary = {
        buildingCondition: orderBuildingCondition(state),
        saleType: orderSaleType(state),
        serial: orderSerial(state),
        inventoryBuilding: orderInventoryBuilding(state),
        buildingStyle: orderBuildingStyle(state),
        buildingDimension: orderBuildingDimension(state),
        customBuildOptions: _.size(orderCustomBuildOptions(state) === 0) ? null : orderCustomBuildOptions(state),
        currentSaleType: currentOrderSaleType(state),
        threedViewer: order3dViewer(state)
    }

    commit(types.UPDATE_SUMMARY, {building: summary})
}

export default computeOrderBuildingSummary
