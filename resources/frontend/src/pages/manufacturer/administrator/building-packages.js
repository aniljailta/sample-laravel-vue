const routes = (configRoute) => [
    {
        path: configRoute.routePrefix + '/building-package/categories',
        name: 'building-package-categories',
        props: {title: 'Building Packages Categories'},
        meta: {
            title: 'Building Package Categories',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/building-package-categories/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/building-package',
        name: 'building-packages',
        props: {title: 'Building Packages'},
        meta: {
            title: 'Building Packages',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/building-packages/list/ListItems.vue'], resolve)
        }
    }
]

export default routes
