const routes = (configRoute) => [
    {
        path: configRoute.routePrefix + '/employees',
        name: 'employees',
        props: {title: 'Employees'},
        meta: {
            title: 'Employees',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/employees/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/employees/:id',
        name: 'employee.show',
        props: true,
        meta: {
            title: 'Employee',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/employees/show/ShowItem.vue'], resolve)
        }
    }
]

export default routes
