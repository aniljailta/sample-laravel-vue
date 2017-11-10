const routes = (configRoute) => [
    {
        path: configRoute.routePrefix + '/styles',
        name: 'style-library',
        props: { title: 'Style Library' },
        meta: {
            title: 'Style Library',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/styles/list/ListItems.vue'], resolve)
        }
    }
]

export default routes
