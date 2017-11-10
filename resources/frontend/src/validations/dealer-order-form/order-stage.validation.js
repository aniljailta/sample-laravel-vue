import {required} from 'vuelidate/lib/validators'
import date from 'src/validators/date'

export default function () {
    let allRules = {
        orderDate: {required, date}
    }

    return allRules
}
