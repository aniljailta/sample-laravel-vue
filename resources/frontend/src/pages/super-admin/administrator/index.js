const configRoute = {
    role: 'administrator',
    routePrefix: ''
}

const routes = [
    {
        path: '/',
        redirect: configRoute.routePrefix + '/dashboard'
    },
    {
        path: configRoute.routePrefix + '/dashboard',
        name: 'dashboard',
        meta: {
            title: 'Dashboard',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/dashboard/super-admin/Administrator.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/building-statuses',
        name: 'building-statuses',
        props: { title: 'Building Statuses' },
        meta: {
            title: 'Building Statuses',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/building-statuses/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/option-categories',
        name: 'option-categories',
        props: { title: 'Option Categories' },
        meta: {
            title: 'Option Categories',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/option-categories/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/styles',
        name: 'style-catalog',
        props: { title: 'Style Catalog' },
        meta: {
            title: 'Style Catalog',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/style-catalog/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/colors',
        name: 'color-catalog',
        props: { title: 'Color Catalog' },
        meta: {
            title: 'Color Catalog',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/color-catalog/list/ListItems.vue'], resolve)
        }
    },
    {
        path: configRoute.routePrefix + '/options',
        name: 'option-catalog',
        props: { title: 'Option Catalog' },
        meta: {
            title: 'Option Catalog',
            roles: ['administrator']
        },
        component: resolve => {
            require(['src/components/views/option-catalog/list/ListItems.vue'], resolve)
        }
    }
]

export default routes