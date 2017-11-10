/*eslint new-cap: 0 */

import {
    settings
} from '../settings/_getters'

import orderOrderDate from '../_getters/date'
import orderBuilding from '../_getters/order-building'
import updateOrderOrder from './order'

moment.updateLocale('en', {
    week: {
        dow: 1 // Monday is the first day of the week.
    }
})
let dateFormat = 'MM/DD/YYYY'

const computeCed = function ({state, commit}) {
    let saleType = orderBuilding.orderSaleType(state)
    let leadTimeSetting = settings(state.settings, {id: 'leadTime'})
    let estimatedDeliveryPeriodSetting = settings(state.settings, {id: 'estimatedDeliveryPeriod'})

    let orderDate = orderOrderDate(state)
    let ced

    if (leadTimeSetting !== null && orderDate) {
        const orderDateMoment = new moment(orderDate, dateFormat) // should be date from DB too?

        let startOffset
        let leadTime = _.toInteger(leadTimeSetting)
        let estimatedDeliveryPeriod = _.toInteger(estimatedDeliveryPeriodSetting)

        if (saleType === 'custom-order') startOffset = _.toInteger(leadTime)
        if (saleType === 'dealer-inventory') startOffset = 0

        let startPeriod = orderDateMoment.clone().add(startOffset, 'days')
        // need to get any day from Monday-Friday after adding offset
        if (startPeriod.isoWeekday() > 5) {
            startPeriod = startPeriod.endOf('week').add(1, 'days') // get nearest monday
        }

        // adding estimated delivery period offset
        let endPeriod = startPeriod.clone().add(estimatedDeliveryPeriod, 'days')
        // need to get any day from Monday-Friday after adding offset
        if (endPeriod.isoWeekday() > 5) {
            endPeriod = endPeriod.endOf('week').add(1, 'days') // get nearest monday
        }

        ced = {
            start: startPeriod.format(dateFormat),
            end: endPeriod.format(dateFormat)
        }
    } else {
        ced = {
            start: null,
            end: null
        }
    }

    updateOrderOrder({state, commit}, {'customerExpectsDate': ced})
}

export default computeCed