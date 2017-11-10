import {maxLength, required} from 'vuelidate/lib/validators'

export default function(vm) {
    let rules
    if (this.color === null) {
        rules = {
            color: {required}
        }
    }

    if (this.color !== null) {
        rules = {
            color: {
                name: {
                    maxLength: maxLength(50)
                }
            }
        }
    }

    return rules
}
