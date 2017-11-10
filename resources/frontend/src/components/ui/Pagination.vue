<template>

    <nav>
        <ul class="pagination" v-if="pagination.lastPage > 0" :class="sizeClass">
            <li v-if="showPrevious()" class="pointer" :class="{ 'disabled' : pagination.currentPage <= 1 }">
                <a v-if="pagination.currentPage > 1 " :aria-label="config.ariaFirst" v-on:click.prevent="changePage(1)">
                    <span aria-hidden="true">{{ config.ariaFirst }}</span>
                </a>

                <a v-if="pagination.currentPage <= 1">
                    <span aria-hidden="true">{{ config.previousText }}</span>
                </a>

                <a v-if="pagination.currentPage > 1 " :aria-label="config.ariaPrevioius" v-on:click.prevent="changePage(pagination.currentPage - 1)">
                    <span aria-hidden="true">{{ config.previousText }}</span>
                </a>
            </li>
            <li v-for="num in array" class="pointer" :class="{ 'active': num === pagination.currentPage }">
                <a v-on:click.prevent="changePage(num)">{{ num }}</a>
            </li>
            <li v-if="showNext()" class="pointer" :class="{ 'disabled' : pagination.currentPage === pagination.lastPage || pagination.lastPage === 0 }">
                <a v-if="pagination.currentPage === pagination.lastPage || pagination.lastPage === 0">
                    <span aria-hidden="true">{{ config.nextText }}</span>
                </a>

                <a v-if="pagination.currentPage < pagination.lastPage" :aria-label="config.ariaNext" v-on:click.prevent="changePage(pagination.currentPage + 1)">
                    <span aria-hidden="true">{{ config.nextText }}</span>
                </a>

                <a v-if="pagination.lastPage !== pagination.currentPage" :aria-label="config.ariaLast" v-on:click.prevent="changePage(pagination.lastPage)">
                    <span aria-hidden="true">{{ config.ariaLast }}</span>
                </a>
            </li>
        </ul>
    </nav>

</template>

<script type="text/babel">
    export default {
        data() {
            return {}
        },
        components: {},
        props: {
            pagination: {
                type: Object,
                required: true
            },
            callback: {
                type: Function,
                required: true
            },
            options: {
                type: Object
            },
            size: {
                type: String
            }
        },
        computed: {
            array () {
                if (this.pagination.lastPage <= 0) {
                    return []
                }

                let from = this.pagination.currentPage - this.config.offset
                if (from < 1) {
                    from = 1
                }

                let to = from + (this.config.offset * 2)
                if (to >= this.pagination.lastPage) {
                    to = this.pagination.lastPage
                }

                let arr = []
                while (from <= to) {
                    arr.push(from)
                    from++
                }

                return arr
            },
            config () {
                return Object.assign({
                    offset: 3,
                    ariaPrevious: 'Previous',
                    ariaNext: 'Next',
                    ariaFirst: 'First',
                    ariaLast: 'Last',
                    previousText: '«',
                    nextText: '»',
                    alwaysShowPrevNext: false
                }, this.options)
            },
            sizeClass () {
                if (this.size === 'large') {
                    return 'pagination-lg'
                } else if (this.size === 'small') {
                    return 'pagination-sm'
                } else {
                    return ''
                }
            }
        },
        methods: {
            showPrevious () {
                return this.config.alwaysShowPrevNext || this.pagination.currentPage > 1
            },
            showNext () {
                return this.config.alwaysShowPrevNext || this.pagination.currentPage < this.pagination.lastPage
            },
            changePage (page) {
                if (this.pagination.currentPage === page) {
                    return
                }

                this.callback(page)
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .pointer {
        cursor: pointer;
    }
</style>