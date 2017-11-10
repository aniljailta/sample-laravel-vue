import {
    orderDealer,
    orderBuilding,
    orderOrder
} from './'

import orderDate from './date'
// ex.Computed
import currentOrderCustomerExpectsDate from './customer-expects-date'

// grumpy =(
const orderType = state => {
    return state.order.type
}

const orderPaymentType = state => {
    return state.order.paymentType
}

const orderPaymentMethod = state => {
    return state.order.paymentMethod
}

const orderTransactionId = state => {
    return state.order.transactionId
}

const orderDeliveryRemarks = state => {
    return state.order.deliveryRemarks
}

const orderRtoType = state => {
    return state.order.rtoType
}

const orderRtoTerm = state => {
    return state.order.rtoTerm
}

const orderPromo99 = state => {
    return state.order.promo99
}

const orderSignatureMethodId = state => {
    return state.order.signatureMethodId
}

const currentOrderRtoType = state => {
    let rtoType = state.order.rtoType
    if (rtoType === null) {
        return 'Not yet selected'
    }

    if (rtoType === 'buydown') {
        return 'Buydown'
    } else {
        return 'No Buydown'
    }
}

const currentOrderRtoTerm = state => {
    let rtoTerms = state.orderTerms.rtoTerms
    let rtoTerm = orderRtoTerm(state) || null

    if (!_.isNil(rtoTerms) && !_.isNil(rtoTerm) && !_.isNil(rtoTerms[rtoTerm])) {
        return rtoTerms[rtoTerm]
    }

    return null
}

const currentOrderType = state => {
    let type = state.order.type
    if (type === 'order') {
        return 'Order'
    }
    if (type === 'quote') {
        return 'Quote'
    }
    return null
}

const currentOrderPaymentType = state => {
    let paymentType = state.order.paymentType
    if (paymentType === null) {
        return 'Not yet selected'
    }

    if (paymentType === 'cash') {
        return 'Cash'
    } else {
        return 'Rent-to-Own'
    }
}

const currentOrderPaymentMethod = state => {
    let paymentMethod = state.order.paymentMethod
    if (paymentMethod === 'cash') {
        return 'Cash'
    }

    if (paymentMethod === 'check') {
        return 'Check'
    }

    if (paymentMethod === 'credit_card') {
        return 'Credit Card'
    }

    return null
}

/*
 * Order Options
 */
const orderOptions = state => state.order.options || []
const taxableOptions = state => orderOptions(state).filter(option => option.taxable)
const nonTaxableOptions = state => orderOptions(state).filter(option => !option.taxable)
const rtoOptions = state => orderOptions(state).filter(option => option.rto)
const nonRtoOptions = state => orderOptions(state).filter(option => !option.rto)
const rtoDepositOptions = state => orderOptions(state).filter(option => option.rtoDeposit)

const currentTotalOrderOptions = state => _.sumBy(orderOptions(state), 'total')
const currentTotalTaxableOptions = state => _.sumBy(taxableOptions(state), 'total')
const currentTotalNonTaxableOptions = state => _.sumBy(nonTaxableOptions(state), 'total')
const currentTotalRtoOptions = state => _.sumBy(rtoOptions(state), 'total')
const currentTotalNonRtoOptions = state => _.sumBy(nonRtoOptions(state), 'total')
const currentTotalRtoDepositOptions = state => _.sumBy(rtoDepositOptions(state), 'total')

const orderAmountReceived = state => {
    return state.order.amountReceived
}

const orderMfDepositReceived = state => {
    return state.order.mfDepositReceived || 0
}

const orderRtoDepositReceived = state => {
    return state.order.rtoDepositReceived || 0
}

const currentChangeOrderFee = state => {
    return state.order.changeOrderFee || 0
}

/*
 * @grossBuydown
 */
const orderGrossBuydown = state => {
    let amountReceived = orderAmountReceived(state)
    let rtoDepositReceived = orderRtoDepositReceived(state)
    let mfDepositAmountDue = currentOrderMfDepositAmountDue(state)

    let grossBuydown = amountReceived - mfDepositAmountDue + rtoDepositReceived
    return grossBuydown
}

/*
 * @rtoDeposit
 */
