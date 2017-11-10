export default {
	searches: {
		customer_id(search) {
			let query = {}
			let customers = search.value.split(',')
			_.set(query, ['where', 'customer_id', 'inq'], customers)
			return query
		},
		status(search) {
			let query = {}
			let statuses = search.value.split(',')
			_.set(query, ['where', 'status', 'inq'], statuses)
			return query
		}
	}
}
