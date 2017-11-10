<template>

    <tr>
        <td class="text-left">
            <span v-if="status.buildingStatus">{{ status.buildingStatus.name }}</span>
        </td>
        <td class="text-left">
            <span>{{ filters.moment(status.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY') || '-' }}</span>
        </td>
        <td class="text-center">
            <span v-if="status.expense && status.expense.bill">{{ status.expense.bill.number }}</span>
        </td>
        <td class="text-center">
            <div class="btn-group" v-if="editable">
                <button class="btn btn-default btn-xs"
                        title="Update"
                        v-on:click="openChangeStatusModal">
                    <i class="fa fa-pencil fa-fw"></i>
                </button>
                <button class="btn btn-default btn-xs"
                        title="Update"
                        v-on:click="openDeleteStatusModal">
                    <i class="fa fa-remove fa-fw"></i>
                </button>
            </div>
            <component v-if="changeStatusModal"
                       is="ChangeStatusModal"
                       transition="modal"
                       :item="status"
                       :params=" { mode: 'update' } "
                       :modal-title="'Update Building Status'"
                       v-on:close="closeChangeStatusModal"
                       v-on:saved="changeStatusCallback"
                       :show="changeStatusModal"></component>

            <component v-if="deleteStatusModal"
                       is="DeleteStatusModal"
                       transition="modal"
                       :item="status"
                       :modal-title="'Update Building Status #' + status.id"
                       v-on:close="closeDeleteStatusModal"
                       v-on:removed="deleteStatusCallback"
                       :show="deleteStatusModal"></component>
        </td>
    </tr>

</template>

<script type="text/babel">
    export default {
        data() {
            return {
                changeStatusModal: false,
                deleteStatusModal: false
            }
        },
        props: {
            status: {
                default() {
                    return {}
                }
            },
            editable: {
                default: false
            }
        },
        components: {
            ChangeStatusModal: function(resolve) {
                require(['../../building-history/modals/ModalChange.vue'], resolve)
            },
            DeleteStatusModal: function(resolve) {
                require(['../../building-history/modals/ModalDelete.vue'], resolve)
            }
        },
        computed: {},
        methods: {
            openChangeStatusModal() {
                this.changeStatusModal = true
            },
            openDeleteStatusModal() {
                this.deleteStatusModal = true
            },
            closeChangeStatusModal() {
                this.changeStatusModal = false
            },
            closeDeleteStatusModal() {
                this.deleteStatusModal = false
            },
            changeStatusCallback() {
                this.$parent.refresh()
            },
            deleteStatusCallback() {
                this.$parent.refresh()
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
    #sn {
        span {
            font-size: 100%;
        }

        a {
            font-size: 100%;
            color: white;
        }
    }
</style>