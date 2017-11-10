import moment from 'moment'
import parseDates from 'src/components/views/_base/ListItems/_helpers/parse-route-search-dates'

let dimensions = [
    {
        name: 'Sale ID',
        id: 'sale_id',
        checked: false,
        order_by: 'id',
        query: {
            fields: ['id'],
            group_by: ['id']
        }
    },
    {
        name: 'Order ID',
        id: 'order_id',
        checked: true,
        order_by: 'order_id',
        query: {
            fields: ['order_id'],
            group_by: ['order_id']
        }
    },
    {
        name: 'Date Created',
        id: 'date_created',
        checked: false,
        order_by: 'created_at',
        query: {
            fields: ['created_at'],
            group_by: ['created_at']
        }
    },
    {
        name: 'Date Submitted',
        id: 'date_submitted',
        checked: true,
        order_by: 'order.date_submitted',
        query: {
            fields: ['order_id'],
            include: ['order'],
            leftJoinRelation: ['order'],
            group_by: ['order.date_submitted']
        }
    },
    {
        name: 'Status',
        id: 'status_id',
        checked: false,
        order_by: 'status_id',
        query: {
            fields: ['status_id'],
            group_by: ['status_id']
        }
    },
    {
        name: 'Customer',
        id: 'customer',
        order_by: 'order.order_reference.first_name',
        query: {
            fields: ['order_id'],
            include: ['order.order_reference'],
            leftJoinRelation: ['order.order_reference'],
            group_by: ['order.order_reference.first_name']
        },
        checked: false
    },
    {
        name: 'Order Type',
        id: 'order_type',
        checked: false,
        order_by: 'order.sale_type',
        query: {
            fields: ['order_id'],
            include: ['order'],
            leftJoinRelation: ['order'],
            group_by: ['order.sale_type']
        }
    },
    {
        name: 'Payment Type',
        id: 'payment_type',
        checked: false,
        order_by: 'order.payment_type',
        query: {
            fields: ['order_id'],
            include: ['order'],
            leftJoinRelation: ['order'],
            group_by: ['order.payment_type']
        }
    },
    {
        name: 'Building SN',
        id: 'building_sn',
        checked: false,
        order_by: 'building.serial_number',
        query: {
            fields: ['building_id'],
            include: ['building'],
            leftJoinRelation: ['building'],
            group_by: ['building.serial_number']
        }
    },
    {
        name: 'Dealer',
        id: 'dealer',
        checked: false,
        order_by: 'order.dealer_id',
        query: {
            fields: ['order_id'],
            include: ['order.dealer'],
            leftJoinRelation: ['order.dealer'],
            group_by: ['order.dealer_id']
        }
    },
    {
        name: 'Invoice #',
        id: 'invoice_id',
        checked: false,
        order_by: 'invoice_id',
        query: {
            fields: ['invoice_id'],
            group_by: ['invoice_id']
        }
    },
    {
        name: 'Dealer Commission',
        id: 'dealer_commission',
        checked: false,
        order_by: 'dealer_commission',
        query: {
            fields: ['order_id'],
            include: ['order'],
            leftJoinRelation: ['order'],
            fn: {
                'sum.dealer_commission': 'order.dealer_commission'
            }
        }
    },
    {
        name: 'Retail',
        id: 'retail',
        checked: false,
        order_by: 'retail',
        query: {
            fields: ['order_id'],
            include: ['order'],
            leftJoinRelation: ['order'],
            fn: {
                'sum.retail': 'order.total_sales_price'
            }
        }
    },
    {
        name: 'Sales Tax',
        id: 'sales_tax',
        checked: false,
        order_by: 'sales_tax',
        query: {
            fields: ['order_id'],
            include: ['order'],
            leftJoinRelation: ['order'],
            fn: {
                'sum.sales_tax': 'order.sales_tax'
            }
        }
    },
    {
        name: 'Amount Received',
        id: 'amount_received',
        checked: false,
        order_by: 'order.amount_received',
        query: {
            fields: ['order_id'],
            include: ['order'],
            leftJoinRelation: ['order'],
            group_by: ['order.amount_received']
        }
    }
]

