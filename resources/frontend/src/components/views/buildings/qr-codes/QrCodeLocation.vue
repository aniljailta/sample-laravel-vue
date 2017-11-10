<template>
    <section class="row overlayable">
        <data-process :process="dataProcess" :with_loader="true"></data-process>

        <div class="col-xs-12 col-sm-12 col-md-12" v-if="!this.qrcode && dataIsReady">
            <button class="btn btn-md btn-success"
                    title="Generate Build Status QR Code"
                    v-on:click="openQrGenerateModal">
                <i class="fa fa-qrcode" aria-hidden="true"></i> Generate Location Update QR Code
            </button>
        </div>

        <div class="row" v-if="this.qrcode && dataIsReady">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <section class="panel sub-panel">
                    <header class="panel-heading">
                        <h4 class="panel-title">Building Location QR Code Details</h4>
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                <dl class="dl-horizontal mb-none">
                                    <dt>Created at</dt>
                                    <dd>{{ filters.moment(qrcode.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') || '-'}}</dd>

                                    <dt>Updated at</dt>
                                    <dd>{{ filters.moment(qrcode.updatedAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') || '-'}}</dd>
                                </dl>
                                <dl class="dl-horizontal mb-none">
                                    <dt>Expiry Date</dt>
                                    <dd>{{ filters.moment(qrcode.expireOn, 'YYYY-MM-DD', 'MM/DD/YYYY') || '-' }}</dd>
                                </dl>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">
                                <img v-if="qrcode.path" v-bind:src="'/storage/'+qrcode.path">
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="btn-group">
                            <button type="button" class="btn btn-md btn-success" title="Regenerate location update QR Code" v-on:click="openQrGenerateModal">
                                <i class="fa fa-qrcode" aria-hidden="true"></i> Regenerate Location Update QR Code
                            </button>
                            <a type="button" target="_blank" class="btn btn-md btn-warning" title="Print Qr Code" v-bind:href="'/print/'+qrcode.identifier+'?print=now'">
                                <i class="fa fa-print" aria-hidden="true"></i> Print QR Code
                            </a>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <component v-if="qrGenerateModal"
                   is="QrGenerateModal"
                   transition="modal"
                   :item="item"
                   :params=" { modaltitle: 'Generate Building Location Update QR Code',type: 'location' } "
                   v-on:close="closeQrGenerateModal"
                   v-on:saved="qrGenerateCallback"
                   :show="qrGenerateModal"></component>
    </section>
</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import apiQrCode from 'src/api/qrcodes'

    export default {
        extends: BaseDataItem,
        name: 'qrcode-build',
        data() {
            return {
                qrGenerateModal: false,
                qrcode: []
            }
        },
        components: {
            QrGenerateModal: function(resolve) {
                require(['../qr-codes/modals/ModalCreate.vue'], resolve)
            }
        },
        props: {
            item: {
                default() {
                    return {}
                }
            }
        },
        computed: {},
        methods: {
            initData() {
                apiQrCode.get({
                    query: {
                        buildingId: this.item.id,
                        type: 'location'
                    }
                }).then(response => {
                    this.qrcode = response.data[0]
                    this.$emit('data-ready')
                }).catch(response => {
                    this.$emit('data-failed', response)
                })
            },
            openQrGenerateModal() {
                this.qrGenerateModal = true
            },
            closeQrGenerateModal() {
                this.qrGenerateModal = false
            },
            qrGenerateCallback() {
                this.initData()
                this.$parent.refresh('building-locations')
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    img{
        width:100px;
    }
</style>