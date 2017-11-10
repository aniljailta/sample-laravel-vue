import {required} from 'vuelidate/lib/validators'

export default function () {
    let settings = {
        estimatedDeliveryPeriod: {required},
        leadTime: {required},
        initialContactEligibility: {required},
        changeOrderFee: {},
        footnote: {}
    }

    return {settings}
}
