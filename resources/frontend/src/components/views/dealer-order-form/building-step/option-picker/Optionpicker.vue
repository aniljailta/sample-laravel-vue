<script type="text/babel">
    import OptionPicker from 'components/ui/Optionpicker/Optionpicker.vue'
    // import {mapGetters} from 'vuex'

    export default {
        name: 'option-picker',
        extends: OptionPicker,
        methods: {
            addCustomOption(option) {
                this.$parent.$refs.optionManager.$emit('option-manager-add-option', option)
            }
        }
    }
</script>

<style type="text/css">
</style>