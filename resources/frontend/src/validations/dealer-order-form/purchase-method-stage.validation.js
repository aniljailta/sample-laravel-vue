import {required, between} from 'vuelidate/lib/validators'

export default function () {
    let allRules = {
        paymentType: {required},
        amountReceived: {
            required,
            between: between(this.minAmountApplyToOrder, 10000000)
        }
    }

    if (this.paymentType === 'rto') {
        allRules.rtoTerm = {required}
    }

    return allRules
}
