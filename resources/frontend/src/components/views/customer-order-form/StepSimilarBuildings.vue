<template>

    <div>
        <div id="loading" ref="loading" class="text-center" v-if="loading">
            <p class="text-muted">Loading...</p>
            <i class="fa fa-circle-o-notch fa-spin fa-2x text-muted"></i><br>
        </div>

        <div v-if="loading === null" class="alert alert-info" role="alert">
            Select building style and dimension to view the similar building inventory.
        </div>

        <div v-if="loading === false && similarBuildings === null" class="alert alert-warning" role="alert">
            There are no inventory buildings that are similar to the one you selected.
        </div>

        <div v-if="loading === false && (response.statusText || response.message)" class="alert alert-danger" role="alert">
            <span v-if="response.statusText">{{ response.statusText }}</span>
            <span v-if="response.message">{{ response.message }}</span>
        </div>

        <div class="row text-center">
            <button class="btn btn-default"
                    style="margin-bottom: 1em; margin-top: 1em"
                    @click.prevent="nextStep()">Next<i class="fa fa-arrow-right fa-fw"></i>
            </button>
        </div>

        <div v-if="loading === false && similarBuildings !== null">
            <h5 class="list-group-item-heading">
                Here is a list of inventory buildings that are similar to the one you selected.<br>
                Buildings in inventory are generally delivered within 2 business days.
            </h5>

            <div class="list-group">
                <a class="list-group-item" v-for="similarBuilding in similarBuildings">
                    <h4 class="list-group-item-heading">
                        <span v-if="similarBuilding.serialNumber">{{ similarBuilding.serialNumber }}</span>
                    </h4>
                    <p class="list-group-item-text">
                        <i class="fa fa-truck" aria-hidden="true"></i>
                        {{ similarBuilding.lastLocation.dealer.businessName }}
                        <i class="fa fa-phone" aria-hidden="true"></i>
                        {{ similarBuilding.lastLocation.dealer.phone }}
                    </p>
                </a>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    export default {
        data() {
            return {
                _token: null,
                similarBuildings: null,
                loading: null,
                response: {}
            }
        },
        mounted() {
            this._token = window.document.getElementById('_token').content
            this.$parent.$watch('buildingStyle', this.detectBuildingModel)
            this.$parent.$watch('buildingDimension', this.detectBuildingModel)
        },
        computed: {
        },
        methods: {
            nextStep() {
                this.$parent.nextStep('next')
            },
            pullSimilarBuildings(response) {
                if (_.isObject(response.data['payload']) && _.isArray(response.data['payload']['buildings']) && !_.isEmpty(response.data['payload']['buildings'])) {
                    this.similarBuildings = response.data['payload']['buildings']
                    return true
                }

                if (response.data['status'] === 'error' || !response.ok) {
                    if (!_.isEmpty(response.data['message'])) {
                        this.response.message = response.data['message']
                    }
                }

                this.similarBuildings = null
                return false
            },
            detectBuildingModel(oldVal, newVal) {
                var self = this
                if (!_.isNull(this.$parent.buildingStyle) && !_.isNull(this.$parent.buildingDimension)) {
                    var options = {
                        _token: this._token,
                        building_style: this.$parent.buildingStyle,
                        building_dimension: this.$parent.buildingDimension
                    }

                    this.loading = true
                    this.response = {}
                    this.$http.post('api/similar-inventory', options).then(
                            (response) => {
                                self.pullSimilarBuildings(response)
                                self.loading = false
                            },
                            (response) => {
                                self.response.statusText = response.statusText
                                self.loading = false
                            }
                    )
                }
            }
        }
    }
</script>

<style type="text/css">
</style>