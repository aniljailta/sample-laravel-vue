<template>

    <tr>
        <td class="text-left">
            <span v-if="location.location">{{ location.location.name }}</span>
        </td>
        <td class="text-left">
            <span>{{ filters.moment(location.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY') || '-' }}</span>
        </td>
        <td class="text-center">
            <div class="btn-group" v-if="editable">
                <button class="btn btn-default btn-xs"
                        title="Update"
                        v-on:click="openChangeLocationModal">
                    <i class="fa fa-pencil fa-fw"></i>
                </button>
                <button class="btn btn-default btn-xs"
                        title="Update"
                        v-on:click="openDeleteLocationModal">
                    <i class="fa fa-remove fa-fw"></i>
                </button>
            </div>
            <component v-if="changeLocationModal"
                       is="ChangeLocationModal"
                       transition="modal"
                       :item="location"
                       :params=" { mode: 'update' } "
                       :modal-title="'Update Building Location'"
                       v-on:close="closeChangeLocationModal"
                       v-on:saved="changeLocationCallback"
                       :show="changeLocationModal"></component>

            <component v-if="deleteLocationModal"
                       is="DeleteLocationModal"
                       transition="modal"
                       :item="location"
                       :modal-title="'Update Building Location #' + location.id"
                       v-on:close="closeDeleteLocationModal"
                       v-on:removed="deleteLocationCallback"
                       :show="deleteLocationModal"></component>
        </td>
    </tr>

</template>

<script type="text/babel">
    export default {
        data() {
            return {
                changeLocationModal: false,
                deleteLocationModal: false
            }
        },
        props: {
            location: {
                default() {
                    return {}
                }
            },
            editable: {
                default: false
            }
        },
        components: {
            ChangeLocationModal: function(resolve) {
                require(['../../building-locations/modals/ModalChange.vue'], resolve)
            },
            DeleteLocationModal: function(resolve) {
                require(['../../building-locations/modals/ModalDelete.vue'], resolve)
            }
        },
        computed: {},
        methods: {
            openChangeLocationModal() {
                this.changeLocationModal = true
            },
            openDeleteLocationModal() {
                this.deleteLocationModal = true
            },
            closeChangeLocationModal() {
                this.changeLocationModal = false
            },
            closeDeleteLocationModal() {
                this.deleteLocationModal = false
            },
            changeLocationCallback() {
                this.$parent.refresh()
            },
            deleteLocationCallback() {
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