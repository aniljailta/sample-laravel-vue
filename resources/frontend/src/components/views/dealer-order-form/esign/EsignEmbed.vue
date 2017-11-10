<template>

    <modal :show="getEsignEmbed.show"
           :modal-class="modalClass"
           :modal-style="modalStyle"
           :close-modal-method="close">
        <div class="panel-container">
            <div class="panel-heading">
                <h2 class="panel-title">E-sign Order</h2>
            </div>

            <div class="panel-body">
                <iframe frameborder="0" width="100%" height="100%" v-bind:src="'/orders/' + orderCurrent.id + '/initial-esign'"></iframe>
            </div>

            <div class="panel-footer" style="text-align: center">
                <button type="button" class="btn btn-default" v-on:click="close">Close</button>
            </div>
        </div>
    </modal>

</template>

<script type="text/babel">
    import {mapActions, mapGetters} from 'vuex'
    import Modal from 'src/components/ui/Modal.vue'

    export default {
        components: {
            Modal
        },
        data() {
            return {
                modalClass: 'col-md-9 col-sm-12 col-xs-12 dof-esigns-modal',
                modalStyle: {
                    float: 'none',
                    padding: '0',
                    height: '90%',
                    maxWidth: '1024px'
                }
            }
        },
        created() {
            let self = this
            window.addEventListener('message', receiveMessage, false)

            function receiveMessage(event) {
                // For Chrome, the origin property is in the event.originalEvent object.
                let origin = event.origin || event.originalEvent.origin
                let allowOrigin = location.protocol + '//' + location.host

                if (origin !== allowOrigin) return
                if (_.get(event, 'data.event', null) !== 'signed') return
                if (!_.get(event, 'data.fileId')) return

                self.$bus.$emit('dofFileSigned', event.data.fileId)
            }
        },
        computed: {
            ...mapGetters({
                getEsignEmbed: 'dealerOrderForm/esign/getEsignEmbed',
                orderState: 'dealerOrderForm/orderState',
                orderCurrent: 'dealerOrderForm/orderCurrent',
                attachmentsPerCategory: 'dealerOrderForm/attachmentsPerCategory'
            }),
            files() {
                return this.attachmentsPerCategory
            }
        },
        methods: {
            ...mapActions({
                addAttachment: 'dealerOrderForm/addAttachment',
                esignEmbedShow: 'dealerOrderForm/esign/esignEmbedShow',
                esignEmbedHide: 'dealerOrderForm/esign/esignEmbedHide'
            }),
            prepareDocuments() {
                /* this.dataProcess.text = 'Preparing documents..'
                let item = {
                    id: this.orderCurrent.id,
                    categoryId: 'complete_order_documents'
                }

                apiOrders.generateDocument({item, data: item})
                    .then(response => {
                        if (response.data.payload) {
                            // add attachment to store
                            _.each(response.data.payload.files, item => {
                                this.addAttachment(item)
                            })

                            this.$emit('data-ready')
                        }
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })*/
            },
            close() {
                this.esignEmbedHide()
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .panel-container {
        height: 100%;
        position: relative;
    }

    .panel-heading {
        position: absolute !important;
        height: 50px;
        width: 100%;
    }

    .panel-body {
        height: 100%;
        width: 100%;
        padding: 3.5em 0;
    }

    .panel-footer {
        position:absolute;
        height: 50px;
        bottom: 0;
        width: 100%;
    }
</style>
