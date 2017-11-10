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

            this.refreshList({
                orderBy: field,
                direction: direction
            })
        },
        changePage(page) {
            this.refreshList({page: page})
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
