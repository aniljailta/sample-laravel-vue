<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

        <form v-show="dataIsReady">
            <div class="form-group">
                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-12">
                        <label for="location" class="control-label">New Location</label>
                        <select id="location"
                                name="location"
                                class="form-control"
                                v-model="curItem.locationId"
                                initial="off">
                            <option v-for="location in locations"
                                    v-bind:value="location.id"
                                    v-bind:selected="curItem.locationId && parseInt(curItem.locationId) === location.id">
                                {{ location.name }}
                            </option>
                        </select>
                        <!--<div v-if="$validator.location_id && $validator.location_id.required" class="alert alert-danger"
                             role="alert">Field is required.
                        </div>-->
                    </div>
                </div>
            </div>
        </form>

    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    // apis
    import apiLocations from 'src/api/locations'
    import apiBuildingLocations from 'src/api/building-locations'

    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'

    export default {
        extends: BaseDataItem,
        name: 'building-location-form',
        data() {
            return {
                curItem: {},
                locations: []
            }
        },
        methods: {
            initData() {
                if (this.params.mode && this.params.mode === 'create') {
                    this.curItem = {buildingId: this.item.buildingId}
                }

                if (this.params.mode && this.params.mode === 'update') {
                    this.curItem = _.cloneDeep(this.item)
                }

                apiLocations.get({
                    query: {
                        per_page: 99999
                    }
                }).then(response => {
                    this.locations = response.data.data
                    this.$emit('data-ready')
                })
            },
            submit() {
                let self = this
                return new Promise(function (resolve) {
                    /* self.$validate(function () {
                     if (self.$validator.valid) resolve()
                     })*/
                    resolve()
                }).then(() => {
                    let item = _.merge({}, {
                        buildingId: this.curItem.buildingId || null,
                        locationId: this.curItem.locationId || null
                    })

                    if (this.curItem.id) item.id = this.curItem.id

                    let form = objectToFormData(convertKeys.toSnake(item))

                    this.run({text: 'Saving..', type: 'form'})
                    return apiBuildingLocations.save({ item: item, data: form })
                            .then(response => {
                                self.$emit('data-process-update', {
                                    running: false,
                                    success: response.data.msg
                                })
                                self.$emit('item-saved')
                            })
                            .catch(response => {
                                this.$emit('data-failed', response)
                            })
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
</style>