let searches = [
    {
        name: 'Sale ID',
        id: 'sale_id',
        value: null,
        checked: false,
        query: {
            where: 'id'
        }
    },
    {
        name: 'Order ID',
        id: 'order_id',
        value: null,
        checked: false,
        query: {
            where: 'order_id'
        }
    },
    {
        name: 'Date Created',
        id: 'date_created',
        parse(value) {
            let between = []
            _.merge(between, parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD HH:mm:ss'))
            return {between}
        },
        checked: false,
        query: {
            where: 'created_at'
        }
    },
    {
        name: 'Date Submitted',
        id: 'date_submitted',
        parse(value) {
            let between = this.value.between
            _.merge(between, parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD'))
            return {between}
        },
        checked: true,
        query: {
            where: 'order.date_submitted'
        }
    },
    {
        name: 'Status',
        id: 'status_id',
        value: null,
        checked: false
        // query: {
        //     where: 'status_id'
        // }
    },
    {
        name: 'Customer',
        id: 'customer',
        value: null,
        checked: false
        // query by helper
    },
    /*
     {
     name: 'Customer',
     id: 'customer',
     value: null,
     query: {
     where: 'order.order_reference.id'
     },
     checked: false
     },*/
    {
        name: 'Order Type',
        id: 'order_type',
        value: null,
        checked: false
        // query: {
        //     where: 'order.sale_type'
        // }
    },
    {
        name: 'Payment Type',
        id: 'payment_type',
        value: null,
        checked: false
        // query: {
        //     where: 'order.payment_type'
        // }
    },
    {
        name: 'Building SN',
        id: 'building_id',
        value: null,
        checked: false,
        query: {
            where: 'building.id'
        }
    },
    {
        name: 'Dealer',
        id: 'dealer',
        value: null,
        checked: false
    },
    {
        name: 'Invoice #',
        id: 'invoice_id',
        checked: false,
        query: {
            where: 'invoice_id'
        }
    },
    {
        name: 'Dealer Commission',
        id: 'dealer_commission',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Retail',
        id: 'retail',
        value: null,
        checked: false,
        query: {
            where: 'order.total_sales_price'
        }
    },
    {
        name: 'Sales Tax',
        id: 'sales_tax',
        value: null,
        checked: false,
        query: {
            where: 'order.sales_tax'
        }
    },
    {
        name: 'Amount Received',
        id: 'deposit',
        value: null,
        checked: false,
        query: {
            where: 'order.amount_received'
        }
    },
    {
        name: 'Order Date',
        id: 'order_date',
        value: {
            between: [
                moment().startOf('month').format('YYYY-MM-DD'),
                moment().endOf('month').format('YYYY-MM-DD')
            ]
        },
        parse(value) {
            let between = this.value.between
            _.merge(between, parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD'))
            return {between}
        },
        checked: false,
        query: {
            where: 'order.order_date'
        }
    }
]

let totals = [
    {
        name: 'Rows',
        id: 'totalRows',
        type: 'unit',
        checked: null,
        selectable: false,
        value: null
    },
    {
        name: 'Dealer Commission',
        id: 'dealerCommission',
        type: 'money',
        checked: false,
        value: null,
        query: {
            aggregate: {
                'sum.dealerCommission': 'dealer_commission'
            },
            include: ['order'],
            leftJoinRelation: ['order'],
            fn: {
                'sum.dealer_commission': 'order.dealer_commission'
            }
        }
    },
    {
        name: 'Retail',
        id: 'totalRetail',
        type: 'money',
        checked: false,
        value: null,
        query: {
            aggregate: {
                'sum.totalRetail': 'retail'
            },
            include: ['order'],
            leftJoinRelation: ['order'],
            fn: {
                'sum.retail': 'order.total_sales_price'
            }
        }
    },
    {
        name: 'Sales Tax',
        id: 'totalSalesTax',
        type: 'money',
        checked: false,
        value: null,
        query: {
            aggregate: {
                'sum.totalSalesTax': 'sales_tax'
            },
            include: ['order'],
            leftJoinRelation: ['order'],
            fn: {
                'sum.sales_tax': 'order.sales_tax'
            }
        }
    },
    {
        name: 'Deposit Amount',
        id: 'totalDepositAmount',
        type: 'money',
        checked: false,
        value: null,
        query: {
            aggregate: {
                'sum.totalDepositAmount': 'amount_received'
            },
            include: ['order'],
            leftJoinRelation: ['order'],
            fn: {
                'sum.amount_received': 'order.amount_received'
            }
        }
    }
]

export default {
    dimensions,
    searches,
    totals
}
