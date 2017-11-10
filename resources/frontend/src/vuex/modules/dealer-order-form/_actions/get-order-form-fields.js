import deleteNullProperties from 'src/helpers/delete-null-properties'
import extractBuildingOptions from 'src/helpers/option-picker/extract-building-options'

const getOrderFormFields = function ({state}, data) {
    let form = state.state.form
    data = data || {}
    if (form === 'dealer') {
        let temp = _.cloneDeep({
            // dealer step
            form: form,
            id: state.current.id,
            dealerNotes: state.current.dealerNotes,

            dealerId: state.dealer.id,
            salesPerson: state.dealer.salesPerson,

            // customer step
            customer: state.customer,

            // building step
            buildingCondition: state.building.buildingCondition,
            saleType: state.building.saleType,
            serial: state.building.serial,
            buildingPackage: (state.building.buildingPackage) ? state.building.buildingPackage.id : null,
            buildingStyle: (state.building.buildingStyle) ? state.building.buildingStyle.id : null,
            buildingDimension: (state.building.buildingDimension) ? state.building.buildingDimension.id : null,
            customBuildOptions: extractBuildingOptions(state.building.customBuildOptions),
            threedoptions: state.building.threedoptions,
            views: state.building.views,

            // order step
            type: state.order.type,
            amountReceived: state.order.amountReceived,
            grossBuydown: state.order.grossBuydown,
            paymentType: state.order.paymentType,
            paymentMethod: state.order.paymentMethod,
            transactionId: state.order.transactionId,
            deliveryCharge: state.order.deliveryCharge,
            deliveryRemarks: state.order.deliveryRemarks,
            orderDate: state.order.date,
            ced: state.order.customerExpectsDate,
            signatureMethodId: state.order.signatureMethodId,
            salesTaxRate: state.order.salesTaxRate,

            rtoType: state.order.rtoType,
            rtoTerm: state.order.rtoTerm,
            promo99: state.order.promo99,

            // order step - renter
            renter: state.renter,
            orderOptions: state.order.options
        })

        let payload = _.merge(temp, data)

        if (_.isUndefined(payload.withEmpty) || payload.withEmpty === false) deleteNullProperties(payload)
        return payload
    }
}

export default getOrderFormFields
