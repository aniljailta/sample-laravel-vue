import {required, email, url, numeric} from 'vuelidate/lib/validators'

import name from 'src/validators/name'
import address from 'src/validators/address'
import zip from 'src/validators/zip'
import geo from 'src/validators/geo'
import phone from 'src/validators/phone'

export default function () {
    let settings = {
        name: {required, name},
        address: {required, address},
        city: {required, geo},
        state: {required, geo},
        zip: {required, zip},
        phone: {required, phone},
        email: {required, email},

        timeZone: {},
        perPage: {},
        mailHost: {required},
        mailPort: {required, numeric},
        mailUsername: {required},
        mailFrom: {required, email},
        mailPassword: {required},
        mailEncryption: {},

        website: {url},
        facebook: {},
        instagram: {},
        pinterest: {},
        gplus: {},
        logo: {required}
    }

    return {settings}
}
