const routes = (configRoute) => [
    {
        path: configRoute.routePrefix + '/invoices',
        name: 'invoices',
        props: {title: 'Invoices'},
        meta: {
            title: 'Invoices',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/invoices/list/ListItems.vue'], resolve)
        }
    }
]

export default routes
