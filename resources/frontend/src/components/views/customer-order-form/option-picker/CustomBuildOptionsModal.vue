<template>

    <div>
        <modal v-bind:show="show"
               v-bind:draggable="false"
               v-bind:modal-class="modalClass"
               v-bind:modal-style="modalStyle"
               v-bind:close-modal-method="close">

            <div>
                <div class="panel-heading">
                    <h2 class="panel-title">Custom Build Options</h2>
                </div>
                <div class="panel-body modal-body">
                    <custom-build-options ref="customBuildOptions"
                                          v-on:select-building-package="selectBuildingPackage"
                                          v-bind:building-package="buildingPackage"
                                          v-bind:building-dimension="buildingDimension"
                                          v-bind:custom-build-options="customBuildOptions"
                                          v-bind:options="options"
                                          v-bind:option-categories="optionCategories">
                    </custom-build-options>
                </div>
                <div class="panel-footer" style="text-align: center">
                    <button type="button" class="btn btn-default" v-on:click="close">Close</button>
                    <button type="button" class="btn btn-warning" v-on:click="apply">Apply</button>
                </div>
            </div>

        </modal>
    </div>

</template>

<script type="text/babel">
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
        props: {
            options: {
                default() { return {} }
            },
            buildingPackage: {
                default() { return {} }
            },
            buildingDimension: {
                default() { return {} }
            },
            customBuildOptions: {
                default() { return {} }
            },
            optionCategories: {
                default() { return {} }
            }
        },
        created() {
            this.$on('open-modal', this.openModalMethod)
            this.$on('close-modal', this.closeModalMethod)
        },
        methods: {
            selectBuildingPackage(buildingPackage) {
                this.$emit('select-building-package', buildingPackage)
            },
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
                        this.$emit('update-custom-build-options', this.$refs.customBuildOptions.buildingOptions)
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