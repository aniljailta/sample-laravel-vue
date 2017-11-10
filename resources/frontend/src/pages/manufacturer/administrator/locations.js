const routes = (configRoute) => [
    {
        path: configRoute.routePrefix + '/locations',
        name: 'locations',
        props: {title: 'Locations'},
        meta: {
            title: 'Locations',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/locations/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/locations/:id',
        name: 'location.show',
        props: true,
        meta: {
            title: 'Location',
            id: item => item.name,
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/locations/show/ShowItem.vue'], resolve)
        }
    }
]

export default routes
