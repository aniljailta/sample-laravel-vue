const routes = (configRoute) => [
    {
        path: configRoute.routePrefix + '/options',
        name: 'option-library',
        props: { title: 'Option Library' },
        meta: {
            title: 'Option Library',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/options/list/ListItems.vue'], resolve)
        }
    }
]

export default routes
