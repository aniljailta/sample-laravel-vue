import {required, email} from 'vuelidate/lib/validators'

import name from 'src/validators/name'
import phone from 'src/validators/phone'

export default function () {
    let settings = {
        firstName: {required, name},
        lastName: {required, name},
        title: {required, name},
        email: {required, email},
        phone: {required, phone}
    }

    return {settings}
}
