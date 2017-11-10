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

        <modal-duplicate v-if="modal !== null && modal.mode === 'duplicate' " :show="true"
                         v-on:close="closeModalUpdate"
                         v-on:saved="fetchData"
                         :item="modal !== null && modal.mode === 'duplicate' ? modal.item : null"></modal-duplicate>

        <modal-return v-if="modal !== null && modal.mode === 'return' " :show="true"
                      v-on:close="closeModalUpdate"
                      v-on:saved="fetchData"
                      :item="modal !== null && modal.mode === 'return' ? modal.item : null"></modal-return>

        <modal-delete v-if="modal !== null && modal.mode === 'delete' " :show="true"
                      v-on:close="closeModalDelete"
                      v-on:removed="fetchData"
                      :item="modal !== null && modal.mode === 'delete' ? modal.item : null"></modal-delete>

        <modal-update-order v-if="modal !== null && modal.mode === 'editOrder' " :show="true"
                            v-on:close="closeModalUpdate"
                            v-on:saved="fetchData"
                            :item="modal !== null && modal.mode === 'editOrder' ? modal.item : null"></modal-update-order>

        <modal-update-sale v-if="modal !== null && modal.mode === 'editSale' " :show="true"
                           v-on:close="closeModalUpdate"
                           v-on:saved="fetchData"
                           :item="modal !== null && modal.mode === 'editSale' ? modal.item : null"></modal-update-sale>
    </div>

</template>

<script type="text/babel">
    // base
    import baseListModals from 'src/components/views/_base/ListItems/Modals.vue'
    // modals
    import ModalCreate from '../modals/ModalCreate.vue'
    import ModalUpdate from '../modals/ModalUpdate.vue'
    import ModalDelete from '../modals/ModalDelete.vue'
    import ModalDuplicate from '../modals/ModalDuplicate.vue'
    import ModalReturn from '../modals/ModalReturn.vue'

    // order modals
    import ModalUpdateOrder from '../../orders/modals/ModalUpdate.vue'
    import ModalAttachments from '../../orders/modals/ModalAttachments.vue'

    // sale modals
    import ModalUpdateSale from '../../sales/modals/ModalUpdate.vue'

    export default {
        extends: baseListModals,
        components: {
            ModalCreate,
            ModalUpdate,
            ModalDuplicate,
            ModalReturn,
            ModalDelete,
            ModalUpdateOrder,
            ModalAttachments,
            ModalUpdateSale
        },
        data() {
            return {}
        },
        methods: {
        }
    }
</script>