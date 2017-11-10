import {required, email} from 'vuelidate/lib/validators'

import name from 'src/validators/name'

export default function () {
    let customer = {
        firstName: {name},
        lastName: {name},
        email: {email}
    }

    if (_.isEmpty(this.customer.firstName) && _.isEmpty(this.customer.lastName)) {
        customer.email.required = required
    }

    return {customer}
}
