/**
 * Communicates with API server about charts
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const actions = {
    get: {
        method: 'GET',
        url: '/api/charts/dashboard/chart{chartid}'
    },
    chart: {
        method: 'GET',
        url: '/api/charts/dashboard/chart{chartid}'
    }
}
const options = {
    headers: {
        'X-CSRF-TOKEN': csrfToken()
    }
}

const chartResource = Vue.resource('/api/charts/dashboard{/id}', {}, actions, options)

export default {
    actions,
    get ({chartId}) {
        return chartResource.get({chartId})
    },
    chart(chart) {
        let chartParams = {
            chartid: chart.chart,
            rAxis: chart.activeRAxis,
            xAxis: chart.activeXAxis
        }
        if (chart.hasOwnProperty('activeLAxis')) {
            chartParams['lAxis'] = chart.activeLAxis
        }
        return chartResource.chart(chartParams)
            .then(
                (response) => {
                    return response
                }
            )
            .catch((response) => {
                if (response.status === 422) return Promise.reject(response.data)
                return Promise.reject(response.statusText)
            })
    }
}
