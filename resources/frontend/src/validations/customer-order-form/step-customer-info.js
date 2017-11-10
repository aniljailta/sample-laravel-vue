import {required, email} from 'vuelidate/lib/validators'

import name from 'src/validators/name'
import address from 'src/validators/address'
import zip from 'src/validators/zip'
import geo from 'src/validators/geo'
import phone from 'src/validators/phone'

export default function () {
    let customer = {
        firstName: {required, name},
        lastName: {required, name},
        email: {required, email},
        phoneNumber: {required, phone},
        address: {required, address},
        zip: {required, zip},
        city: {required, geo},
        state: {required, geo},
        // dynamic below
        buildingLocationAddress: {address},
        buildingLocationCity: {geo},
        buildingLocationState: {geo},
        buildingLocationZip: {zip}
    }

    if (this.customer.buildingInSameAddress === false) {
        customer.buildingLocationAddress.required = required
        customer.buildingLocationCity.required = required
        customer.buildingLocationState.required = required
        customer.buildingLocationZip.required = required
    }

    return {customer}
}
