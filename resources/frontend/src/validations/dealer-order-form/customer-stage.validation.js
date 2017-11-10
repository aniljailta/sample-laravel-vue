import {required, email} from 'vuelidate/lib/validators'

import name from 'src/validators/name'
import address from 'src/validators/address'
import zip from 'src/validators/zip'
import geo from 'src/validators/geo'
import phone from 'src/validators/phone'

export default function () {
    let customer = {
        learningAboutUs: {},
        learningAboutUsOther: {},
        firstName: {required, name},
        lastName: {required, name},
        email: {email},
        phoneNumber: {phone},
        address: {address},
        zip: {zip},
        city: {geo},
        state: {geo},
        // dynamic below
        buildingLocationAddress: {address},
        buildingLocationCity: {geo},
        buildingLocationState: {geo},
        buildingLocationZip: {zip}
    }

    if (this.orderStateMode === 'submit') {
        customer.learningAboutUs.required = required
        customer.email.required = required
        customer.phoneNumber.required = required
        customer.address.required = required
        customer.zip.required = required
        customer.city.required = required
        customer.state.required = required

        if (this.customer.learningAboutUs === 'other') {
            customer.learningAboutUsOther.required = required
        }

        if (this.customer.buildingInSameAddress === false) {
            customer.buildingLocationAddress.required = required
            customer.buildingLocationCity.required = required
            customer.buildingLocationState.required = required
            customer.buildingLocationZip.required = required
        }
    }

    return {customer}
}
