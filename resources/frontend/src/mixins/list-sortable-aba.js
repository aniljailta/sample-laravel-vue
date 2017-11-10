import qs from 'qs'

export default {
    methods: {
        orderBy(field) {
            // vue router style
            let direction = 'desc'
            let current = {}
            let routeQuery = qs.parse(this.$route.query)

            if (!_.isUndefined(routeQuery.orderBy)) {
                current = _.zipObject(['field', 'direction'], routeQuery.orderBy.split(' '))
            }

            if (!_.isUndefined(current.field) && current.field === field) {
                direction = (!_.isNil(current.direction) && current.direction === 'desc') ? 'asc' : 'desc'
            }

            let query = _.assign({}, routeQuery, {
                orderBy: field + ' ' + direction
            })
            this.refreshList({query, $router: this.$router})
        },
        changePage(page) {
            let routeQuery = qs.parse(this.$route.query)
            let query = _.assign({}, routeQuery, {page: page})
            this.refreshList({query, $router: this.$router})
        },
        getClass(field, area = 'icon') {
            let className = {}
            let current = {}
            let routeQuery = qs.parse(this.$route.query)

            if (!_.isUndefined(routeQuery.orderBy)) {
                current = _.zipObject(['field', 'direction'], routeQuery.orderBy.split(' '))
            } else {
                current.field = 'id'
            }

            if (current.field !== field) {
                if (area === 'icon') className['fa-sort'] = true
                return className
            }

            if (area === 'head') {
                className['sort'] = true
            }

            if (current.direction === 'desc' && area === 'icon') {
                className['fa-sort-desc'] = true
            }

            if (current.direction !== 'desc' && area === 'icon') {
                className['fa-sort-asc'] = true
            }

            return className
        }
    }
}
