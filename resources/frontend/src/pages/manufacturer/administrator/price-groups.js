const routes = (configRoute) => [
    {
        path: configRoute.routePrefix + '/price-groups',
        name: 'price-groups',
        props: { title: 'Price Groups' },
        meta: {
            title: 'Price Groups',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/price-groups/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/price-groups/:id',
        name: 'price-group.show',
        props: true,
        meta: {
            title: 'Price Group',
            roles: ['administrator']
        },
        component: function (resolve) {
            require(['src/components/views/price-groups/show/ShowItem.vue'], resolve)
        }
    }
]

export default routes
