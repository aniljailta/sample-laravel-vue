<template>

    <div class="list-totals pull-right">
        <dl class="dl-horizontal">
            <template v-for="item in list">
                <dt>Total {{ item.name }}</dt>
                <dd v-if="item.type === 'money' ">
                    <span class="label label-default">{{ filters.money(item.value) }}</span>
                </dd>
                <dd v-else>
                    <span class="label label-default">{{ item.value }}</span>
                </dd>
            </template>
        </dl>
    </div>

</template>

<script type="text/babel">
    export default {
        data() {
            return {}
        },
        props: {
            list: {
                default() {
                    return {}
                }
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
</style>