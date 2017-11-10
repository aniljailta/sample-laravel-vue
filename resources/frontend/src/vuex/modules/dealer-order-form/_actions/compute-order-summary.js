import types from '../types'

import {
    orderType,
    orderPaymentType,
    orderPaymentMethod,
    currentOrderSalesTax,
    currentChangeOrderFee,
    currentOrderRtoDeposit,
    orderRtoType,
    orderGrossBuydown,
    currentOrderNetBuydown,
    currentOrderTotalDepositAmountDue,
    currentOrderRtoPayment,
    orderAmountReceived,
    orderTransactionId,
    currentOrderTotalBuildingPrice,
    currentOrderPaymentType,
    currentOrderPaymentMethod,
    currentOrderType,
    currentOrderRtoType,
    currentOrderRtoTerm
} from '../_getters/order-order'

const computeOrderSummary = function ({commit, state}) {
    let summary = {
        type: orderType(state),
        paymentType: orderPaymentType(state),
        paymentMethod: orderPaymentMethod(state),
        salesTax: currentOrderSalesTax(state),
        changeOrderFee: currentChangeOrderFee(state),
        securityDeposit: currentOrderRtoDeposit(state),
        rtoType: orderRtoType(state),
        grossBuydown: orderGrossBuydown(state),
        netBuydown: currentOrderNetBuydown(state),
        totalAmountDue: currentOrderTotalDepositAmountDue(state),
        rtoPayment: currentOrderRtoPayment(state)(),
        amountReceived: orderAmountReceived(state),
        transactionId: orderTransactionId(state),
        currentTotal: currentOrderTotalBuildingPrice(state),
        currentPaymentType: currentOrderPaymentType(state),
        currentPaymentMethod: currentOrderPaymentMethod(state),
        currentType: currentOrderType(state),
        currentRtoType: currentOrderRtoType(state),
        currentRtoTerm: currentOrderRtoTerm(state)
    }

    commit(types.UPDATE_SUMMARY, {order: summary})
}

export default computeOrderSummary