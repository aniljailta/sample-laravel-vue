<template>

    <div>
        <modal v-bind:show="show"
               v-bind:modal-class="modalClass"
               v-bind:modal-style="modalStyle"
               v-bind:mask-style="maskStyle">

            <div v-if="item">
                <div class="panel-heading">
                    <h2 class="panel-title">{{ modalTitle }}</h2>
                </div>
                <div class="panel-body modal-body overlayable">
                    <div class="">
                        <data-process v-bind:with_loader="true" v-bind:process="process"></data-process>
                        <div v-if="!(process.running || process.success)">
                            <br>
                            <div class="modal-icon">
                                <i class="fa fa-question-circle"></i>
                            </div>
                            <div class="modal-text">
                                <h4>This action is permanent, do you want to continue?</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer text-center">
                    <button type="button" class="btn btn-default" v-on:click="close" v-bind:disabled="running">Close</button>
                    <button type="button"
                            class="btn btn-danger"
                            v-show="!process.success"
                            v-on:click="remove"
                            v-bind:disabled="running">
                        Delete
                    </button>
                </div>
            </div>

        </modal>
    </div>

</template>

<script type="text/babel">
    import Modal from 'src/components/ui/Modal.vue'
    import DataProcess from 'src/components/ui/DataProcess.vue'

    export default {
        data() {
            return {
                modalClass: 'modal-block-primary',
                modalStyle: {
                    float: 'none',
                    padding: '0',
                    display: 'inline-block'
                },
                maskStyle: {
                    position: 'fixed'
                },
                process: {
                    type: 'data',
                    text: 'Removing..',
                    running: false,
                    success: null,
                    error: null
                }
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
        created() {
            this.$on('item-removed', () => {
                this.$emit('removed')
            })
        },
        methods: {
            remove() {
                let self = this
                this.process.running = true
                this.process.error = null
                this.process.success = null
                this.removeItem({ item: this.item })
                        .then(msg => {
                            self.process.running = false
                            self.process.success = msg
                            self.$emit('item-removed')
                        })
                        .catch(msg => {
                            self.process.running = false
                            self.process.error = msg
                        })
            },
            clear() {
                this.process.error = null
                this.process.success = null
            },
            close() {
                this.clear()
                this.$emit('close')
            }
        },
        computed: {
            running() {
                return this.process.running
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>