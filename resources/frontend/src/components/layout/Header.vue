<template>
    <header class="header">

        <div class="logo-container header-left">
            <div class="left">
                <a href="/" class="logo" v-if="logo">
                    <img :src="logo" height="35" alt="Urban Shed Concepts">
                </a>
            </div>

            <span class="separator-left left"></span>

            <div class="company-logo left">
                <img class="right" src="images/logo-main.png" alt="Urban Shed Concepts">
                <div class="right">
                    <span>Powered by</span><br>
                    <b>The Shed App</b>
                </div>
            </div>

            <div class="visible-xs toggle-sidebar-left" @click="toggleSideBar">
                <i class="fa fa-bars"></i>
            </div>
        </div>


        <div class="header-right">
            <time-zone-info class="hidden-xs"/>
            <span class="separator"></span>
            <user-box/>
        </div>

    </header>

</template>

<script type="text/babel">
    import {mapGetters} from 'vuex'
    import TimeZoneInfo from './TimeZoneInfo.vue'
    import UserBox from './UserBox.vue'
    export default {
        data() {
            return {}
        },
        components: {TimeZoneInfo, UserBox},
        props: {
            sideBarOpened: {
                type: Boolean,
                default: false
            }
        },
        computed: {
            ...mapGetters({
                'logo': 'settings/logo'
            })
        },
        methods: {
            toggleSideBar() {
                this.$emit('update:sideBarOpened', !this.sideBarOpened)
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
    .header-left {
        float: left;
        height: 56px;
    }
    .left {
        float: left;
    }
    .right {
        float: right;
    }
    .company-logo {
        overflow: auto;
        margin-top: 8px;
    }
    .company-logo img {
        margin-left: 7px;
        height: 36px;
    }
    .company-logo b {
        color: #275582;
        font-size: 14px;
    }
    .company-logo span {
        color: #c3c3c3;
        font-size: 11px;
    }
    .separator-left {
        display: inline-block;
        height: 100%;
        margin: 0 25px 0;
        width: 2px;
        vertical-align: middle;
        background: #1d2127 -webkit-gradient(linear, left top, left bottom, color-stop(10%, #1d2127), to(#121518));
        background: #1d2127 linear-gradient(#1d2127 10%, #121518);
    }
</style>