<template>
    <div>
        <div class="item col-xs-4 col-md-3 " v-if="settings.include3d === 1">
            <div class="thumbnail">
                <button class="btn btn-default start-from-scratch-button" v-on:click="nextStep('3d-viewer')" :class="{'active': threedViewer}">
                    <i class="fa fa-cube fa-2x"></i>
                    <h4>
                       3D Shed Builder
                    </h4>
                </button>
            </div>
        </div>
        <div class="item col-xs-4 col-md-3">
            <div class="thumbnail">
                <button class="btn btn-default start-from-scratch-button" v-on:click="checkAction()">
                    <i class="fa fa-cogs fa-2x"></i>
                    <h4>
                        Shed Builder
                    </h4>
                </button>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
    /*global swal*/
    import {mapGetters, mapActions} from 'vuex'

    export default {
        data() {
            return {
            }
        },
        components: {
        },
        computed: {
            ...mapGetters({
                threedViewer: 'dealerOrderForm/order3dViewer',
                settings: 'dealerOrderForm/settings/settingsGlobal'
            })
        },
        methods: {
            ...mapActions({
                updateOrderBuilding: 'dealerOrderForm/updateOrderBuilding'
            }),
            nextStep(type) {
                if (!this.threedViewer) {
                    this.updateOrderBuilding({
                        buildingStyle: null,
                        buildingDimension: null,
                        buildingPackage: null,
                        customBuildOptions: []
                    })
                    this.$parent.$parent.change({}, type)
                }
               this.$emit('next-step')
            },
            checkAction() {
                if (this.threedViewer) {
                    swal({
                        title: 'Are you sure?',
                        text: 'Selecting this design option will delete your 3D shed configuration.',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55'
                    },
                    (isConfirm) => {
                        if (isConfirm) {
                            this.updateOrderBuilding({
                                buildingStyle: null,
                                buildingDimension: null,
                                buildingPackage: null,
                                customBuildOptions: [],
                                threedoptions: null,
                                saleType: 'custom-order'
                            })
                            this.$nextTick(() => {
                                this.$emit('next-step')
                            })
                        }
                    })
                } else {
                    this.$emit('next-step')
                }
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .item {
        padding: 0.3em 5px 0 5px;
    }

    .start-from-scratch-button {
        height: 100%;
        width: 100%;
        border-radius: 0px;
        white-space: normal;
    }

    div.thumbnail {
        text-align: center;
        margin-bottom: 0px;
        // padding: 0;
        padding: 3px;
        -webkit-border-radius: 0px;
        -moz-border-radius: 0px;
        border-radius: 0px;

        /* trying to set the same height for all elements */
        height: 200px;
        max-width: 200px;
    }
</style>