const currentOrderRtoDeposit = state => {
    let building = orderBuilding(state)
    // let dealer = orderDealer(state)
    let totalRtoDepositOptions = currentTotalRtoDepositOptions(state)
    let promo99 = orderOrder(state).promo99

    if (building === null) return 0 // building not specified yet

    let rtoDeposit = 0 // init value

    // define security deposit
    if (promo99) {
        rtoDeposit = 99
    } else if (building.saleType === 'dealer-inventory' && building.inventoryBuilding.serial !== null) {
        rtoDeposit = building.inventoryBuilding.securityDeposit
    } else if (building.saleType === 'custom-order' && building.buildingDimension !== null) {
        var width = building.buildingDimension.width
        if (width <= 8) rtoDeposit = 150
        if (width > 8 && width <= 10) rtoDeposit = 200
        if (width > 10 && width <= 12) rtoDeposit = 250
        if (width > 12 && width <= 14) rtoDeposit = 300
    }

    // add delivery charge
    rtoDeposit = rtoDeposit + totalRtoDepositOptions
    return rtoDeposit
}

/*
 * @netBuydown (calculated with 'no-buydown')
 */
const currentOrderNetBuydown = state => {
    let paymentType = orderOrder(state).paymentType
    if (paymentType !== 'rto') return 0

    let grossBuydown = orderGrossBuydown(state)
    let minRtoDepositAmount = currentOrderMinRtoDepositAmount(state)({ rtoType: 'no-buydown' })
    let taxFactor = orderSalesTaxRate(state) / 100

    let netBuydown = 0
    if (grossBuydown >= minRtoDepositAmount) {
        netBuydown = (grossBuydown - minRtoDepositAmount) / (1 + taxFactor)
    }

    return parseFloat(netBuydown)
}

/*
 * @totalBuildingPrice
 * shell price + total building options
 * @return float
 */
const currentOrderTotalBuildingPrice = state => {
    let building = orderBuilding(state)
    let total = 0

    // Custom Order
    if (building.saleType === 'dealer-inventory') {
        // Get Base Model Price
        if (building.inventoryBuilding.serial !== null) {
            total += _.toNumber(building.inventoryBuilding.price)
        }
    }

    // Custom Order
    if (building.saleType === 'custom-order') {
        // Get Base Model Price
        if (building.buildingDimension) {
            total += _.toNumber(building.buildingDimension.shellPrice)
        }

        let totalOptions = 0
        if (building.customBuildOptions.length > 0) {
            totalOptions = _.reduce(building.customBuildOptions, function (total, el) {
                total += (el.unitPrice * el.quantity)
                return _.toNumber(total)
            }, 0)
        }
        total += _.toNumber(totalOptions)
    }

    return total
}

/**
 * Calculate sales tax (@salesTax)
 * @return Number
 */
const currentOrderSalesTax = state => {
    let taxRate = orderSalesTaxRate(state)
    let totalBuildingPrice = currentOrderTotalBuildingPrice(state)
    let totalTaxableOptions = currentTotalTaxableOptions(state)

    return parseFloat((totalBuildingPrice + totalTaxableOptions) * (taxRate / 100))
}

/**
 * Calculate tax and factor, can be dealer|sales (@taxRate, @taxFactor)
 * @return number
 */
const orderSalesTaxRate = state => {
    let dealer = orderDealer(state)
    if (state.order.salesTaxRate !== null) {
        return _.toNumber(state.order.salesTaxRate)
    }
    if (dealer.taxRate !== null) {
        return _.toNumber(dealer.taxRate)
    }
    return 0
}

/**
 * Calculate RTO Amount (@rtoAmount)
 * @return number
 */
const currentOrderRtoAmount = state => {
    let netBuydown = currentOrderNetBuydown(state)
    let totalBuildingPrice = currentOrderTotalBuildingPrice(state)
    let totalRtoOptions = currentTotalRtoOptions(state)

    let rtoAmount = totalBuildingPrice + totalRtoOptions
    if (netBuydown > 0) {
        rtoAmount -= netBuydown
    }

    return rtoAmount
}

/**
 * Calculate RTO Payment
 * @return number
 */
const currentOrderRtoPayment = state => args => {
    args = args || {}
    let totalBuildingPrice = currentOrderTotalBuildingPrice(state)
    let totalRtoOptions = currentTotalRtoOptions(state)
    let paymentType = orderOrder(state).paymentType
    let rtoTerm = currentOrderRtoTerm(state)
    let taxFactor = orderSalesTaxRate(state) / 100

    if (!(paymentType === 'rto' && rtoTerm !== null)) {
        return 0
    }

    let rtoType = args.rtoType || 'buydown'
    let rtoFactor = rtoTerm.rtoFactor

    if (rtoType === 'no-buydown') {
        let total = (totalBuildingPrice + totalRtoOptions) / rtoFactor
        return total * (1 + taxFactor)
    }

    if (rtoType === 'buydown') {
        let advancedMonthlyRtoPayment = (currentOrderRtoAmount(state) / rtoFactor)
        let rtoPayment = advancedMonthlyRtoPayment * (1 + taxFactor)
        return rtoPayment
    }

    return 0
}

