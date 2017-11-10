import {required, maxLength} from 'vuelidate/lib/validators'
import accepted from 'src/validators/accepted'

export default {
    buildingStyle: {required},
    buildingDimension: {required},
    confirmEmailing: {
        required,
        accepted
    },
    contact: {
        type: {required},
        time: {required}
    },
    currentRequiredCategories: {
        maxLength: maxLength(0)
    }
}
