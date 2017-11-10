const routes = (configRoute) => [
    {
        path: configRoute.routePrefix + '/customers',
        name: 'customers',
        props: {title: 'Customers'},
        meta: {
            title: 'Customers',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/customers/list/ListItems.vue'], resolve)
        }
    }
]

export default routes
