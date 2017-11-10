import qrCodes from './qr-codes'

const configRoute = {
    role: 'employee',
    routePrefix: ''
}

const routes = [
    ...qrCodes(configRoute),
    {
        path: '/',
        redirect: configRoute.routePrefix + '/dashboard'
    },
    {
        path: configRoute.routePrefix + '/dashboard',
        name: 'dashboard',
        meta: {
            title: 'Dashboard',
            roles: ['employee']
        },
        component: resolve => {
            require(['src/components/views/dashboard/manufacturer/Employee.vue'], resolve)
        }
    }
]

export default routes