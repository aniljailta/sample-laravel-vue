<template>

    <li class="list-group-item list-item">
        <table>
            <tr>
                <td class="list-left">
                    <img class="image-preview" :src="image.publicPath">
                </td>
                <td class="list-desc">
                    <h5>
                        <strong>{{ item.name }}</strong>
                        <span class="label label-success">{{ filters.money(item.totalPrice) }}</span>
                    </h5>
                    <h6><strong>{{ item.buildingModel.name }}</strong> <span class="label label-success">{{ filters.money(item.buildingModel.shellPrice) }}</span></h6>
                    <h6><span>{{ item.description }}</span></h6>
                </td>
                <td class="text-right">
                    <button type="button"
                            class="btn btn-sm btn-default"
                            v-on:click="select(item)">
                        <i class="fa fa-cogs" aria-hidden="true"></i> Select
                    </button>
                    <button class="btn btn-sm btn-default"
                            style="margin: 0.5em 0 0.5em 0"
                            v-on:click="toggleOptions" v-bind:class="{ 'active': showOptions }">
                        Options ({{ countOptions }}) <i class="fa fa-angle-down" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>

            <tr is="building-package-options" v-if="item.options && showOptions" :options="item.options"></tr>
        </table>
    </li>

</template>

<script type="text/babel">
    /*global swal*/
    import Popover from 'uiv/src/components/popover/Popover.vue'
    import BuildingPackageOptions from './BuildingPackageOptions.vue'
    import {mapGetters, mapActions} from 'vuex'

    export default {
        data() {
            return {
                showOptions: false
            }
        },
        components: {
            Popover,
            BuildingPackageOptions
        },
        props: {
            item: {
                default() {
                    return {}
                }
            }
        },
        computed: {
            ...mapGetters({
                threedViewer: 'dealerOrderForm/order3dViewer'
            }),
            countOptions() {
                return _.size(this.item.options)
            },
            image() {
                let image = {}
                if (_.isArray(this.item.files) && this.item.files.length > 0) {
                    image = _.cloneDeep(_.last(this.item.files))
                    return image
                }

                return {
                    publicPath: this.item.buildingModel.style.image ? this.item.buildingModel.style.image.publicPath : null,
                    width: 60,
                    height: 60
                }
            }
        },
        methods: {
            ...mapActions({
                updateOrderBuilding: 'dealerOrderForm/updateOrderBuilding'
            }),
            select(item) {
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
                                    threedoptions: null
                                })
                                this.$emit('select', item)
                            }
                        })
                } else {
                    this.$emit('select', item)
                }
            },
            toggleOptions() {
                this.showOptions = !(this.showOptions)
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

    .list-item {
        /* position: static;
        overflow: auto; */
        vertical-align: top;
        width: 100%;

        .list-left {
            padding-right: 0.5em;
            position: relative;

            .image-preview {
                height: auto;
                width: 5em;
                padding: 3px;
            }
        }
        .list-desc {
            vertical-align: top;
            width: 100%;
        }
        .list-price {
            float: right;
            margin-bottom: 0.5em;
        }
        .thumbnail {
            width: 100%;
            height: 100%;
        }
    }

</style>