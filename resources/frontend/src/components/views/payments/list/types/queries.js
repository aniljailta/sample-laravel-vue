export default {
	searches: {
		invoice_id(search) {
			let query = {}
			let invoices = search.value.split(',')
			_.set(query, ['where', 'invoice_id', 'inq'], invoices)
			return query
		}
	}
}