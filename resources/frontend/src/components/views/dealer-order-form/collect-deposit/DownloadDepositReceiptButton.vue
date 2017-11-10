<script type="text/babel">
    import DownloadDocumentButton from '../partial/DownloadDocumentButton.vue'

    export default {
        extends: DownloadDocumentButton,
        data() {
            return {
                generating: false,
                url: null
            }
        },
        props: {
            id: {
                type: String,
                default: null
            },
            categoryId: {
                type: String,
                default: null
            },
            downloadLabel: {
                type: String,
                default: null
            }
        },
        methods: {
            download() {
                let self = this
                this.$bus.$emit('dofGenerateDocument', {
                    validate(rootVm) {
                        return self.$parent.$validate({$touch: true})
                    },
                    before() {
                        self.generating = true
                    },
                    stop() {
                        self.generating = false
                    },
                    success: self.onSuccess
                })
            }
        }
    }
</script>

<style type="text/css">

</style>