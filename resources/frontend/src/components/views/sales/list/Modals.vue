<template>

    <div>
        <modal-update v-if="modal !== null && modal.mode === 'edit' " :show="true"
                      v-on:close="closeModalUpdate"
                      v-on:saved="fetchData"
                      :item="modal !== null && modal.mode === 'edit' ? modal.item : null"></modal-update>

        <modal-attachments v-if="modal !== null && modal.mode === 'attachments' " :show="true"
                           v-on:close="closeModalAttachments"
                           v-on:file-removed="fetchData"
                           :item="modal !== null && modal.mode === 'attachments' ? modal.item : null">
        </modal-attachments>

        <modal-update-order v-if="modal !== null && modal.mode === 'editOrder' " :show="true"
                            v-on:close="closeModalUpdate"
                            v-on:saved="fetchData"
                            :item="modal !== null && modal.mode === 'editOrder' ? modal.item : null">
        </modal-update-order>
    </div>

</template>

<script type="text/babel">
    // base
    import baseListModals from 'src/components/views/_base/ListItems/Modals.vue'
    // modals
    import ModalUpdate from '../modals/ModalUpdate.vue'
    import ModalAttachments from '../modals/ModalAttachments.vue'
    import ModalUpdateOrder from '../../orders/modals/ModalUpdate.vue'

    export default {
        extends: baseListModals,
        components: {
            ModalUpdate,
            ModalAttachments,
            ModalUpdateOrder
        },
        data() {
            return {}
        },
        methods: {
            closeModalAttachments() {
                this.$emit('change-entry', null)
            }
        }
    }
</script>