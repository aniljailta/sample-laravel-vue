<template>

    <div>
        <modal :show="show"
               :draggable="false"
               :modal-class="modalClass"
               :modal-style="modalStyle">
            <div>
                <div class="panel-heading">
                    <h2 class="panel-title">Custom Build Options</h2>
                </div>
                <div class="panel-body modal-body">
                    <custom-build-options ref="customBuildOptions"></custom-build-options>
                </div>
                <div class="panel-footer" style="text-align: center">
                    <button type="button" class="btn btn-default" @click="close">Close</button>
                    <button type="button" class="btn btn-warning" @click="apply">Apply</button>
                </div>
            </div>

        </modal>
    </div>

</template>

<script type="text/babel">
    import {mapActions} from 'vuex'

    import Modal from 'src/components/ui/Modal.vue'
    import CustomBuildOptions from './CustomBuildOptions.vue'

    export default {
        data() {
            return {
                show: false,
                modalClass: 'col-md-8 col-sm-9 col-xs-11',
                modalStyle: {
                    padding: '0',
                    float: 'none'
                }
            }
        },
        components: {
            Modal,
            CustomBuildOptions
        },
        created() {
            this.$on('open-modal', this.openModalMethod)
            this.$on('close-modal', this.closeModalMethod)
        },
        methods: {
            ...mapActions({
                updateOrderBuilding: 'dealerOrderForm/updateOrderBuilding'
            }),
            openModalMethod() {
                this.show = true
            },
            close() {
                this.show = false
            },
            apply() {
                let valid = this.validate()
                if (valid) {
                    new Promise(resolve => {
                        this.updateOrderBuilding({
                            'customBuildOptions': this.$refs.customBuildOptions.buildingOptions,
                            threedoptions: null
                        })
                        resolve()
                    }).then(() => {
                        this.close()
                    })
                }
            },
            validate() {
                return this.$refs.customBuildOptions.validate()
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

</style>