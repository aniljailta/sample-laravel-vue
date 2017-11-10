const routes = (configRoute) => [
    {
        path: configRoute.routePrefix + '/reports/dealer-inventory',
        name: 'reports.dealer-inventory',
        props: {title: 'Dealer Inventory'},
        meta: {
            title: 'Dealer Inventory',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/reports/dealer-inventory/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/reports/sales',
        name: 'reports.sales',
        props: {title: 'Sales'},
        meta: {
            title: 'Sales',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/reports/sales/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/reports/orders',
        name: 'reports.orders',
        props: {title: 'Orders'},
        meta: {
            title: 'Orders',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/reports/orders/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/reports/lead-contacts',
        name: 'reports.lead-contacts',
        props: {title: 'Lead and Closing Report'},
        meta: {
            title: 'Lead and Closing Report',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/reports/lead-contacts/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/reports/dealer-commission',
        name: 'reports.dealer-commission',
        props: {title: 'Dealer Commission'},
        meta: {
            title: 'Dealer Commission',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/reports/dealer-commission/list/ListItems.vue'], resolve)
        }
    }
]

export default routes
