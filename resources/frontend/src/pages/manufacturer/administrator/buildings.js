const routes = (configRoute) => [
    {
        path: configRoute.routePrefix + '/buildings',
        name: 'buildings',
        props: {title: 'Buildings'},
        meta: {
            title: 'Buildings',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/buildings/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/buildings/:id',
        name: 'building.show',
        props: true,
        meta: {
            title: 'Building',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/buildings/show/ShowItem.vue'], resolve)
        }
    }
]

export default routes
