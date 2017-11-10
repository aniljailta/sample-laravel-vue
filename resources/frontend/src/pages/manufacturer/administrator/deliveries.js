const routes = (configRoute) => [
    {
        path: configRoute.routePrefix + '/deliveries',
        name: 'deliveries',
        props: {title: 'Deliveries'},
        meta: {
            title: 'Deliveries',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/deliveries/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/delivery-trailers',
        name: 'delivery-trailers',
        props: {title: 'Delivery Trailers'},
        meta: {
            title: 'Delivery Trailers',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/delivery-trailers/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/delivery-trucks',
        name: 'delivery-trucks',
        props: {title: 'Delivery Trucks'},
        meta: {
            title: 'Delivery Trucks',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/delivery-trucks/list/ListItems.vue'], resolve)
        }
    }
]

export default routes
