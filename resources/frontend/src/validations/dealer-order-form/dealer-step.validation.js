import {required} from 'vuelidate/lib/validators'

import name from 'src/validators/name'

export default {
    dealer: {
        id: {required},
        salesPerson: {required, name}
    }
}
