import {required} from 'vuelidate/lib/validators'

export default function () {
    let allRules = {
        // inital step
        paymentMethod: {required},
        transactionId: {}
    }

    if (this.paymentMethod === 'credit_card' || this.paymentMethod === 'check') {
        allRules.transactionId.required = required
    }

    return allRules
}
