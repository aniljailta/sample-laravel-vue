import Vue from 'vue'
import VueRouter from 'vue-router'

// Can't to load it here by chunk order loading issue
// import store from '../vuex/store'

import states from './states'

// Routes
import system from './system'
import manufacturerUserRoutes from './manufacturer'
import rtoUserRoutes from './rto'
import superAdminUserRoutes from './super-admin'

Vue.use(VueRouter)
const router = new VueRouter({
    // mode: 'history'
})

const getRoutes = (companyRole, userRole) => {
    let rootRoute = {
        path: '/',
        component: resolve => require(['../components/layout/Main.vue'], resolve)
    }

    let userRoutes = []
    switch (companyRole) {
        case 'manufacturer':
            userRoutes = manufacturerUserRoutes(userRole)
            break
        case 'rto':
            userRoutes = rtoUserRoutes(userRole)
            break
        case 'super_admin':
            userRoutes = superAdminUserRoutes(userRole)
            break
    }

    rootRoute.children = userRoutes
    return [
        rootRoute,
        ...system
        // todo: login page?
    ]
}

const construct = (store) => {
    router.beforeEach((to, from, next) => {
        Promise.all([
            states.getUserState(store),
            states.getCompanyState(store)
        ]).then(response => {
            let userRole = response[0]
            let company = response[1]

            if (to.matched.length === 0) {
                // The target route doesn't exist yet
                // TODO: replace children routes asynchronically based on company-user roles?
                // https://github.com/vuejs/vue-router/issues/1156
                let routes = getRoutes(company.roleId, userRole.name)
                router.addRoutes(routes)
                next(to.fullPath)
            } else {
                // The route and component are known and data is available
                // Check role (and permission later?)
                if (to.meta.roles && (_.isUndefined(userRole.name) || to.meta.roles.indexOf(userRole.name) === -1)) {
                    next({ path: '/oops' })
                }

                next()
            }
        })
    })

    return router
}

export default construct
