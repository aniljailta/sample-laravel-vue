<template>
    <section class="panel sub-panel">
        <header class="panel-heading">
            <h4 class="panel-title">Location</h4>
        </header>

        <div class="panel-body">
            <div class="row">
                <label class="col-sm-2 control-label">Current Location</label>
                <div class="col-sm-8">
                    <div class="row">
                        <building-location-label :location="location.location" v-if="location.location"></building-location-label>
                    </div>

                    <div class="row mt-xs">
                        Changed on {{ filters.moment(location.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') || '-' }}
                        <span v-if="location.user">by {{ location.user.fullName }}</span>
                    </div>

                    <div class="row mt-xs">
                        <button class="btn btn-default btn-sm"
                                title="Change"
                                v-on:click="openChangeLocationModal">
                            Change
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <component v-if="changeLocationModal"
                   is="ChangeLocationModal"
                   transition="modal"
                   :item="location"
                   :params=" { mode: 'create' } "
                   :modal-title="'Change Building Location'"
                   v-on:close="closeChangeLocationModal"
                   v-on:saved="changeLocationCallback"
                   :show="changeLocationModal"></component>
    </section>
</template>

<script type="text/babel">
    import BuildingLocationLabel from 'src/components/views/partials/BuildingLocationLabel.vue'

    export default {
        data() {
            return {
                changeLocationModal: false
            }
        },
        components: {
            BuildingLocationLabel,
            ChangeLocationModal: function(resolve) {
                require(['../building-locations/modals/ModalChange.vue'], resolve)
            }
        },
        props: {
            location: {
                default() {
                    return {}
                }
            }
        },
        computed: {},
        methods: {
            openChangeLocationModal() {
                this.changeLocationModal = true
            },
            closeChangeLocationModal() {
                this.changeLocationModal = false
            },
            changeLocationCallback() {
                this.$parent.refresh('building-locations')
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>