<template>

	<div class="search-form form-horizontal">
		<ul class="list-group list-group-horizontal list-tools-form">
			<template v-for="search in currentSearches">
				<form-search-text v-if="search.id === 'payment_id'"
                                  v-bind:item="search">
                </form-search-text>
				<form-search-date v-if="search.id === 'date_created'"
								  v-bind:item="search"
								  v-bind:format="'YYYY-MM-DD HH:mm:ss'">
				</form-search-date>
				<form-search-select v-if="search.id === 'invoice_id'"
                                    :label="'id'"
                                    v-bind:title="'id'"
                                    v-bind:datas="invoices"
                                    v-bind:item="search">
                </form-search-select>
			</template>
		</ul>
	</div>

</template>

<script type="text/babel">
	import baseSearchForm from 'src/components/views/_base/ListItems/filters/SearchForm.vue'
	import FormSearchDate from 'src/components/views/_base/ListItems/search-forms/Date.vue'
	import FormSearchSelect from 'src/components/views/_base/ListItems/search-forms/Select.vue'
	import FormSearchText from 'src/components/views/_base/ListItems/search-forms/TextInput.vue'

    import apiInvoives from 'src/api/invoices'

	export default {
		extends: baseSearchForm,
		data() {
			return {}
		},
		components: {
			FormSearchDate,
			FormSearchSelect,
			FormSearchText
		},
		methods: {
			syncSearches() {},
			fetchData() {
                const datas = [
                    apiInvoives.get({
                        query: {
                            per_page: 99999,
                            order_by: ['id asc']
                        }
                    })
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.invoices = response[0].data.data
                        this.$emit('data-ready')
                        return response
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            }
		}
	}
</script>