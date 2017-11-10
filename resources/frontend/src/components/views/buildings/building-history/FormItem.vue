<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

        <form v-show="dataIsReady">
            <div class="form-group">
                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-12">
                        <label for="status" class="control-label">New Status</label>
                        <select id="status"
                                name="status"
                                class="form-control"
                                v-model="curItem.statusId"
                                v-on:change="selectStatus($event.target.value)"
                                initial="off">
                            <option v-for="status in buildingStatuses"
                                    v-bind:value="status.value"
                                    v-bind:disabled="status.options && status.options.disabled"
                                    v-bind:selected="curItem.statusId && parseInt(curItem.statusId) === status.value">
                                {{ status.display }}
                                <template v-if="status.options && status.options['data-cost']">
                                    ({{ filters.money(status.options['data-cost']) }})
                                </template>
                            </option>
                        </select>
                        <!--<div v-if="$validator.status_id && $validator.status_id.required" class="alert alert-danger" role="alert">Field is required.</div>-->
                    </div>
                </div>
            </div>
        </form>

    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    // apis
    import apiBuildingStatuses from 'src/api/building-statuses'
    import apiBuildingHistories from 'src/api/building-histories'
    import apiUsers from 'src/api/users'

    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'

    export default {
        extends: BaseDataItem,
        name: 'building-status-form',
        data() {
            return {
                curItem: {},
                buildingStatuses: []
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

                const datas = [
                    apiBuildingStatuses.getToPrioritize({
                        building_id: this.item.buildingId
                    }),
                    apiUsers.get({
                        query: {
                            per_page: 9999,
                            order: 'id asc'
                        }
                    })
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.buildingStatuses = response[0].data
                        return response
                    })
                    .then(() => {
                        this.$emit('data-ready')
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
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
                        statusId: this.curItem.statusId || null
                    })

                    if (this.curItem.id) item.id = this.curItem.id

                    let form = objectToFormData(convertKeys.toSnake(item))

                    this.run({text: 'Saving..', type: 'form'})
                    return apiBuildingHistories.save({ item: item, data: form })
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
            },
            selectStatus(id) {
                let status = _.find(this.buildingStatuses, { value: parseInt(id) })
                // this.$set(this.curItem, 'statusId', status.value)
                if (_.isUndefined(status)) return

                let cost = _.get(status, 'options.data-cost', null)
                this.$set(this.curItem, 'cost', cost)
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
</style>