/**
 * Calculate total purchase (@totalPurchase)
 * @return number
 */
const currentOrderTotalPurchase = state => {
    let totalBuildingPrice = currentOrderTotalBuildingPrice(state)
    let totalTaxableOptions = currentTotalTaxableOptions(state)
    let totalNonTaxableOptions = currentTotalNonTaxableOptions(state)
    let dealer = orderDealer(state)
    let taxFactor = orderSalesTaxRate(state) / 100
    let totalPurchase = 0

    if (dealer.depositType === 1) {
        totalPurchase = totalBuildingPrice + totalTaxableOptions
    } else {
        totalPurchase = (totalBuildingPrice + totalTaxableOptions) * (1 + taxFactor) + totalNonTaxableOptions
    }

    return Math.round(totalPurchase * 100) / 100
}

/**
 * Calculate purchase outright deposit amount (@purchaseOutrightDepositAmount)
 * @return number
 */
const currentOrderPurchaseOutrightDepositAmount = state => {
    let paymentType = orderOrder(state).paymentType
    if (paymentType !== 'cash') return 0

    let totalPurchase = currentOrderTotalPurchase(state)
    let dealer = orderDealer(state)
    let cashSaleDepositFactor = dealer.cashSaleDepositRate === null ? 1 : dealer.cashSaleDepositRate / 100

    let depositAmount = cashSaleDepositFactor * totalPurchase
    return Math.round(depositAmount * 100) / 100
}

/**
 * Calculate min rto deposit amount (@minRtoDepositAmount)
 * @return number
 */
const currentOrderMinRtoDepositAmount = state => args => {
    let paymentType = orderOrder(state).paymentType
    if (paymentType !== 'rto') return 0

    args = args || {}
    let promo99 = orderOrder(state).promo99
    let rtoDeposit = currentOrderRtoDeposit(state)
    let minDepositAmount = 0

    let rtoType = args.rtoType || 'buydown'

    if (promo99) {
        minDepositAmount = rtoDeposit
    } else {
        let rtoPaymentWTax = currentOrderRtoPayment(state)({rtoType: rtoType})
        minDepositAmount = rtoDeposit + rtoPaymentWTax
    }

    return Math.round(minDepositAmount * 100) / 100
}

/**
 * Calculate Amount to Apply to Order (Minimum) (@minAmountToApplyToOrder, required for customer input)
 * @return number
 */
const currentOrderMinAmountApplyOrder = state => {
    let paymentType = orderOrder(state).paymentType
    let changeOrderFee = currentChangeOrderFee(state)
    // min deposit amount based on buydown (default)
    let minRtoDepositAmount = currentOrderMinRtoDepositAmount(state)()
    let mfDepositAmountDue = currentOrderMfDepositAmountDue(state)

    let minAmountApplyOrder = 0
    if (paymentType === 'cash') {
        minAmountApplyOrder = mfDepositAmountDue
    }

    if (paymentType === 'rto') {
        let rtoDepositAmountDue = currentOrderRtoDepositAmountDue(state)

        if (changeOrderFee) {
            minAmountApplyOrder = mfDepositAmountDue + rtoDepositAmountDue
        } else {
            minAmountApplyOrder = minRtoDepositAmount + mfDepositAmountDue
        }
    }

    return Math.round(minAmountApplyOrder * 100) / 100
}

/**
 * Calculate total deposit amount due on order
 * @totalDepositAmountDue
 * @return number
 */
const currentOrderTotalDepositAmountDue = state => {
    let rtoAmountDue = currentOrderRtoDepositAmountDue(state)
    let mfAmountDue = currentOrderMfDepositAmountDue(state)
    let totalAmountDue = mfAmountDue + rtoAmountDue
    return Math.round(totalAmountDue * 100) / 100
}

/**
 * Calculate manufacturer deposit amount due on order
 * @mfDepositAmountDue
 * @return number
 */
