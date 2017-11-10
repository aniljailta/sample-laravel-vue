import {required, email, numeric} from 'vuelidate/lib/validators'

import name from 'src/validators/name'
import bname from 'src/validators/bname'
import address from 'src/validators/address'
import zip from 'src/validators/zip'
import geo from 'src/validators/geo'
import phone from 'src/validators/phone'
// import customerRules from './customer-stage.validation'

export default function () {
    let renterData = {
        coRenterFirstName: {name},
        coRenterLastName: {name},
        propertyOwnership: {},
        landlordFullName: {name},
        landlordPhoneNumber: {phone},
        // textAllowed1: 'no',
        cellPhoneNumber2: {phone},
        // textAllowed2: 'no', // not reqired
        homePhoneNumber: {phone},
        // emailInsteadOfMail: true, // not required
        renterEmployer: {bname},
        renterEmployerPhoneNumber: {phone},
        renterEmployerPhoneExtension: {numeric},
        renterSupervisor: {name},
        renterSupervisorOccupation: {name},

        coRenterEmployer: {bname},
        coRenterEmployerPhoneNumber: {phone},
        coRenterEmployerPhoneExtension: {numeric},
        coRenterSupervisor: {name},
        coRenterSupervisorOccupation: {name},

        reference1Name: {name},
        reference1Relationship: {name},
        reference1PhoneNumber: {phone},
        reference1Address: {address},
        reference1City: {geo},
        reference1State: {geo},
        reference1Zip: {zip},

        reference2Name: {name},
        reference2Relationship: {name},
        reference2PhoneNumber: {phone},
        reference2Address: {address},
        reference2City: {geo},
        reference2State: {geo},
        reference2Zip: {zip}
    }

    let customer = {
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
        customer.email.required = required
        customer.phoneNumber.required = required
        customer.address.required = required
        customer.zip.required = required
        customer.city.required = required
        customer.state.required = required

        if (this.customer.buildingInSameAddress === false) {
            customer.buildingLocationAddress.required = required
            customer.buildingLocationCity.required = required
            customer.buildingLocationState.required = required
            customer.buildingLocationZip.required = required
        }
    }

    if (this.orderStateMode === 'submit') {
        renterData.propertyOwnership.required = required

        renterData.renterEmployer.required = required
        renterData.renterEmployerPhoneNumber.required = required
        renterData.renterSupervisor.required = required
        renterData.renterSupervisorOccupation.required = required

        renterData.reference1Name.required = required
        renterData.reference1Relationship.required = required
        renterData.reference1PhoneNumber.required = required
        renterData.reference1Address.required = required
        renterData.reference1City.required = required
        renterData.reference1State.required = required
        renterData.reference1Zip.required = required

        renterData.reference2Name.required = required
        renterData.reference2Relationship.required = required
        renterData.reference2PhoneNumber.required = required
        renterData.reference2Address.required = required
        renterData.reference2City.required = required
        renterData.reference2State.required = required
        renterData.reference2Zip.required = required

        if (this.renterData.propertyOwnership === 'rent') {
            renterData.landlordFullName.required = required
            renterData.landlordPhoneNumber.required = required
        }

        if (this.renterData.coRenterFirstName || this.renterData.coRenterLastName) {
            renterData.coRenterFirstName.required = required
            renterData.coRenterLastName.required = required
            renterData.coRenterEmployer.required = required
            renterData.coRenterEmployerPhoneNumber.required = required
            renterData.coRenterSupervisor.required = required
            renterData.coRenterSupervisorOccupation.required = required
        }
    }

    let rtoRules = {
        renterData,
        customer
    }
    return rtoRules
}
