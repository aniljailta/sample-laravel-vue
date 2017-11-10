<template>

    <modal :show="show"
           :modal-class="modalClass"
           :modal-style="modalStyle"
           :mask-style="maskStyle">

        <div>
            <div class="panel-heading">
                <h2 class="panel-title">Attachments for Sale # {{ item.id }}</h2>
            </div>
            <div class="panel-body modal-body">
                <div class="form-container">
                    <file-manager ref="fileManager"
                                  v-bind:options="fileManager"
                                  v-bind:delete-file="deleteFile"
                                  v-bind:files="item.order.files"
                                  v-bind:storable_id="item.orderId"
                                  v-bind:storable_type="'order'"></file-manager>
                </div>
            </div>
            <div class="panel-footer" style="text-align: center">
                <button type="button" class="btn btn-default" v-on:click="close">Close</button>
            </div>
        </div>

    </modal>

</template>

<script type="text/babel">
    import Modal from 'src/components/ui/Modal.vue'
    import FileManager from 'src/components/views/partials/FileManager.vue'

    export default {
        data() {
            return {
                modalClass: 'col-md-7 col-sm-9 col-xs-11',
                modalStyle: {
                    float: 'none',
                    padding: '0'
                },
                maskStyle: {
                    position: 'fixed'
                },
                fileManager: {
                    deleteUrl: '/api/files/'
                }
            }
        },
        components: {
            Modal,
            FileManager
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
            close() {
                this.$emit('close')
            },
            deleteFile(key) {
                this.$emit('file-removed')
                /*
                let filteredList = _.filter(this.selected.item.order.files, function (item) {
                    return (item.id !== key)
                }, this)
                let newItem = _.cloneDeep(this.selected.item)
                newItem.order.files = filteredList
                this.update(this.selected.index, newItem)
                this.select({
                    item: newItem,
                    mode: 'attachments',
                    index: this.selected.index
                })*/
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>