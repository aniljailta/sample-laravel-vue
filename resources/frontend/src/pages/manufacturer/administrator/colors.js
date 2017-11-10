const routes = (configRoute) => [
    {
        path: configRoute.routePrefix + '/colors',
        name: 'color-library',
        props: { title: 'Color Library' },
        meta: {
            title: 'Color Library',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/colors/list/ListItems.vue'], resolve)
        }
    }
]

export default routes
