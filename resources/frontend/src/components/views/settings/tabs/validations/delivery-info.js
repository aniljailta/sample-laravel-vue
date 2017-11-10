import {required, email} from 'vuelidate/lib/validators'
import phone from 'src/validators/phone'
import name from 'src/validators/name'

export default function () {
    let settings = {
        deliveryDispatch: {required},
        deliveryContactName: {name},
        deliveryContactPhone: {phone},
        deliveryContactEmail: {email}
    }

    if (this.settings.deliveryDispatch === 'dispatch') {
        settings.deliveryContactName.required = required
        settings.deliveryContactPhone.required = required
        settings.deliveryContactEmail.required = required
    }

    return {settings}
}
