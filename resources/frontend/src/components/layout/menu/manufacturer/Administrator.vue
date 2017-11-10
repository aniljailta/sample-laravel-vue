<template>
    <ul class="nav nav-main">

        <li :class="{'nav-active': $route.name === 'dashboard'}">
            <router-link :to="{name: 'dashboard'}"><i class="fa fa-home"></i> <span>Dashboard</span></router-link>
        </li>
        <li :class="{'nav-active': $route.name === 'buildings'}">
            <router-link :to="{name: 'buildings'}"><i class="fa fa-wrench"></i> <span>Buildings</span></router-link>
        </li>
        <li :class="{'nav-active': $route.name === 'orders'}">
            <router-link :to="{name: 'orders'}"><i class="fa fa-file-text-o"></i> <span>Orders</span></router-link>
        </li>
        <li :class="{'nav-active': $route.name === 'sales'}">
            <router-link :to="{name: 'sales'}"><i class="fa fa-file-text"></i> <span>Sales</span></router-link>
        </li>
        <li :class="{'nav-active': $route.name === 'price-groups'}">
            <router-link :to="{name: 'price-groups'}"><i class="fa fa-money"></i> <span>Price Groups</span></router-link>
        </li>

        <!-- TODO: WIP 239?
        <v-nav @refresh="refreshScroller" ref="navDeliveries" :children="navDeliveries">
            <a slot="parent" @click="$refs.navDeliveries.toggle()"><i class="fa fa-car"></i> <span>Deliveries</span></a>
            <ul slot="children" class="nav nav-children">
                <li :class="{'nav-active': $route.name === 'deliveries'}">
                    <router-link :to="{name: 'deliveries'}"><i class="fa fa-truck"></i> <span>Deliveries</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'delivery-trucks'}">
                    <router-link :to="{name: 'delivery-trucks'}"><i class="fa fa-truck"></i> <span>Trucks</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'delivery-trailers'}">
                    <router-link :to="{name: 'delivery-trailers'}"><i class="fa fa-truck"></i> <span>Trailers</span></router-link>
                </li>
            </ul>
        </v-nav>
        -->

        <v-nav @refresh="refreshScroller" ref="navReports" :children="navReports">
            <a slot="parent" @click="$refs.navReports.toggle()"><i class="fa fa-bar-chart"></i> <span>Reports</span></a>
            <ul slot="children" class="nav nav-children">
                <li :class="{'nav-active': $route.name === 'reports.dealer-inventory'}">
                    <router-link :to="{name: 'reports.dealer-inventory'}"><span>Dealer Inventory</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'reports.orders'}">
                    <router-link :to="{name: 'reports.orders'}"><span>Orders</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'reports.sales'}">
                    <router-link :to="{name: 'reports.sales'}"><span>Sales</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'reports.lead-contacts'}">
                    <router-link :to="{name: 'reports.lead-contacts'}"><span>Lead and Closing Reports</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'reports.dealer-commission'}">
                    <router-link :to="{name: 'reports.dealer-commission'}"><span>Dealer Commission</span></router-link>
                </li>
            </ul>
        </v-nav>

        <li :class="{'nav-active': $route.name === 'employees'}">
            <router-link :to="{name: 'employees'}"><i class="fa fa-users"></i> <span>Employees</span></router-link>
        </li>

        <li :class="{'nav-active': $route.name === 'customers'}">
            <router-link :to="{name: 'customers'}"><i class="fa fa-users"></i> <span>Customers</span></router-link>
        </li>

        <v-nav @refresh="refreshScroller" ref="navInvoices" :children="navInvoices">
            <a slot="parent" @click="$refs.navInvoices.toggle()"><i class="fa fa-file" aria-hidden="true"></i> <span>Invoices</span></a>
            <ul slot="children" class="nav nav-children">
                <li :class="{'nav-active': $route.name === 'invoices'}">
                    <router-link :to="{name: 'invoices'}"><span>Invoices</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'payments'}">
                    <router-link :to="{name: 'payments'}"><span>Payments</span></router-link>
                </li>
            </ul>
        </v-nav>

        <v-nav @refresh="refreshScroller" ref="navSystem" :children="navSystem">
            <a slot="parent" @click="$refs.navSystem.toggle()"><i class="fa fa-cogs"></i> <span>System</span></a>
            <ul slot="children" class="nav nav-children">
                <li :class="{'nav-active': $route.name === 'settings'}">
                    <router-link :to="{name: 'settings'}"><span>Settings</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'building-models'}">
                    <router-link :to="{name: 'building-models'}"><span>Building Models</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'building-packages'}">
                    <router-link :to="{name: 'building-packages'}"><span>Building Packages</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'building-package-categories'}">
                    <router-link :to="{name: 'building-package-categories'}"><span>Building Package Categories</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'plants'}">
                    <router-link :to="{name: 'plants'}"><span>Plants</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'style-library'}">
                    <router-link :to="{name: 'style-library'}"><span>Style Library</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'color-library'}">
                    <router-link :to="{name: 'color-library'}"><span>Color Library</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'option-library'}">
                    <router-link :to="{name: 'option-library'}"><span>Option Library</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'locations'}">
                    <router-link :to="{name: 'locations'}"><span>Locations</span></router-link>
                </li>
                <li :class="{'nav-active': $route.name === 'dealers'}">
                    <router-link :to="{name: 'dealers'}"><span>Dealers</span></router-link>
                </li>
            </ul>
        </v-nav>
    </ul>
</template>

<script type="text/babel">
    import VNav from 'src/components/ui/Nav.vue'
    export default {
        data() {
            return {
                navDeliveries: [
                    'deliveries',
                    'delivery-trucks',
                    'delivery-trailers'
                ],
                navReports: [
                    'reports.dealer-inventory',
                    'reports.orders',
                    'reports.sales',
                    'reports.lead-contacts',
                    'reports.dealer-commission'
                ],
                navInvoices: [
                    'invoices',
                    'payments'
                ],
                navSystem: [
                    'settings',
                    'building-models',
                    'building-statuses',
                    'building-packages',
                    'building-package-categories',
                    'plants',
                    'style-library',
                    'color-library',
                    'option-library',
                    'option-categories',
                    'locations',
                    'dealers'
                ]
            }
        },
        components: {
            VNav
        },
        props: {},
        methods: {
            refreshScroller() {
                this.$emit('refresh-scroller')
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>