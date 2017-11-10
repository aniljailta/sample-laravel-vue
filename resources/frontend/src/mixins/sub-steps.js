/*
 *  This mixin required additional methods:
 *  nextStep()
 *  $validate()
 *  $validateDeps()
 *  This mixin required additional event listener:
 *  go-to-step(direction)
 */
export default {
    created() {
        this.$on('go-to-step', (params) => {
            // this.$parent.goToStep(params.step, params.options)
        })
    },
    methods: {
        goToStep(step, options) {
            options = options || {}

            if (step === 'next') {
                let isValid = this.$validate(this.currentStep, {$touch: true})
                if (!isValid) return false

                step = this.nextStep('next')
                if (!step) {
                    this.$emit('go-to-step', {
                        step: 'next',
                        options: options
                    })
                }
            }

            if (step === 'previous') {
                step = this.nextStep('previous')
                if (!step) {
                    this.$emit('go-to-step', {
                        step: 'previous',
                        options: options
                    })
                }
            }

            if (options && options.hasOwnProperty('validateDeps') && options.validateDeps === true) {
                let valid = this.$validateDeps(step)
                if (!valid) return false
            }

            if (step) this.currentStep = step
            return true
        },
        // get steps in reverse order
        _stepDepends(step) {
            return this.nextStep('previous', step)
        },
        $validateDeps(step) {
            let dep = step
            let deps = []
            do {
                dep = this._stepDepends(dep)
                if (dep) deps.push(dep)
            } while (dep !== null)

            if (!_.isEmpty(deps)) {
                if (!this.$validate(deps, {$touch: true})) return false
            }
            return true
        }
    }
}
