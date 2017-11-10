// import {required} from 'vuelidate/lib/validators'

export default function () {
    let settings = {
        rtoIsUsed: {},
        rtoCompanyId: {}
    }

    if (this.settings.rtoIsUsed) {
        // settings.rtoCompanyId.required = required
    }

    return {settings}
}
