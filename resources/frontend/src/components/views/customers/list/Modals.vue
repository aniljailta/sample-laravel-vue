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
    </div>

</template>

<script type="text/babel">
    // base
    import baseListModals from 'src/components/views/_base/ListItems/Modals.vue'
    // modals
    import ModalCreate from '../modals/ModalCreate.vue'
    import ModalUpdate from '../modals/ModalUpdate.vue'
    import ModalDelete from '../modals/ModalDelete.vue'

    export default {
        extends: baseListModals,
        components: {
            ModalCreate,
            ModalUpdate,
            ModalDelete
        },
        data() {
            return {}
        },
        methods: {
        }
    }
</script>