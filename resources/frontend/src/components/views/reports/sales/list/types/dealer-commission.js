import def from './default'
import moment from 'moment'
import parseDates from 'src/components/views/_base/ListItems/_helpers/parse-route-search-dates'
let defo = _.cloneDeep(def)

let dimensions = _.map(defo.dimensions, (item) => {
    // enable
    if (item.id === 'sale_id') item.checked = true
    if (item.id === 'date_created') item.checked = false
    if (item.id === 'building_sn') item.checked = true
    if (item.id === 'dealer') item.checked = true
    if (item.id === 'invoice_id') item.checked = true
    if (item.id === 'dealer_commission') item.checked = true
    if (item.id === 'retail') item.checked = true
    if (item.id === 'sales_tax') item.checked = true
    // disable
    if (item.id === 'status_id') item.checked = false
    if (item.id === 'customer') item.checked = false
    if (item.id === 'order_type') item.checked = false
    if (item.id === 'payment_type') item.checked = false
    return item
})

let searches = _.map(defo.searches, (item) => {
    if (item.id === 'date_created') {
        item.checked = false
        item.value = {
            between: [
                moment().startOf('month').format('YYYY-MM-DD HH:mm:ss'),
                moment().endOf('month').format('YYYY-MM-DD HH:mm:ss')
            ]
        }
        item.parse = function(value) {
            let between = this.value.between
            _.merge(between, parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD HH:mm:ss'))
            return {between}
        }
    }
    if (item.id === 'status_id') {
        item.checked = true
        item.value = 'invoiced'
    }
    if (item.id === 'order_type') {
        item.checked = false
        item.value = null
    }
    if (item.id === 'payment_type') {
        item.checked = false
        item.value = null
    }
    if (item.id === 'building_id') {
        item.checked = false
        item.value = null
    }
    if (item.id === 'dealer') {
        item.checked = false
        item.value = null
    }
    return item
})

let totals = _.map(defo.totals, (item) => {
    if (item.id === 'dealerCommission') {
        item.checked = true
        item.value = null
    }
    if (item.id === 'totalRetail') {
        item.checked = true
        item.value = null
    }
    if (item.id === 'totalSalesTax') {
        item.checked = false
        item.value = null
    }
    return item
})

export default {
    dimensions,
    searches,
    totals
}
