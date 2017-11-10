/**
 * Communicates with API server about buildings
 */

import Vue from 'vue'
import { csrfToken } from './_config'

const actions = {
    // required for calculate URL for export data to xls,csv
    get: {
        method: 'GET',
        url: '/api/price-groups{/id}'
    },
    updatePriceGroup: {
        method: 'POST',
        url: '/api/price-group/update-publish-date'
    },
    getPriceLists: {
        method: 'GET',
        url: '/api/price-group/get-price-list'
    },
    updatePriceLists: {
        method: 'POST',
        url: '/api/price-group/update-price-list'
    },
    importPriceList: {
        method: 'POST',
        url: '/api/price-group/import-price-list'
    },
    priceGroupCategories: {
        method: 'GET',
        url: '/api/price-group/categories'
    },
    priceGroupStatuses: {
        method: 'GET',
        url: '/api/price-group/statuses'
    }
}

const options = {
    headers: {
        'X-CSRF-TOKEN': csrfToken()
    }
}

const priceGroupResource = Vue.resource('/api/price-groups{/id}', {}, actions, options)

export default {
  actions,
    get ({ id, query }) {
        return priceGroupResource.get({ id, ...query })
    },
    save ({ item, data }) {
        if (typeof item.id === 'undefined') {
          return priceGroupResource.save({}, data)
        } else {
          data.append('_method', 'put')
          return priceGroupResource.save({
            id: item.id
          }, data)
        }
    },
    delete({ item }) {
        return priceGroupResource.delete({ id: item.id })
    },
    updatePriceGroup ({item, data}) {
        return priceGroupResource.updatePriceGroup({}, data)
    },
    getPriceLists ({item}) {
        return priceGroupResource.getPriceLists({ id: item.id, category: item.category })
    },
    updatePriceLists ({item, data}) {
        return priceGroupResource.updatePriceLists({}, data)
    },
    importPriceList ({item, data}) {
        return priceGroupResource.importPriceList({}, data)
    },
    priceGroupCategories () {
        return priceGroupResource.priceGroupCategories()
    },
    priceGroupStatuses () {
        return priceGroupResource.priceGroupStatuses()
    }
}