const currentOrderMfDepositAmountDue = state => {
    let paymentType = orderOrder(state).paymentType
    let changeOrderFee = currentChangeOrderFee(state)
    let mfDepositReceived = orderMfDepositReceived(state)

    let mfDepositAmountDue = 0

    if (paymentType === 'cash') {
        mfDepositAmountDue = currentOrderPurchaseOutrightDepositAmount(state) - mfDepositReceived
        mfDepositAmountDue = Math.max(mfDepositAmountDue, 0)
        mfDepositAmountDue += changeOrderFee
    }

    if (paymentType === 'rto') {
        let totalNonRtoOptions = currentTotalNonRtoOptions(state)

        mfDepositAmountDue = totalNonRtoOptions - mfDepositReceived
        mfDepositAmountDue = Math.max(mfDepositAmountDue, 0)
        mfDepositAmountDue += changeOrderFee
    }

    return mfDepositAmountDue
}

/**
 * Calculate rto deposit amount due on order
 * @rtoDepositAmountDue
 * @return number
 */
const currentOrderRtoDepositAmountDue = state => {
    let paymentType = orderOrder(state).paymentType
    if (paymentType !== 'rto') return 0

    // min deposit amount based on buydown (default)
    let minDepositAmount = currentOrderMinRtoDepositAmount(state)({rtoType: 'no-buydown'})
    let rtoDepositReceived = orderRtoDepositReceived(state)
    let grossBuydown = orderGrossBuydown(state)
    let netBuydown = currentOrderNetBuydown(state)

    let rtoDepositAmountDue = 0
    if (netBuydown <= 0) {
        rtoDepositAmountDue = minDepositAmount - rtoDepositReceived
    } else {
        rtoDepositAmountDue = grossBuydown - rtoDepositReceived
    }

    rtoDepositAmountDue = Math.max(rtoDepositAmountDue, 0)
    return rtoDepositAmountDue
}

/**
 * (@ordetTotal)
 */
const currentOrderTotal = state => {
    let totalBuildingPrice = currentOrderTotalBuildingPrice(state)
    let totalOptions = currentTotalOrderOptions(state)
    return totalBuildingPrice + totalOptions
}

/**
 * (@totalAmount)
 */
const currentTotalAmount = state => {
    let totalBuildingPrice = currentOrderTotalBuildingPrice(state)
    let totalOptions = currentTotalOrderOptions(state)
    let salesTax = currentOrderSalesTax(state)
    let changeOrderFee = currentChangeOrderFee(state)
    return (totalBuildingPrice + totalOptions + salesTax + changeOrderFee)
}

/**
 * (@balanceDue)
 */
const currentBalanceDue = state => {
    let paymentType = orderOrder(state).paymentType
    let totalPurchase = currentOrderTotalPurchase(state)
    let rtoAmount = currentOrderRtoAmount(state)
    let totalDepositReceived = orderAmountReceived(state)

    let balanceDue = 0

    if (paymentType === 'rto') {
        balanceDue = rtoAmount
    } else {
        balanceDue = totalPurchase - totalDepositReceived
    }

    return Math.round(balanceDue * 100) / 100
}

const currentOrderIsChangeOrder = state => (state.order.originalOrder)

export default {
    orderDate,
    orderType,
    orderPaymentType,
    orderGrossBuydown,
    orderAmountReceived,
    orderPaymentMethod,
    orderTransactionId,
    orderDeliveryRemarks,
    orderRtoType,
    orderRtoTerm,
    orderPromo99,
    orderSignatureMethodId,
    currentOrderCustomerExpectsDate,
    currentOrderRtoType,
    currentOrderRtoTerm,
    currentOrderType,
    currentOrderPaymentType,
    currentOrderPaymentMethod,
    currentOrderRtoDeposit,
    currentOrderNetBuydown,
    currentOrderTotalBuildingPrice,
    currentOrderSalesTax,
    currentChangeOrderFee,
    currentOrderPurchaseOutrightDepositAmount,
    currentOrderRtoAmount,
    currentOrderRtoPayment,
    currentOrderMfDepositAmountDue,
    currentOrderRtoDepositAmountDue,
    currentOrderTotalDepositAmountDue,
    currentOrderMinRtoDepositAmount,
    currentOrderMinAmountApplyOrder,
    currentTotalAmount,
    currentOrderTotal,
    currentOrderTotalPurchase,
    currentBalanceDue,
    orderOptions,
    currentTotalOrderOptions,
    currentTotalTaxableOptions,
    currentTotalNonTaxableOptions,
    currentTotalRtoOptions,
    currentTotalNonRtoOptions,
    currentTotalRtoDepositOptions,
    currentOrderIsChangeOrder,
    orderSalesTaxRate
}