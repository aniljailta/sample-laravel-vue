/**
 * Communicates with API server about buildings
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const actions = {
    // TODO: should be changed to primary get() later
    perId: {
        method: 'GET',
        url: '/api/buildings/per-id'
    },
    searchBuildingBySerial: {
        method: 'GET',
        url: '/api/dealer-inventory-search/{dealerId}/{serial}'
    },
    statuses: {
        method: 'GET',
        url: '/api/buildings/statuses'
    },
    // + required for calculate URL for export data to xls,csv
    inventory: {
        method: 'GET',
        url: '/api/buildings/inventory'
    }
}

const options = {
    headers: {
        'X-CSRF-TOKEN': csrfToken()
    }
}

const buildingResource = Vue.resource('/api/buildings{/id}', {}, actions, options)

export default {
    actions,
    get ({id, query}) {
        return buildingResource.get({id, ...query})
    },
    save ({item, data}) {
        if (typeof item.id === 'undefined') {
            return buildingResource.save({}, data)
        } else {
            data.append('_method', 'put')
            return buildingResource.save({
                id: item.id
            }, data)
        }
    },
    delete({item}) {
        return buildingResource.delete({id: item.id})
    },
    // TODO: should be changed to primary get() later
    allBuildings () {
        return buildingResource.perId().then((response) => {
            return response.data
        }, (response) => {
            if (response.status === 422) return response.data
            return response.statusText
        })
    },
    updateBuilding ({payload}) {
        return buildingResource.update({
            id: payload.id
        }, payload)
    },
    searchBuildingBySerial ({params}) {
        return buildingResource.searchBuildingBySerial(params)
    },
    inventory({query}) {
        return buildingResource.inventory(query)
    },
    statuses ({params}) {
        return buildingResource.statuses(params)
    }
}
