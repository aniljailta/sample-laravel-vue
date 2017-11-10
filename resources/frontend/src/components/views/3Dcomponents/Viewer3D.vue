<template>
    <div class="col-xs-12 plr-none">
        <data-process v-bind:with_loader="true" v-bind:process="dataProcess" :mode="'row'"></data-process>
        <div class="row" v-show="loaded">
            <div class="col-md-7">
                <div class="row" v-if="loaded">
                    <div class="text-center col-md-12" style="margin-bottom:10px;">
                        <div class="btn-group">
                            <button class="view-button btn btn-primary" data-type="front" @click="setView('front')">Front</button>
                            <button class="view-button btn btn-success" data-type="left" @click="setView('left')">Left</button>
                            <button class="view-button btn btn-info" data-type="back" @click="setView('back')">Back</button>
                            <button class="view-button btn btn-danger" data-type="right" @click="setView('right')">Right</button>
                        </div>
                            
                            <button class="view-button btn btn-warning" data-type="top" @click="setView('top')">Plan</button>
                         <!-- 
                         <div class="btn-group">    
                            <button class="view-button btn btn-primary" @click="save()">Save</button>
                            <button class="view-button btn btn-success" @click="load()">Load</button>
                            <!-- <button class="view-button btn btn-info" @click="fullScreen()">Full Screen</button> 
                        </div>-->
                    </div>
                </div>
                <transition name="fade">
                    <viewer v-show="buildingModel"></viewer>
                </transition>    
                <div class="placeholder" v-if="!buildingModel" >
                    <h4 class="placeholderinner" v-if="!buildingStyle">Select a style to get started</h4>
                    <div v-if="buildingStyle" class="placeholderinner"> 
                        <img class="item" v-bind:src="buildingStyle.iconPath" v-bind:alt="buildingStyle.name">
                        <div class="text-center divtext"> 
                            {{ buildingStyle.name }}
                        </div>
                    </div>        
                </div>
            </div>
            <div class="col-md-5" v-if="loaded">
                <div class="row fix-height">
                    <div class="list-group-item item-heading">
                        <div class="text-left" style="padding-left:0;">
                            <h4>Total Building Price: {{ totalOptions | money }}</h4>
                        </div>
                        <div class="clearfix"></div>
                     </div>       
                    <transition name="slide-fade" mode="in-out">

                    <div class="list-group tab-section" v-if="menuitem.show">

                        <a href="#" @click="setMenu('9','Visual Environment Settings')" class="list-group-item"> Visual Environment Settings</a>

                        <a href="#" v-bind:class="{ active: buildingStyle }" @click="setMenu('10','Building Style')" class="list-group-item"> <img class="styleicontab" v-bind:src="buildingStyle.iconPath" v-bind:alt="buildingStyle.name" v-if="buildingStyle"> {{ buildingStyle ? buildingStyle.name : 'Building Style' }}  <i class="fa fa-arrow-right fa-fw pull-right" v-if="buildingStyle" aria-hidden="true"></i></a>

                        <a href="#" @click="setMenu('1','Model Dimensions')" v-if="buildingStyle" class="list-group-item" v-bind:class="{ active: this.buildingModel }"> {{this.buildingModel ? 'Dimensions: ' + this.buildingModel.width + ' x ' + this.buildingModel.length + ' ($' + this.buildingModel.shellPrice +')' : 'Model Dimensions'}} <i class="fa fa-arrow-right fa-fw pull-right" v-if="this.buildingModel" aria-hidden="true"></i></a>

                        <a href="#" v-if="buildingModel" @click="setMenu('2' ,'How Big Should My Shed Be?')" class="list-group-item" > How Big Should My Shed Be?</a>

                        <a href="#" v-if="availableCategories" v-bind:class="{ required: category.isRequired && !getDirty(category),active: getDirty(category), faded: isSetToFade(category) }" @click="setMenuDynamic( category )" class="list-group-item"  v-for="category in availableCategories">
                            {{getTitle(category)}} <sup v-if="category.isRequired && !getDirty(category)"> * required </sup>
                            <i class="fa fa-arrow-right fa-fw pull-right" v-if="getDirty(category)" aria-hidden="true"></i>
                        </a>
                    </div>
                    </transition>
                    <transition  
                        v-on:before-enter="beforeEnter" 
                        v-on:enter="enter"
                        v-on:before-leave="beforeEnter" 
                        v-on:leave="enter"
                    >
                    <panel v-if="!menuitem.show">
                        <div slot="header">
                            <div class="list-group-item" @click="setback()" v-if="menuitem.showDynamo">
                                <i class="fa fa-arrow-left fa-fw" aria-hidden="true"></i> {{menuitem.selectedcategory.name}}
                            </div>
                            <div class="list-group-item" @click="setback()" v-if="menuitem.showStat">
                                <i class="fa fa-arrow-left fa-fw" aria-hidden="true"></i> {{menuitem.heading}}
                            </div>
                        </div>
                        <div class="scroll-view"> 
                            <div class="row" v-if="menuitem.showDynamo">
                                <panel-tab-options 
                                    v-bind:validation="getValidation()"
                                    v-bind:options="options"
                                    v-bind:building-items="this.currentAvailableOptions()[menuitem.selectedcategory.name]"
                                    v-bind:building-options="buildingOptions"
                                    v-bind:building-model="buildingModel"
                                    v-bind:building-package="buildingPackage"
                                    v-bind:editable="false"
                                    >
                                </panel-tab-options>
                            </div>
                            <div class="row" v-if="menuitem.selected == '1' && menuitem.showStat">
                                 <div class="col-md-12" v-if="buildingStyle.buildingModels">
                                     <a href="#" v-for="dimension in buildingStyle.buildingModels" class="dimesions" @click="setSize(dimension.width, dimension.length,  dimension.wallHeight, dimension)">{{ dimension.width }}' x {{ dimension.length }}' 
                                        <span class="shellprice">${{ dimension.shellPrice }}</span>
                                     </a>
                                 </div>
                             </div>
                            <div class="row" v-if="menuitem.selected == '2' && menuitem.showStat">
                                <div class="col-md-3">
                                    <img data-id="2d-atv" class="item2d" draggable="true"  :src="getAsset('img', 'atv')">
                                    <div class="text-center divtext">ATV</div>
                                </div>
                                <div class="col-md-3">
                                    <img data-id="2d-bed" class="item2d" draggable="true"  :src="getAsset('img', 'bed')">
                                    <div class="text-center divtext">Bed</div>
                                </div>
                                <div class="col-md-3">
                                    <img data-id="2d-bike" class="item2d" draggable="true"  :src="getAsset('img', 'bike')">
                                    <div class="text-center divtext">Bike</div>
                                </div>
                                <div class="col-md-3">
                                    <img data-id="2d-computer_table" class="item2d" draggable="true"  :src="getAsset('img', 'computerTable')">
                                    <div class="text-center divtext">Computer table</div>
                                </div>
                                <div class="col-md-3">
                                    <img data-id="2d-croquet" class="item2d" draggable="true"  :src="getAsset('img', 'croquet')">
                                    <div class="text-center divtext">Croquet</div>
                                </div>
                                <div class="col-md-3">
                                    <img data-id="2d-kf-04" class="item2d" draggable="true"  :src="getAsset('img', 'kf-04')">
                                    <div class="text-center divtext">KF-04</div>
                                </div>
                                <div class="col-md-3">
                                    <img data-id="2d-lawn_mower" class="item2d" draggable="true"  :src="getAsset('img', 'lawnMower')">
                                    <div class="text-center divtext">Lawn Mower</div>
                                </div>
                                <div class="col-md-3">
                                    <img data-id="2d-lazyboy" class="item2d" draggable="true"  :src="getAsset('img', 'lazyboy')">
                                    <div class="text-center divtext">Lazyboy</div>
                                </div>
                                <div class="col-md-3">
                                    <img data-id="2d-office_desk" class="item2d" draggable="true"  :src="getAsset('img', 'officeDesk')">
                                    <div class="text-center divtext">Office Desk</div>
                                </div>
                                <div class="col-md-3">
                                    <img data-id="2d-ping_pong" class="item2d" draggable="true"  :src="getAsset('img', 'pingPong')">
                                    <div class="text-center divtext">Ping Pong</div>
                                </div>
                                <div class="col-md-3">
                                    <img data-id="2d-sofa1" class="item2d" draggable="true"  :src="getAsset('img', 'sofa1')">
                                    <div class="text-center divtext">Sofa1</div>
                                </div>
                                <div class="col-md-3">
                                    <img data-id="2d-sofa2" class="item2d" draggable="true"  :src="getAsset('img', 'sofa2')">
                                    <div class="text-center divtext">Sofa2</div>
                                </div>      
                                <div class="col-md-3">
                                    <img data-id="2d-toolbox" class="item2d" draggable="true"  :src="getAsset('img', 'toolbox')">
                                    <div class="text-center divtext">Toolbox</div>
                                </div>
                                <div class="col-md-3">
                                    <img data-id="2d-TV" class="item2d" draggable="true"  :src="getAsset('img', 'tv')">
                                    <div class="text-center divtext">TV</div>
                                </div>
                                <div class="col-md-3">
                                    <img data-id="2d-wagon" class="item2d" draggable="true"  :src="getAsset('img', 'wagon')">
                                    <div class="text-center divtext">Wagon</div>
                                </div>
                                <div class="col-md-3">
                                    <img data-id="2d-wheel_barrow" class="item2d" draggable="true"  :src="getAsset('img', 'wheelBarrow')">
                                    <div class="text-center divtext">Wheel Barrow</div>
                                </div>      
                                <div class="col-md-3">
                                    <img data-id="2d-work_bench" class="item2d" draggable="true"  :src="getAsset('img', 'workBench')">
                                    <div class="text-center divtext">Work bench</div>
                                </div>
                            </div>
                            <div class="row" v-if="menuitem.selected == '9' && menuitem.showStat">
                                <div class="col-md-12">
                                    <div>
                                    <input type="checkbox" class="show-environemnt" @change="showEnvironment($event)"> Show Environment </div>
                                </div>
                                <div class="envdiv">
                                    <div class="col-md-9">
                                        <input class="grass-scale form-control" :value="grassscale" placeholder="Grass scale, default is 1">
                                    </div>
                                    <div class="col-md-3">
                                        <button class="grass-scale-set btn btn-primary" @click="grassScale">Set</button>
                                    </div>
                                </div>
                                <div class="envdiv">
                                    <div class="col-md-9">
                                        <input class="grass-count form-control" size="35" :value="grasscount" placeholder="Grass amount, default is 200000">
                                    </div> 
                                    <div class="col-md-3">
                                        <button class="grass-count-set btn btn-primary" @click="grassCount">Set</button>
                                    </div>
                                </div>                           
                            </div>
                            <div class="row need-to-align" v-if="menuitem.selected == '10' && menuitem.showStat" >
                                <div class="col-md-3" v-for="style in buildingStyles" v-on:click="setStyle(style)" v-if="style['3dModel'] && style['3dModel'].dataId">
                                    <img class="item" v-bind:src="style.iconPath" v-bind:alt="style.name">
                                    <div class="text-center divtext"> 
                                        {{ style.name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </panel>
                    </transition>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
/*global Viewer3D*/
import Accordion from 'vue-strap/src/Accordion.vue'
import panel from 'vue-strap/src/Panel.vue'
import PanelTabOptions from './PanelTabOptions.vue'
import customBuildOptionsValidation from 'src/validations/dealer-order-form/custom-build-options.js'
import {mapGetters, mapActions} from 'vuex'
import manageOptionPickerDataMixin from 'src/components/ui/Optionpicker/manage-data-mixin.js'

import DataProcessMixin from 'src/mixins/vue-data-process'
import DataProcess from 'src/components/ui/DataProcess.vue'

export default {
    mixins: [manageOptionPickerDataMixin, DataProcessMixin],
    components: {
        'viewer': {template: ' <div class="viewers"></div>'},
        Accordion,
        panel,
        PanelTabOptions,
        DataProcess
    },
    data () {
        return {
            dataProcess: {
                type: 'data',
                text: 'Initializing 3D Model...',
                running: true,
                error: null,
                success: null
            },
            assets: {
                fonts: {},
                models: {},
                img: {}
            },
            viewerPath: window.Viewer3DPath || 'https://usc3d.urbanshedconcepts.com/',
            loaded: false,
            mainColor: '#b5001a',
            secondaryColor: '#ffffff',
            checked: false,
            selected: 'info',
            first: 'primary',
            types: ['default', 'primary', 'info', 'success', 'warning', 'danger', 'top'],
            grassscale: '',
            grasscount: '',
            menuitem: {
                show: true
            },
            availableCategories: {},
            buildingOptions: [],
            alias: {
                buildingOptions: 'buildingOptions'
            },
            buildingItems: [],
            sortOrderKeys: []
        }
    },
    created() {
        window.Viewer3DPath = this.viewerPath
        this.loadScript()
        this.buildingOptions = _.cloneDeep(this.customBuildOptions)
    },
    updated() {
        this.loaddrag()
    },
    computed: {
        ...mapGetters({
            buildingStyles: 'dealerOrderForm/styles/list',
            buildingStyle: 'dealerOrderForm/orderBuildingStyle',
            options: 'dealerOrderForm/options/list',
            optionCategories: 'dealerOrderForm/options/categories',
            customBuildOptions: 'dealerOrderForm/orderCustomBuildOptions',
            buildingPackage: 'dealerOrderForm/orderBuildingPackage',
            buildingModel: 'dealerOrderForm/orderBuildingDimension',
            threedViewer: 'dealerOrderForm/order3dViewer'
        }),
        totalOptions () {
            if (!this.buildingModel) return 0
            if (this.buildingOptions.length === 0) {
                return this.buildingModel.shellPrice
            }
            return _.reduce(this.buildingOptions, function (memo, option) {
                return memo + (option.unitPrice * option.quantity)
            }, 0, this) + this.buildingModel.shellPrice
        }
    },
    methods: {
        ...mapActions({
            updateThreedViewerOrder: 'dealerOrderForm/updateThreedViewerOrder',
            updateOrderBuilding: 'dealerOrderForm/updateOrderBuilding'
        }),
        loadScript() {
            let self = this
            let srtipeScript = document.createElement('link')
            srtipeScript.setAttribute('rel', 'stylesheet')
            srtipeScript.setAttribute('type', 'text/css')
            srtipeScript.setAttribute('href', this.viewerPath + 'css/viewer3D.css')
            document.getElementsByTagName('head')[0].appendChild(srtipeScript)
            srtipeScript = document.createElement('script')
            srtipeScript.setAttribute('type', 'text/javascript')
            srtipeScript.setAttribute('src', this.viewerPath + 'js/viewer3D.js')
            document.getElementsByTagName('head')[0].appendChild(srtipeScript)

            function onLoad() {
                self.$nextTick(() => {
                    self.initViewer()
                })
            }

            srtipeScript.onload = onLoad
            srtipeScript.onreadystatechange = function() {
                if (this.readyState === 'complete') {
                    onLoad()
                }
            }

            this.$http.get(this.viewerPath + 'assets.json').then(response => {
                this.assets = response.data
            })
        },
        getValidation() {
            return customBuildOptionsValidation
        },
        initViewer() {
            let viewer3D = new Viewer3D(600, 400)
            this.$refs.viewer3D = viewer3D
            this.$refs.el = viewer3D.element
            $('.viewers').append(this.$refs.el)
            viewer3D.addEventListener('change', (e) => {
                this.checkContent(viewer3D.save())
                this.updateThreedViewerOrder({
                    threedoptions: JSON.stringify({shed: viewer3D.save()})
                })
            })
            viewer3D.addEventListener('progress', (e) => {
                this.dataProcess.text = 'Loading 3D Model ' + (e.loaded / e.total * 100).toFixed(2) + '%'
                if (e.loaded === e.total) {
                    this.$emit('data-ready')
                    this.loaded = true
                }
            })
            viewer3D.addEventListener('ready', (e) => {
                this.loadColor()
            })
            if (_.isUndefined(this.threedViewer) || this.threedViewer === null) return
            let shedExists = JSON.parse(this.threedViewer)
            this.$refs.viewer3D.load(shedExists.shed)
            this.availableCategories = this.currentAvailableCategories()
            if (this.availableCategories) {
                new Promise(resolve => {
                    this.buildingOptions = _.cloneDeep(this.customBuildOptions)
                    resolve()
                }).then(() => {
                   this.validateBuilding()
                })
            }
        },
        loadColor() {
            if (_.isUndefined(this.threedViewer) || this.threedViewer === null) return
            let sidingItem = this.filterItem('Siding')
            if (sidingItem && sidingItem.color) {
                this.setColor(sidingItem.color.hex, sidingItem.color.name)
            }
            let trimItem = this.filterItem('Trim')
            if (trimItem && trimItem.color) {
                this.setSecondaryColor(trimItem.color.hex, trimItem.color.name)
            }
        },
        filterItem(name) {
                let option = _.filter(this.buildingOptions, function (item) {
                    return item.category.name === name
                }, this)
                if (!option.length) return undefined
                return option[0]
        },
        checkContent(content) {
            let array = ['Door', 'Window', 'Deck']
            if (!this.menuitem.selectedcategory) return
            if (_.includes(array, this.menuitem.selectedcategory.name)) {
                if (content.doors.length > 0) {
                    this.removeBeforeAddingOption('Door', content.doors)
                } else {
                    this.checkForInitial('Door')
                }
                if (content.windows.length > 0) {
                    this.removeBeforeAddingOption('Window', content.windows)
                } else {
                    this.checkForInitial('Window')
                }
                if (content.decks.length > 0) {
                    this.removeBeforeAddingOption('Deck', content.decks)
                } else {
                    this.checkForInitial('Deck')
                }
            }
            this.validateBuilding()
        },
        checkForInitial(category) {
            var selectedOption = _.find(this.buildingOptions, function (el) {
                return el.category.name === category
            }, this)
            if (typeof selectedOption !== 'undefined') {
                this.updateByCategory(category)
            }
        },
        grassScale() {
            this.$refs.viewer3D.environment.grassScale = this.grassscale
        },
        grassCount() {
            this.$refs.viewer3D.environment.grassCount = this.grasscount
        },
        showFlowerBox($event) {
            this.$refs.viewer3D.shed.showFlowerBoxes = $event.target.checked
        },
        showShutter($event) {
            this.$refs.viewer3D.shed.showShutters = $event.target.checked
        },
        showEnvironment($event) {
            this.$refs.viewer3D.environment.enabled = $event.target.checked
        },
        setSize(width, depth, height, buildingModel) {
            this.updateOrderBuilding({
                'buildingDimension': buildingModel
            })
            this.availableCategories = this.currentAvailableCategories()
            if (this.availableCategories) {
                new Promise(resolve => {
                    this.resetShed()
                    resolve()
                }).then(() => {
                    // get available default options
                    let availableOptions = _.filter(this.options, function (item) {
                        return _.includes(_.map(item.allowableModels, 'id'), buildingModel.id)
                    }, this)
                    // set default options
                    let extraProps = {}
                    for (let item of availableOptions) {
                        if (item.isDefault === 1) {
                            if (item.forceQuantity && item.forceQuantity === 'building_length') {
                                extraProps.minQuantity = this.buildingModel.length
                                extraProps.quantity = this.buildingModel.length
                            }
                            if (item.defaultColor) {
                                item.color = item.defaultColor
                            }
                            this.addOption(item, extraProps)
                        }
                    }
                })
            }
            let style = this.buildingStyle['3dModel'].dataId
            this.$refs.viewer3D.shed.setSize(width, depth, height, style)
        },
        setColor(color, name) {
            this.mainColor = color
            this.menuitem.siding = name
            this.$refs.viewer3D.shed.setColor(color, this.secondaryColor.toString())
        },
        setSecondaryColor(color, name) {
            this.secondaryColor = color
            this.menuitem.trim = name
            this.$refs.viewer3D.shed.setColor(this.mainColor.toString(), color)
        },
        setRoof(roofColor) {
            this.menuitem.roof = roofColor
            this.$refs.viewer3D.shed.roof.color = roofColor
        },
        setView(viewPerspective) {
            this.$refs.viewer3D.perspective = viewPerspective
        },
        save() {
            this.download('usc3d.json', JSON.stringify(this.$refs.viewer3D.save()))
        },
        load: function () {
            let $fileInput = $('<input>').attr({style: 'display:none;', type: 'file'}).appendTo($('body'))
            $fileInput.click()
            $fileInput.on('change', (e) => {
                let reader = new FileReader()
                reader.onload = (e) => {
                    this.$refs.viewer3D.load(JSON.parse(e.target.result))
                }
                reader.readAsText($fileInput[0].files[0])
                $fileInput.remove()
            })
        },
        download(filename, text) {
            var element = document.createElement('a')
            element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text))
            element.setAttribute('download', filename)
            element.style.display = 'none'
            document.body.appendChild(element)
            element.click()
            document.body.removeChild(element)
        },
        loaddrag() {
            let items = document.getElementsByClassName('item')
            let items2D = document.getElementsByClassName('item2d')
            _.each([].slice.call(items).concat([].slice.call(items2D)), (element) => {
                element.addEventListener('touchstart', (e) => {
                    let event = new DragEvent('dragstart', {dataTransfer: new DataTransfer()})
                    window.mainDragEvent = event
                    element.dispatchEvent(event)
                })
                element.addEventListener('dragstart', (e) => {
                    e.dataTransfer.setData(e.currentTarget.getAttribute('data-id'), '')
                    var crt = document.createElement('div')
                    crt.style.display = 'none'
                    e.dataTransfer.setDragImage(crt, 0, 0)
                }, false)
            })
        },
        getAsset(type, asset) {
            return this.viewerPath + type + '/' + this.assets[type][asset]
        },
        setMenu(type, head) {
            this.menuitem.showStat = true
            this.menuitem.showDynamo = false
            this.menuitem.show = false
            this.menuitem.selected = type
            this.menuitem.heading = head
            if (type === '1') {
                if (!this.buildingStyle) {
                    this.menuitem.selected = '10'
                    this.menuitem.heading = 'Building Style'
                }
            }
            if (type === '2') {
                this.trackSortId(0)
                this.$refs.viewer3D.perspective = 'top'
            }
        },
        setMenuDynamic(category) {
            this.menuitem.showDynamo = true
            this.menuitem.showStat = false
            this.menuitem.selected = category.name
            this.menuitem.selectedcategory = category
            this.menuitem.show = false
            let index = this.findIndexkey(category.name)
            this.trackSortId(index)
            if (category.name === 'Interior') {
                this.$refs.viewer3D.perspective = 'top'
            }
        },
        validateBuilding() {
            let requireCategoryArray = []
            _.each(this.availableCategories, function(category) {
                if (category.isRequired) {
                    requireCategoryArray.push(category.name)
                }
            })
            let selectedCategory = []
            _.each(this.buildingOptions, function(option) {
                selectedCategory.push(option.category.name)
            })
            if (selectedCategory.length > 0) {
                if (selectedCategory.filter(function (elem) {
                    return requireCategoryArray.indexOf(elem) > -1
                }).length === requireCategoryArray.length) {
                    this.$parent.threedPass = 'yes'
                } else {
                    this.$parent.threedPass = 'no'
                }
            } else {
                this.$parent.threedPass = 'no'
            }
        },
        setback() {
            this.menuitem.show = true
            if (this.menuitem.selected === '2' || this.menuitem.selected === 'Interior') {
                this.$refs.viewer3D.perspective = 'front'
            }
            let saveobject = this.$refs.viewer3D.save()
            this.menuitem.doors = saveobject.doors.length
            this.menuitem.decks = saveobject.decks.length
            this.menuitem.windows = saveobject.windows.length
            this.validateBuilding()
        },
        setStyle(style) {
            this.updateOrderBuilding({
                'buildingStyle': style,
                'buildingDimension': null
            })
            this.availableCategories = {}
            this.resetShed()
            this.setback()
        },
        resetShed() {
            this.buildingOptions = []
        },
        beforeEnter(el) {
            el.style.opacity = 0
        },
        enter: function (el) {
          setTimeout(function() { el.style.opacity = 1 }, 600)
        },
        commonWay(category) {
            if (this.buildingOptions.length > 0) {
                let price = []
                _.each(this.buildingOptions, function (option) {
                    if (option.category.name === category.name) {
                        price.push(option.unitPrice * option.quantity)
                    }
                })
                return price
            }
        },
        getColorName(category) {
            if (this.buildingOptions.length > 0) {
                let color = {}
                _.each(this.buildingOptions, function (option) {
                    if (option.category.name === category.name) {
                        color = option
                    }
                })
                return color
            }
        },
        getTitle: function(category) {
            if (this.buildingOptions.length > 0) {
                let price = this.commonWay(category)
                let color = this.getColorName(category)
                let colorName = color && color.color ? ' (' + color.color.name + ')' : ''
                let itemcount = price.length
                price = price.reduce((a, b) => a + b, 0)
                if (itemcount > 0) {
                    return category.name + ' (' + itemcount + ') ($' + price + ')' + colorName
                }
            }
            return category.name + ' Options'
        },
        getDirty(category) {
            if (this.buildingOptions.length > 0) {
                let itemcount = this.commonWay(category).length
                if (itemcount > 0) {
                    return true
                }
            }
            return false
        },
        trackSortId(key) {
            if (this.sortOrderKeys.indexOf(key) === -1) {
                this.sortOrderKeys.push(key)
            }
        },
        findIndexkey(name) {
           return _.findIndex(this.availableCategories, { 'name': name })
        },
        isSetToFade(category) {
            let index = this.findIndexkey(category.name)
            if (this.sortOrderKeys.indexOf(index) === -1) {
                return true
            }
        },
        getViews() {
           return this.$refs.viewer3D.getImages()
        },
        buildingModelID() {
                if (_.isUndefined(this.buildingModel.id)) return null
                return _.toInteger(this.buildingModel.id)
        },
        currentAvailableCategories() {
            if (this.buildingModelID() == null) return []

            var vm = this
            var currentAvailableCategories = {}

            _.each(this.currentAvailableOptions(), function (item, key) {
                let optionCategory = _.find(vm.optionCategories, { name: key })
                if (optionCategory && optionCategory.group !== 'order') {
                    // Count number of options per category
                    // ( empty object with assigning prop (!important for vue) )
                    currentAvailableCategories[key] = Object.assign({}, optionCategory, {count: item.length})
                }
            }, this)

            return _.orderBy(currentAvailableCategories, 'sortId')
        },
        currentAvailableOptions() {
            if (this.buildingModelID() == null) return []

            var buildingModelID = this.buildingModelID()
            var currentAvailableOptions = _.filter(this.options, function (item) {
                return _.includes(_.map(item.allowableModels, 'id'), buildingModelID)
            }, this)

            return _.groupBy(currentAvailableOptions, option => option.category.name)
        }
    }
}
</script>
<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    
    a.list-group-item {
        text-transform: capitalize;
    }

    .envdiv {
        margin-top: 10px;
        width: 100%;
        float: left;
    }
    
    .viewer-3d {
        display: inline-block;
    }

    .item, .item2d {
        width: 100px;
        height: 100px;
        display: inline-block;
        vertical-align: bottom;
        padding: 10px;
        font-size: 12px;
        margin-bottom: 15px;
        text-align: center;
    }
    .item div, .item2d div {
        width: 100%;
        height: 100%;
        background-size: cover;
    }

    .item-heading {
        top: -66px;
        position: absolute;
        width: 100%;
        font-weight: bold;
    }

    .item img {
        width: 100px;
        height: 100px;
    }

    img.roof-item {
        width: 75px;
        cursor: pointer;
    }

    div.roof-item {
        width: 75px;
        height: 75px;
        display: inline-block;
        cursor: pointer;
    }

    img {
        width: 100%;
    }

    .divtext {
        color: #5d5d5d;
        font-size: 12px;
    }

    .btnset {
        background-color: #5d5d5d;
        color: #fff;
    }

    div.colors-item, div.trim-colors-item {
        width: 110px;
        height: 55px;
        display: inline-block;
        cursor: pointer;
        margin: 5px;
    }

    .label-default.rooftxt {
        font-size: 16px;
    }

    .scroll-view {
        height: 400px;
        overflow-x: hidden;
    }

    .plr-none {
        padding-bottom: 30px;
    }

    .fix-height {
        height:500px;
        overflow:hidden;
    }
    
    .need-to-align .col-md-3 {
        height: 140px;
        cursor: pointer;
    }

    a.list-group-item{
        margin-bottom: 5px;
    }

    .slide-fade-enter-active {
        transition: all .4s ease;
    }

    .slide-fade-leave-active {
        transition: all .4s ease-in;
    }

    .slide-fade-enter, .slide-fade-leave-to {
        transform: translateX(10px);
        opacity: 0.2;
    }

    .fade-enter-active, .fade-leave-active {
        transition: all .4s ease-in-out;
    }

    .fade-enter, .fade-leave-to {
        transform: translateX(10px);
        opacity: 0.2
    }

    .list-group-item.active, .list-group-item.active:focus, .list-group-item.active:hover {
        z-index: 2;
        color: #337ab7;
        background-color: #fff;
        border-color: #337ab7;
    }
    a.list-group-item.required {
        background: #ae0a24;
        color: #fff;
    }
    a.dimesions {
        padding: 5px;
        margin: 2px;
        float: left;
        text-align: center;
        border: 1px solid #f0f1e9;
        background: whitesmoke;
        color: #444;
        width: 85px;
    }
    span.shellprice {
        width: 100%;
        float: left;
        text-align: center;
        font-size: 12px;
        color: #5cb85c;
    }
    .placeholderinner {
        padding-top: 175px;
        text-align: center;
        margin: 0 auto;
        padding-bottom: 90px;
    }

    .styleicontab {
        height: 18px;
        width: 18px;
        top: -4px;
        position: relative;
    }

    .tab-section{
        height: 490px;
        overflow: auto;
    }

    .faded{
        opacity: 0.4;
    }

</style>

<style type="text/css" lang="scss" rel="stylesheet/scss">
    #dealer-order-form .list-group-item.item-heading h4{
        font-weight: 500 !important;
    }
</style>    
