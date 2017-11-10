<template>

    <div>
        <g-map :center="center"
               :zoom="7"
               :map-type-id="mapTypeId"
               ref="map">

            <g-marker
                    v-for="(marker, index) in markers"
                    :key="index"
                    :position="marker.position"
                    :icon="marker.icon"
                    :clickable="true" @click="toggleMarker(marker, index)"
                    :draggable="false">
                <g-info-window :opened="markers[index].showInfo" @closeclick="toggleMarker(marker, index)">
                    <dealer-window :item="marker" v-on:select-dealer="selectDealer"></dealer-window>
                </g-info-window>
            </g-marker>

        </g-map>
    </div>
</template>

<script type="text/babel">
    import DealerWindow from './DealerWindow.vue'
    import {
        load,
        Map as GMap,
        Marker as GMarker,
        InfoWindow as GInfoWindow
    } from 'vue2-google-maps/src/main'

    load({
        key: 'AIzaSyAMjr8cAOysQMwsiEkBjLlROkE1KWkwnXk'
        // libraries: ['places']
    })

    export default {
        data () {
            return {
                center: {
                    lat: 33.456651,
                    lng: -112.064666
                },
                mapTypeId: 'roadmap',
                markers: []
            }
        },
        components: {
            GMap,
            GMarker,
            GInfoWindow,
            DealerWindow
        },
        props: {
            show: {
                type: Boolean
            },
            dealers: {}
        },
        created() {
            this.$watch('dealers', this.createMarkers)
        },
        methods: {
            selectDealer(dealer) {
                this.$emit('select-dealer', dealer.id)
            },
            createMarkers() {
                let markers = []
                _.each(this.dealers, function(dealer, key) {
                    let location = dealer.location || null

                    if (location && location.latitude && location.longitude) {
                        markers.push({
                            showInfo: false,
                            position: {
                                lat: location.latitude,
                                lng: location.longitude
                            },
                            name: location.name,
                            title: dealer.businessName,
                            dealer: dealer,
                            location: location,
                            icon: { url: '/images/map/marker.png' }
                        })
                    }
                })

                this.markers = markers
            },
            toggleMarker(marker, index) {
                if (!marker.showInfo) {
                    this.center = marker.position
                }
                marker.showInfo = !marker.showInfo
            }
        },
        watch: {
            show(newVal, oldVal) {
                this.$refs.map.resizePreserveCenter()
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .vue-map-container {
        width: 100%;
        height: 500px;
        display: block;
    }
</style>