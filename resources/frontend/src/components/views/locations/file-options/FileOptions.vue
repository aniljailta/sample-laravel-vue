<template>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-condensed table-hover">
            <thead>
            <tr>
                <th>File</th>
                <th>Verified Lat</th>
                <th>Verified Long</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="file in files"
                    is="file-item"
                    v-bind:file-option="file"
                    :key="file.id">
                </tr>
                <tr v-show="files.length == 0">
                    <td class="text-center" colspan="4">No file </td>
                </tr>
            </tbody>
        </table>
    </div>

</template>

<script type="text/babel">
    import FileItem from './FileItem.vue'

    export default {
        name: 'file-viewer',
        components: {
            FileItem
        },
        props: {
            files: {
                default() { return [] }
            }
        },
        methods: {
        }
    }
</script>

<style type="text/css">
</style>