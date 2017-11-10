import config from 'src/../config'
import Vue from 'vue'
import Vuex from 'vuex'
// import modules from './modules'
// import dof from './modules/dealer-order-form'

import user from './modules/user'
import settings from './modules/settings'

Vue.use(Vuex)

const store = new Vuex.Store({
    strict: config.debug,
    modules: {
        user,
        settings
    }
})

export default store

if (module.hot) {
    // accept actions and mutations as hot modules
    module.hot.accept(() => {
        // swap in the new actions and mutations
        store.hotUpdate({
            // modules
        })
    })
}
