import PageNotFound from './PageNotFound.vue'

const routes = [
    {
        path: '*',
        name: 'page-not-found',
        component: PageNotFound
    }
]

export default routes