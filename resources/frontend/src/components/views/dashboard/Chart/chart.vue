<template>
<div>
  <div class="row well-sm well-xs">
    <div class="col-xs-12 col-sm-12">
      <vue-highcharts :options="options" ref="lineCharts2"></vue-highcharts>
    </div>
  </div>
  <div class="row well-sm well-xs">
    <div class="col-xs-12 col-sm-12">
      <table>
        <tr>
          <td>
            <dt class="well-sm well-xs pull-right">Time Period</dt>
          </td>
          <td>
            <div class="row well-sm well-xs">
              <div class="col-xs-12 col-sm-12">
                <div class="btn-group" role="group" aria-label="...">
                  <button type="button" @click="loadChartData({type: 'activeXAxis', value: 2})" v-bind:class="{ active: activeXAxis === 2 }" class="btn btn-default">This week</button>
                  <button type="button" @click="loadChartData({type: 'activeXAxis', value: 0})" v-bind:class="{ active: activeXAxis === 0 }" class="btn btn-default">Current Month</button>
                  <button type="button" @click="loadChartData({type: 'activeXAxis', value: 4})" v-bind:class="{ active: activeXAxis === 4 }" class="btn btn-default">YTD</button>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <dt class="well-sm well-xs pull-right">Right axis</dt>
          </td>
          <td>
            <div class="row well-sm well-xs">
              <div class="col-xs-12 col-sm-12">
                <div class="btn-group" role="group" aria-label="...">
                  <button type="button" @click="loadChartData({type: 'activeRAxis', value: 0})" v-bind:class="{ active: activeRAxis === 0 }" class="btn btn-default">$</button>
                  <button type="button" @click="loadChartData({type: 'activeRAxis', value: 3})" v-bind:class="{ active: activeRAxis === 3 }" class="btn btn-default">Count</button>
                  <button type="button" @click="loadChartData({type: 'activeRAxis', value: 1})" v-bind:class="{ active: activeRAxis === 1 }" class="btn btn-default">LFt</button>
                  <button type="button" @click="loadChartData({type: 'activeRAxis', value: 2})" v-bind:class="{ active: activeRAxis === 2 }" class="btn btn-default">SqFt</button>
                </div>
              </div>
            </div>
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>

</template>
 
<script type="text/babel">
import VueHighcharts from 'vue2-highcharts'
import apiDashboardCharts from 'src/api/dashboard-charts'
export default {
  name: 'chart2',
  components: {
    VueHighcharts
  },
  data: () => (
    {
      activeRAxis: 0,
      activeLAxis: 0,
      activeXAxis: 0,
      options: {
            colors: [
              '#008c48', // current line
              '#7ac36a', // current column
              '#010202', // prior line
              '#737373', // prior column
              '#d35400',
              '#2c3e50',
              '#7f8c8d'
              ],
            chart: {
              type: 'area',
              style: {
                // fontFamily: 'Roboto',
                color: '#666666'
              }
            },
            title: {
              align: 'left',
              style: {
                // fontFamily: 'Roboto Condensed',
                fontWeight: 'bold'
              },
              text: 'Sales'
            },
            subtitle: {
              align: 'left',
              style: {
                // fontFamily: 'Roboto Condensed'
              }
            },
            legend: {
              align: 'right',
              verticalAlign: 'bottom'
            },
            xAxis: {
              categories: []
            },
            yAxis: [
              {
                title: {
                  text: false
                },
                gridLineWidth: 1,
                gridLineColor: '#F3F3F3',
                lineColor: '#F3F3F3',
                minorGridLineColor: '#F3F3F3',
                tickColor: '#F3F3F3',
                tickWidth: 1
              },
              {
                title: {
                  text: false
                },
                opposite: true
              }
            ],
            plotOptions: {
              spline: {
                lineWidth: 4,
                marker: {
                  enabled: false
                }
              }
            },
            tooltip: {
              crosshairs: true,
              shared: true
            },
            credits: {
              enabled: false
            },
            series: []
          }
    }
  ),
  methods: {
    apiGet(query) {
        return apiDashboardCharts.get({ query })
    },
    loadChartData() {
      if (arguments.length) {
        this[arguments[0].type] = arguments[0].value
      }
      apiDashboardCharts.chart({
        chart: 2,
        activeRAxis: this.activeRAxis,
        activeLAxis: this.activeLAxis,
        activeXAxis: this.activeXAxis
      })
      .then(
          (response) => {
              let newSeries = JSON.parse(response.bodyText)
              let lineCharts = this.$refs.lineCharts2
              lineCharts.delegateMethod('showLoading', 'Loading...')
              setTimeout(() => {
                  lineCharts.removeSeries(false)
                  lineCharts.delegateMethod('update', newSeries.options)
                newSeries.series.forEach((series, index) => {
                  lineCharts.addSeries(series, index === newSeries.series.length - 1)
                })
                lineCharts.hideLoading()
              }, 2000)
          }
      )
      .catch((response) => {
          if (response.status === 422) return Promise.reject(response.data)
          return Promise.reject(response.statusText)
      })
    }
    // queries() {
    //     return queries
    // }
  },
  created() {
    this.loadChartData()
  }
}
</script>