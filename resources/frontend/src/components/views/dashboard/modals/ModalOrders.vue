<template>

	<modal :show="show"
		   :modal-class="modalClass"
		   :modal-style="modalStyle"
		   :mask-style="maskStyle">

		<div>
			<div class="panel-heading">
				<h2 class="panel-title">Orders Needing Review</h2>
			</div>
			<div class="panel-body modal-body">
				<div class="form-container" v-cloak>
					<list-items></list-items>
				</div>
			</div>
			<div class="panel-footer" style="text-align: center">
				<button type="button" class="btn btn-default" v-on:click="close">Close</button>
			</div>
		</div>

	</modal>

</template>

<script type="text/babel">
	import Modal from 'src/components/ui/Modal.vue'
	import listItems from '../orders/list/ListItems.vue'

	export default {
		data() {
			return {
				modalClass: 'col-md-9 col-sm-10 col-xs-12',
				modalStyle: {
					float: 'none',
					padding: '0'
				},
				maskStyle: {
					position: 'fixed'
				}
			}
		},
		components: {
			Modal,
			listItems
		},
		props: {
			item: {
				required: true
			},
			show: {
				type: Boolean,
				default: false
			}
		},
		methods: {
			close() {
				this.$emit('close')
			}
		}
	}
</script>