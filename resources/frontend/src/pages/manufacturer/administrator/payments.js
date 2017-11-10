const routes = (configRoute) => [
    {
        path: configRoute.routePrefix + '/payments',
        name: 'payments',
        props: {title: 'Payments'},
        meta: {
            title: 'Payments',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/payments/list/ListItems.vue'], resolve)
        }
    }
]

export default routes
