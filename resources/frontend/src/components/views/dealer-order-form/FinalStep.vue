<template>

    <div class="col-xs-12 plr-none">
        <div class="row">
            <div class="col-md-12">

                <div class="list-group overlayable">
                    <div class="list-group-item item-heading">
                        <div class="col-xs-2 plr-none text-left">
                            <button class="btn btn-default"
                                    v-on:click.prevent="goToStep('previous')"><i class="fa fa-arrow-left fa-fw"></i> Previous
                            </button>
                        </div>
                        <div class="col-xs-8 plr-none text-center">
                            <h4>Final</h4>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="list-group-item sub-step">
                        <template v-if="deliveryRemarks.mustCrossNeighboringProperty">
                            <!-- wrap neighborReleaseAttachment for reset cache of data and components  -->
                            <neighbor-release-file v-for="(nra, nraIndex) in [neighborReleaseAttachment]"
                                                   v-if="nra.files.length > 0 || nra.canGenerate || nra.canUpload"
                                                   :key="nra.key"
                                                   ref="neighborReleaseFile"
                                                   class="col-xs-12 col-sm-3 neighbor-release__compenent"
                                                   :attachment="nra"
                                                   :storableId="orderCurrent.id">
                            </neighbor-release-file>
                        </template>
                    </div>
                </div>

            </div>
        </div>

    </div>

</template>

<script type="text/babel">
    import {mapGetters} from 'vuex'
    import NeighborReleaseFile from './final-step/NeighborReleaseFile.vue'

    export default {
        data() {
            return {}
        },
        components: {NeighborReleaseFile},
        created() {},
        computed: {
            ...mapGetters({
                orderCurrent: 'dealerOrderForm/orderCurrent',
                neighborReleaseAttachment: 'dealerOrderForm/neighborReleaseAttachment',
                deliveryRemarks: 'dealerOrderForm/orderDeliveryRemarks'
            })
        },
        methods: {
            $validate() {
                return true
            },
            goToStep(direction) {
                this.$emit('go-to-step', {step: direction})
            },
            nextStep(direction) {
                return this.$parent.nextStep(direction)
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .neighbor-release__compenent {
        float: none;
        margin: 0 auto;
    }
</style>