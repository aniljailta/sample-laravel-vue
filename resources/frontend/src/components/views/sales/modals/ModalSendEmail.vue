<template>

    <div>
        <modal :show="show"
               :modal-class="modalClass"
               :modal-style="modalStyle"
               :mask-style="maskStyle">

            <div>
                <div class="panel-heading">
                    <h2 class="panel-title">Send email for sale # {{ item.id }}</h2>
                </div>
                <div class="panel-body modal-body">
                    <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

                    <div class="panel panel-default preview" v-if="preview">
                        <div class="panel-heading"><b>Subject:</b> {{ preview.subject }}</div>
                        <div class="panel-heading"><b>To:</b> <span v-for="to in preview.to">{{ to.address }} ({{ to.name }})</span></div>
                        <div class="panel-heading"><b>Cc:</b> <span v-for="cc in preview.cc">{{ cc.address }} ({{ cc.name }})</span></div>
                        <div class="panel-body" v-html="preview.body"></div>
                    </div>
                </div>
                <div class="panel-footer" style="text-align: center">
                    <button type="button" class="btn btn-default" v-on:click="close">Close</button>
                    <button type="button" class="btn btn-primary" v-on:click="send" v-bind:disabled="dataProcess.running">Send</button>
                </div>
            </div>

        </modal>
    </div>

</template>

<script type="text/babel">
    import Modal from 'src/components/ui/Modal.vue'
    import DataProcess from 'src/components/ui/DataProcess.vue'
    import DataProcessMixin from 'src/mixins/vue-data-process'

    import apiSales from 'src/api/sales'

    export default {
        mixins: [DataProcessMixin],
        data() {
            return {
                modalClass: 'col-md-8 col-sm-9 col-xs-11',
                modalStyle: {
                    float: 'none',
                    padding: '0'
                },
                maskStyle: {
                    position: 'fixed'
                },
                preview: null
            }
        },
        components: {
            Modal,
            DataProcess
        },
        props: {
            item: {
                required: true
            },
            show: {
                type: Boolean,
                default: false
            }
        },
        methods: {
            send() {
                this.run({text: 'Sending email..', type: 'send-preview'})
                return apiSales.sendEmail({item: this.item})
                    .then(response => {
                        this.$emit('data-process-update', {
                            running: false,
                            success: response.data.message
                        })
                        this.$emit('item-saved')
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            },
            close() {
                this.$emit('close')
            }
        },
        created() {
            apiSales.sendEmail({
                item: this.item,
                params: {preview: true}
            }).then(response => {
                this.$emit('data-process-update', {running: false})
                this.preview = response.data
            }).catch(response => {
                this.$emit('data-failed', response)
            })
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
    .preview .panel-heading {
        padding-top: 3px !important;
        padding-bottom: 3px !important;
    }
</style>