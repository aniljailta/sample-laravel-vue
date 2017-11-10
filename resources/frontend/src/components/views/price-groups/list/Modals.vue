<template>

    <div>
        <modal-create v-if="modal !== null && modal.mode === 'create' " :show="true"
                      v-on:close="closeModalCreate"
                      v-on:saved="fetchData"
                      :item="modal !== null && modal.mode === 'create' ? modal.item : null"></modal-create>

        <modal-update v-if="modal !== null && modal.mode === 'edit' " :show="true"
                      v-on:close="closeModalUpdate"
                      v-on:saved="fetchData"
                      :item="modal !== null && modal.mode === 'edit' ? modal.item : null"></modal-update>

        <modal-delete v-if="modal !== null && modal.mode === 'delete' " :show="true"
                      v-on:close="closeModalDelete"
                      v-on:removed="fetchData"
                      :item="modal !== null && modal.mode === 'delete' ? modal.item : null"></modal-delete>

        <modal-import v-if="modal !== null && modal.mode === 'import' " :show="true"
                      v-on:close="closeModalImport"
                      v-on:saved="fetchData"
                      :item="modal !== null && modal.mode === 'import' ? modal.item : null"></modal-import>

        <modal-price-list v-if="modal !== null && modal.mode === 'price_list' " :show="true"
                          v-on:close="closeModalPriceList"
                          v-on:saved="fetchData"
                          :item="modal !== null && modal.mode === 'price_list' ? modal.item : null"></modal-price-list>

        <modal-publish v-if="modal !== null && modal.mode === 'publish' " :show="true"
                       v-on:close="closeModalPublish"
                       v-on:saved="fetchData"
                       :item="modal !== null && modal.mode === 'publish' ? modal.item : null"></modal-publish>
    </div>

</template>

<script type="text/babel">
    // base
    import baseListModals from 'src/components/views/_base/ListItems/Modals.vue'
    // modals
    import ModalCreate from '../modals/ModalCreate.vue'
    import ModalUpdate from '../modals/ModalUpdate.vue'
    import ModalDelete from '../modals/ModalDelete.vue'
    import ModalImport from '../modals/ModalImport.vue'
    import ModalPriceList from '../modals/ModalPriceList.vue'
    import ModalPublish from '../modals/ModalPublish.vue'

    export default {
        extends: baseListModals,
        components: {
            ModalCreate,
            ModalUpdate,
            ModalDelete,
            ModalImport,
            ModalPriceList,
            ModalPublish
        },
        data() {
            return {}
        },
        methods: {
            closeModalPriceList() {
                this.$emit('change-entry', null)
            },
            closeModalPublish() {
                this.$emit('change-entry', null)
            },
            closeModalImport() {
                this.$emit('change-entry', null)
            }
        }
    }
</script>