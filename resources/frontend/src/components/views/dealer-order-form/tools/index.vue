<template>

    <modal :show="currentModalTool !== false"
           :modal-class="modalClass"
           :modal-style="modalStyle"
           :close-modal-method="closeModalMethod">
        <component :is="currentModalTool">

        </component>
    </modal>

</template>

<script type="text/babel">
    import {mapActions, mapGetters} from 'vuex'

    import Modal from 'src/components/ui/Modal.vue'
    import LoadForm from './LoadForm.vue'
    import SaveForm from './SaveForm.vue'
    import InventoryForm from './InventoryForm.vue'
    import CancellationRequestForm from './CancellationRequestForm.vue'
    import ModalChangeOrder from './ModalChangeOrder.vue'

    export default {
        data() {
          return {
              modalClass: 'col-md-4 col-sm-6 col-xs-11',
              modalStyle: {
                  float: 'none',
                  padding: '0'
              }
          }
        },
        components: {
            Modal,
            LoadForm,
            SaveForm,
            ModalChangeOrder,
            InventoryForm,
            CancellationRequestForm
        },
        computed: {
            ...mapGetters({
                uiToolsShowLoadForm: 'dealerOrderForm/uiTools/getUiToolsShowLoadForm',
                uiToolsShowSaveForm: 'dealerOrderForm/uiTools/getUiToolsShowSaveForm',
                uiToolsShowInventoryForm: 'dealerOrderForm/uiTools/getUiToolsShowInventoryForm',
                uiToolsShowCancellationRequestForm: 'dealerOrderForm/uiTools/getUiToolsShowCancellationRequestForm',
                showChangeOrderModal: 'dealerOrderForm/uiTools/showChangeOrderModal'
            }),
            currentModalTool() {
                if (this.uiToolsShowLoadForm) return 'LoadForm'
                if (this.uiToolsShowSaveForm) return 'SaveForm'
                if (this.uiToolsShowInventoryForm) return 'InventoryForm'
                if (this.uiToolsShowCancellationRequestForm) return 'CancellationRequestForm'
                if (this.showChangeOrderModal) return 'ModalChangeOrder'

                return false
            }
        },
        methods: {
            ...mapActions({
                uiToolsHideLoadForm: 'dealerOrderForm/uiTools/uiToolsHideLoadForm',
                uiToolsHideSaveForm: 'dealerOrderForm/uiTools/uiToolsHideSaveForm',
                uiToolsSetStateInventoryForm: 'dealerOrderForm/uiTools/uiToolsSetStateInventoryForm',
                uiToolsHideCancellationRequestForm: 'dealerOrderForm/uiTools/uiToolsHideCancellationRequestForm'
            }),
            closeModalMethod() {
                if (this.currentModalTool === 'LoadForm') this.uiToolsHideLoadForm()
                if (this.currentModalTool === 'SaveForm') this.uiToolsHideSaveForm()
                if (this.currentModalTool === 'CancellationRequestForm') this.uiToolsHideCancellationRequestForm()
                if (this.currentModalTool === 'InventoryForm') {
                    this.uiToolsSetStateInventoryForm({'show': false})
                }
            }
        }
    }
</script>

<style type="text/css">

</style>

<style type="text/css" lang="scss" rel="stylesheet/scss">
    .customer-panel {
        .panel-heading {
            padding: 0.7em;
        }
    }
</style>