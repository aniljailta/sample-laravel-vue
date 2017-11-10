<template>
    <div id="userbox" class="userbox" :class="{'open': opened}" @click="toggle" v-click-outside="close">

        <a>
            <figure class="profile-picture">
                <img src="/images/user_blue.png">
            </figure>
            <div class="profile-info">
                <span class="name">{{ user.fullName }}</span>
                <span class="role">{{ role.displayName }}</span>
            </div>

            <i class="fa custom-caret"></i>
        </a>

        <div class="dropdown-menu">
            <ul class="list-unstyled">
                <li class="divider"></li>
                <li><a role="menuitem"><i class="fa fa-user"></i> My Profile</a></li>
                <li><a role="menuitem" href="/logout"><i class="fa fa-power-off"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</template>

<script type="text/babel">
    import {mapGetters} from 'vuex'
    import ClickOutside from 'src/directives/click-outside'
    export default {
        data() {
            return {
                opened: false
            }
        },
        directives: {ClickOutside},
        components: {},
        computed: {
            ...mapGetters({
                'user': 'user/user',
                'role': 'user/role'
            })
        },
        methods: {
            toggle() {
                this.opened = !this.opened
            },
            close() {
                this.opened = false
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>