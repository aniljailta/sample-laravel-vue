<template>
    <section class="panel sub-panel">
        <header class="panel-heading">
            <h4 class="panel-title">Status</h4>
        </header>

        <div class="panel-body">
            <div class="row">
                <label class="col-sm-2 control-label">Current Status</label>
                <div class="col-sm-8">
                    <div class="row">
                        <building-status-label :status="status.buildingStatus" v-if="status.buildingStatus"></building-status-label>
                    </div>

                    <div class="row mt-xs">
                        Changed on {{ filters.moment(status.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') || '-' }}
                        <span v-if="status.user">by {{ status.user.fullName }}</span>
                    </div>

                    <div class="row mt-xs">
                        <button class="btn btn-default btn-sm"
                                title="Change"
                                v-on:click="openChangeStatusModal">
                            Change
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <component v-if="changeStatusModal"
                   is="ChangeStatusModal"
                   transition="modal"
                   :item="status"
                   :params=" { mode: 'create' } "
                   :modal-title="'Change Building Status'"
                   v-on:close="closeChangeStatusModal"
                   v-on:saved="changeStatusCallback"
                   :show="changeStatusModal"></component>
    </section>
</template>

<script type="text/babel">
    import BuildingStatusLabel from 'src/components/views/partials/BuildingStatusLabel.vue'

    export default {
        data() {
            return {
                changeStatusModal: false
            }
        },
        components: {
            BuildingStatusLabel,
            ChangeStatusModal: function(resolve) {
                require(['../building-history/modals/ModalChange.vue'], resolve)
            }
        },
        props: {
            status: {
                default() {
                    return {}
                }
            }
        },
        computed: {},
        methods: {
            openChangeStatusModal() {
                this.changeStatusModal = true
            },
            closeChangeStatusModal() {
                this.changeStatusModal = false
            },
            changeStatusCallback() {
                this.$parent.refresh('building-history')
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>