import {required} from 'vuelidate/lib/validators'
import accepted from 'src/validators/accepted'

export default function () {
    let attachedCategories = {}

    if (this.orderStateMode === 'submit') {
        // go to step 6?
        /*
        if (this.deliveryRemarks.mustCrossNeighboringProperty) {
            attachedCategories.signedNeighborRelease.accepted = accepted
        }
        */

        if (this.signatureMethodId === 'manual') {
            attachedCategories.signedOrderDocuments = {accepted}
            if (this.saleType === 'custom-order') {
                attachedCategories.signedBuildingConfiguration = {accepted}
            }
            if (this.paymentType !== 'cash') {
                attachedCategories.driverLicense = {accepted}
            }
        }

        if (this.signatureMethodId === 'e_signature') {
            if (this.saleType === 'custom-order') {
                attachedCategories.signedBuildingConfiguration = {accepted}
            }

            if (this.eSignAvailable && !this.getRtoSignaturePendingStatus) {
                attachedCategories.eSignedOrderDocuments = {accepted}
            }
            if (this.paymentType !== 'cash') {
                attachedCategories.driverLicense = {accepted}
            }
        }
    }

    let signatureMethodId = {required}

    return {
        signatureMethodId,
        attachedCategories
    }
}
