import buildings from './buildings'
import buildingPackages from './building-packages'
import deliveries from './deliveries'
import employees from './employees'
import customers from './customers'
import locations from './locations'
import qrCodes from './qr-codes'
import reports from './reports'
import payments from './payments'
import invoices from './invoices'
import options from './options'
import styles from './styles'
import colors from './colors'
import priceGroups from './price-groups'

const configRoute = {
    role: 'administrator',
    routePrefix: ''
}

const routes = [
    {
        path: '/',
        redirect: configRoute.routePrefix + '/dashboard'
    },
    ...buildingPackages(configRoute),
    ...buildings(configRoute),
    ...deliveries(configRoute),
    ...employees(configRoute),
    ...customers(configRoute),
    ...locations(configRoute),
    ...qrCodes(configRoute),
    ...reports(configRoute),
    ...payments(configRoute),
    ...invoices(configRoute),
    ...options(configRoute),
    ...styles(configRoute),
    ...colors(configRoute),
    ...priceGroups(configRoute),
    {
        path: configRoute.routePrefix + '/dashboard',
        name: 'dashboard',
        meta: {
            title: 'Dashboard',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/dashboard/index.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/orders',
        name: 'orders',
        props: { title: 'Orders' },
        meta: {
            title: 'Orders',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/orders/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/sales',
        name: 'sales',
        props: { title: 'Sales' },
        meta: {
            title: 'Sales',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/sales/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/building-models',
        name: 'building-models',
        props: { title: 'Building Models' },
        meta: {
            title: 'Building Models',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/building-models/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/dealers',
        name: 'dealers',
        props: { title: 'Dealers' },
        meta: {
            title: 'Dealers',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/dealers/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/plants',
        name: 'plants',
        props: { title: 'Plants' },
        meta: {
            title: 'Plants',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/plants/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/settings',
        name: 'settings',
        props: { title: 'Settings' },
        meta: {
            title: 'Settings',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/settings/index.vue'], resolve)
        }
    }
]

export default routes