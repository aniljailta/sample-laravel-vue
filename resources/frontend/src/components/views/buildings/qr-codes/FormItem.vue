<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

        <form v-show="dataIsReady">
            <div class="form-group">
                <span v-if="!this.qrcode">Building # {{ this.curItem.buildingId }}</span>
                <br>
                <span v-if="!this.qrcode">
                    Serial #: <label class="label label-info">{{ this.item.sn }}</label>
                </span>

                <div class="row" v-if="this.qrcode">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <section class="panel sub-panel">
                            <header class="panel-heading">
                                <h4 class="panel-title">QR Code Details</h4>
                            </header>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 text-center">
                                        <dl class="dl-horizontal mb-none">
                                            <dt>Building #</dt>
                                            <dd>{{ this.qrcode.buildingId }}</dd>
                                            <dt>Created at</dt>
                                            <dd>{{ filters.moment(qrcode.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') || '-' }}</dd>

                                            <dt>Updated at</dt>
                                            <dd>{{ filters.moment(qrcode.updatedAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') || '-' }}</dd>
                                        </dl>
                                        <dl class="dl-horizontal mb-none">
                                            <dt>Serial #</dt>
                                            <dd><span class="label label-info">{{ qrcode.serialNumber || '-' }}</span></dd>

                                            <dt>Expiration</dt>
                                            <dd>{{ filters.moment(qrcode.expireOn, 'YYYY-MM-DD', 'MM/DD/YYYY') || '-' }}</dd>
                                        </dl>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">
                                        <img v-if="qrcode.path" v-bind:src="'/storage/'+qrcode.path">
                                    </div>
                                </div>
                            </div>
                        </section>

                    </div>
                </div>
                <div class="row mt-xs">
                    <div class="col-xs-12 col-md-12">
                        <label for="location" class="control-label" v-if="this.qrcode">Update QR Code Expiration:</label>
                        <label for="location" class="control-label" v-if="!this.qrcode">QR Code Expiration:</label>
                        <datepicker id="build_date"
                                    v-bind:width="'100%'"
                                    v-bind:value="filters.moment(curItem.expire_on, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY')"
                                    v-bind:format="'MM/dd/yyyy'"
                                    v-bind:placeholder="'MM/DD/YYYY'"
                                    ref="buildDate"></datepicker>

                    </div>
                </div>
            </div>
        </form>

    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import Datepicker from 'src/components/ui/Datepicker.vue'
    // apis
    import apiQrCode from 'src/api/qrcodes'

    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'

    export default {
        extends: BaseDataItem,
        name: 'qrcode-generation',
        data() {
            return {
                curItem: {
                    expire_on: moment().add(1, 'months'),
                    buildingId: this.item.id
                },
                qrcode: []
            }
        },
        components: {
            Datepicker
        },
        methods: {
            initData() {
                apiQrCode.get({
                    query: {
                        buildingId: this.item.id,
                        type: this.params.type
                    }
                }).then(response => {
                    this.qrcode = response.data[0]
                    if (this.qrcode) this.curItem.expire_on = moment(this.qrcode.expireOn)
                    this.$emit('data-ready')
                }).catch(response => {
                    this.$emit('data-failed', response)
                })
            },
            submit() {
                let self = this
                return new Promise(function (resolve) {
                    resolve()
                }).then(() => {
                    let item = _.merge({}, {
                        buildingId: this.curItem.buildingId || null,
                        type: this.params.type || null,
                        expire_on: this.filters.moment(this.curItem.expire_on, 'MM/DD/YYYY', 'YYYY-MM-DD')
                    })
                    if (this.$refs.buildDate.val) {
                        item.expire_on = this.filters.moment(this.$refs.buildDate.val, 'MM/DD/YYYY', 'YYYY-MM-DD')
                    }
                    if (this.qrcode) item.id = this.qrcode.id
                    let form = objectToFormData(convertKeys.toSnake(item))

                    this.run({text: 'Saving..', type: 'form'})
                    return apiQrCode.save({item: item, data: form})
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
    img {
        width: 100px;
    }
</style>