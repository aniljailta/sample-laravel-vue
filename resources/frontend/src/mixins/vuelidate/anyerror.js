import utils from './_utils'

// https://github.com/monterail/vuelidate/issues/84
export default {
    computed: {
        $anyerror() {
            if (!this.$v) return false
            return utils.deepCheck(this.$v, {$error: true})
        }
    }
}
