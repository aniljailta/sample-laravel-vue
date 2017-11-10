<template>

    <component :is="currentModal">

    </component>

</template>

<script type="text/babel">
    import {mapGetters} from 'vuex'

    import EsignEmbed from './EsignEmbed.vue'
    import EsignEmail from './EsignEmail.vue'

    export default {
        components: {
            EsignEmbed,
            EsignEmail
        },
        data() {
            return {}
        },
        computed: {
            ...mapGetters({
                getEsignEmbed: 'dealerOrderForm/esign/getEsignEmbed',
                getEsignEmail: 'dealerOrderForm/esign/getEsignEmail'
            }),
            currentModal() {
                if (this.getEsignEmbed.show) return 'EsignEmbed'
                if (this.getEsignEmail.show) return 'EsignEmail'

                return false
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
    .dof-esigns-modal {
        .nav-tabs>li>a {
            border-radius: 0px;
        }

        .nav-tabs {
            position: absolute;
            width: 100%;
        }

        .tab-content {
            height: 100%;
            padding: 3em 0 0em 0;
        }

        .tab-pane {
            height: 100%;
        }
    }
</style>