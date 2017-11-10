// import Vue from 'vue'

export default {
    data() {
        return {
            dataProcess: {
                type: 'data',
                text: null,
                running: true,
                error: null,
                success: null
            }
        }
    },
    created() {
        this.$on('data-process', this.dataProcessChange)
        this.$on('data-process-update', this.dataProcessUpdate)
        this.$on('data-process-reset', this.dataProcessReset)
        this.$on('data-ready', this.dataReady)
        this.$on('data-failed', this.dataFailed)
        this.$on('data-all-ready', this.dataAllReady)
    },
    methods: {
        run({ text, type }) {
            let params = {
                running: true,
                text: text || null,
                error: null,
                success: null
            }

            if (!_.isUndefined(type)) params.type = type
            this.$emit('data-process-update', params)
        },
        dataAllReady() {},
        dataProcessChange(process) {
            this.dataProcess = process
        },
        dataProcessUpdate(process) {
            this.$emit('data-process', { ...this.dataProcess, ...process })
        },
        dataProcessReset() {
            this.dataProcess = {
                text: null,
                running: null,
                error: null,
                success: null
            }
        },
        dataReady(params = {}) {
            this.$emit('data-process', { ...this.dataProcess, ...{
                running: false,
                error: null,
                success: null
            }, ...params})
            this.$emit('data-all-ready')
        },
        dataFailed(response) {
            // TODO: STANDARTIZE ALL RESPONSES
            let message
            if (response.message || response.msg) {
                message = response.message || response.msg
            } else if (response.data) {
                if (response.data.message || response.data.msg) {
                    message = response.data.message || response.data.msg
                } else {
                    message = response.data
                }
            } else if (response.statusText) {
                message = response.statusText
            } else {
                message = response
            }

            this.$emit('data-process-update', {
                running: false,
                error: message,
                success: null
            })
        }
    }
}
