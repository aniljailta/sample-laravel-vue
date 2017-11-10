const routes = (configRoute) => [
    {
        path: configRoute.routePrefix + '/qr-build/:identifier',
        props: true,
        meta: {
            title: 'Qr Code Build Status',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/qr-codes/ListBuild.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/qr-location/:identifier',
        meta: {
            title: 'Qr Code Location',
            roles: ['administrator']
        },
        props: true,
        component: resolve => {
            require(['src/components/views/qr-codes/ListLocation.vue'], resolve)
        }
    }
]

export default routes
