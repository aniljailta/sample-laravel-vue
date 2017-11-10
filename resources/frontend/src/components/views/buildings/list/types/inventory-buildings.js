import def from './default'
let defo = _.cloneDeep(def)

let dimensions = _.map(defo.dimensions, (item) => {
    // enable
    if (item.id === 'sort_id') item.checked = true
    if (item.id === 'serial_number') item.checked = true
    if (item.id === 'total_price') item.checked = true
    if (item.id === 'location') item.checked = true

    if (item.id === 'roof_color') item.checked = true
    if (item.id === 'siding_color') item.checked = true
    if (item.id === 'trim_color') item.checked = true

    // disable
    if (item.id === 'build_status') item.checked = false
    if (item.id === 'order_id') item.checked = false
    if (item.id === 'sale_id') item.checked = false
    if (item.id === 'customer') item.checked = false
    if (item.id === 'date_sold') item.checked = false
    return item
})

let searches = _.map(defo.searches, (item) => {
    if (item.id === 'status_id') {
        item.checked = true
        item.value = '4'
    }
    if (item.id === 'location_category') {
        item.checked = true
        item.value = 'dealer'
    }
    if (item.id === 'serial_numbers_values') {
        item.checked = false
        item.value = null
    }
    if (item.id === 'serial_numbers') {
        item.checked = false
        item.value = null
    }
    if (item.id === 'sold_status') {
        item.checked = true
        item.value = 'not_sold'
    }
    return item
})

let totals = _.map(defo.totals, (item) => {
    return item
})

export default {
    dimensions,
    searches,
    totals
}
