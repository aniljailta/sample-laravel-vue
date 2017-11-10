<template>

    <div>
        <modal :show="show"
               :modal-class="modalClass"
               :modal-style="modalStyle"
               :mask-style="maskStyle">

            <div>
                <div class="panel-heading">
                    <h2 class="panel-title">Style Catalog</h2>
                </div>
                <div class="panel-body modal-body">
                    <div class="form-container">
                        <form-item ref="styleCatalog"
                                   v-bind:params="params"
                                   v-bind:item="item"
                                   v-on:data-process="dataProcessChange"
                                   v-on:item-saved="saved">
                        </form-item>
                    </div>
                </div>
                <div class="panel-footer" style="text-align: center">
                    <button type="button" class="btn btn-default" v-on:click="close">Close</button>
                    <button type="button" class="btn btn-primary" v-if="item.updateType !== 'immediate'"
                            v-on:click="save" v-bind:disabled="dataProcess.running || (dataProcess.type === 'data' && dataProcess.error)">
                        Save
                    </button>
                </div>
            </div>

        </modal>
    </div>

</template>

<script type="text/babel">
    import Modal from 'src/components/ui/Modal.vue'
    import DataProcessMixin from 'src/mixins/vue-data-process'
    import FormItem from '../form/StyleCatalog.vue'

    export default {
        mixins: [DataProcessMixin],
        data() {
            return {
                modalClass: 'col-md-8 col-sm-10 col-xs-12',
                modalStyle: {
                    float: 'none',
                    padding: '0'
                },
                maskStyle: {
                    position: 'fixed'
                }
            }
        },
        components: {
            Modal,
            FormItem
        },
        props: {
            params: {
                default() {
                    return {}
                }
            },
            item: {
                required: true
            },
            show: {
                type: Boolean,
                default: false
            }
        },
        methods: {
            save() {
                this.$refs.styleCatalog.submit()
            },
            saved(payload) {
                this.$emit('saved', payload)
            },
            close() {
                this.$emit('close')
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>