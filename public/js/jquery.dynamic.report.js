(function( $, _ ){

    $.fn.dynamicReport = function( params ) {

        var initializeDOM = function(DOM) {
            var cachedDOM = {};
            var requiredDOM = {
                $_report_filter_area: function () { return cachedDOM['$_report_filter_area'] || $(".report_filter_area") },
                $_report_result_area: function () { return cachedDOM['$_report_result_area'] || $(".report_result_area") },

                $_report_filter_form: function () { return cachedDOM['$_report_filter_area'].find('.report_filter_form') },
                $_report_select_dimensions: function () { return cachedDOM['$_report_filter_area'].find(".select-dimensions") },
                $_report_select_conditions: function () { return cachedDOM['$_report_filter_area'].find(".select-conditions") },
                $_report_dimension_area: function () { return cachedDOM['$_report_filter_area'].find(".report-dimension-area") },
                $_report_condition_area: function () { return cachedDOM['$_report_filter_area'].find(".report-condition-area") },

                $_report_dimension_area_list: function () { return cachedDOM['$_report_dimension_area'].find(".report-dimension-area-list") },
                $_report_condition_area_list: function () { return cachedDOM['$_report_condition_area'].find(".report-condition-area-list") },

                $_report_result_msg_area: function () { return cachedDOM['$_report_result_area'].find(".report_result_msg_area") },
                $_report_button_run: function () { return cachedDOM['$_report_filter_area'].find(".button-run-report") },

            };

            $.each(requiredDOM, function(index, initialize) {
                if ( typeof DOM[index] !== 'undefined' )
                    cachedDOM[index] = DOM[index];
                else
                    cachedDOM[index] = initialize();
            });

            return cachedDOM;
        };

        var initializeTemplates = function(templates) {
            var cachedTemplates = {};
            var requiredTemplates = {
                dimension_area_item: function () {
                    return _.template($('#report-dimension-area-item').html(), {variable: 'o'});
                },
                condition_area_item_select: function () {
                    return _.template($("#report-condition-area-item-select").html(), {variable: 'o'});
                },

                condition_area_item_date: function () {
                    return _.template($("#report-condition-area-item-date").html(), {variable: 'o'});
                },
                table_totals: function () {
                    return _.template($("#report-stat-table-totals").html(), {variable: 'o'});
                },
                table_head: function () {
                    return _.template($("#report-stat-table-head").html(), {variable: 'o'});
                },
                table_body: function () {
                    return _.template($("#report-stat-table-body").html(), {variable: 'o'});
                }

            };

            $.each(requiredTemplates, function(index, initialize) {
                if ( typeof templates[index] !== 'undefined' )
                    cachedTemplates[index] = templates[index];
                else
                    cachedTemplates[index] = initialize();
            });

            return cachedTemplates;
        };

        // default params
        params = $.extend({
            dom: {}
        }, params);

        var Report = {

            dom: {},
            data: {
                filter_dimensions_selected: {},
                filter_conditions_selected: {},
                form: {}
            },
            templates: {},

            initialize: function () {
                var dom = initializeDOM(params.dom)
                var templates = initializeTemplates(params.templates)

                this.set_templates(templates);
                this.set_dom(dom);
                this.set_data(params.data);
            },

            set_templates: function (templates) {
                this.templates = templates;
            },

            set_dom: function (dom) {
                this.dom = dom;
            },

            set_data: function (data) {
                this.data.rules = data.rules || {};
                this.data.form = data.form || {};
            },

            initialize_report: function() {
                this.initialize_select_dimensions();
                this.initialize_select_conditions();
                this.initialize_button_run();
                this.initialize_overlay();
                this.apply_defaults(this.data.rules.defaults);
            },

            initialize_overlay: function() {
                this.dom.$_report_result_area.loadingOverlay({
                    //startShowing: true,
                    css: {
                        'background-color': 'rgba(0, 0, 0, 0.075)'
                    }
                });
            },

            initialize_select_dimensions: function() {
                var self = this;

                this.dom.$_report_select_dimensions.find('.select-dimension-item').on('click', function() {

                    var $_self = $(this);
                    var dimension_id = $_self.find('a').data('dimension_id');

                    if ( typeof self.data.filter_dimensions_selected[dimension_id] !== 'undefined'
                        && self.data.filter_dimensions_selected[dimension_id] === true)
                    {
                        self.remove_dimension(self.data.filter_dimensions_selected, dimension_id, $_self);
                    } else
                    {
                        self.add_dimensions(self.data.rules.dimensions, self.data.filter_dimensions_selected, dimension_id, $_self);
                    }

                    self.check_area(self.dom.$_report_dimension_area, self.data.filter_dimensions_selected);
                });

            },

            initialize_select_conditions: function() {
                var self = this;

                this.dom.$_report_select_conditions.find('.select-condition-item').on('click', function() {
                    var $self = $(this);
                    var condition_id = $self.find('a').data('condition_id');
                    if ( typeof self.data.filter_conditions_selected[condition_id] !== 'undefined'
                        && self.data.filter_conditions_selected[condition_id] === true)
                    {
                        self.remove_condition(self.data.filter_conditions_selected, condition_id, $self);
                    } else
                    {
                        self.add_condition(self.data.rules.conditions, self.data.filter_conditions_selected, condition_id, $self);
                    }

                    self.check_area(self.dom.$_report_condition_area, self.data.filter_conditions_selected);
                });

            },

            initialize_button_run: function() {
                var self = this;

                this.dom.$_report_button_run.on('click', function() {
                    var $_self = $(this);

                    self.get_report(
                        self.dom.$_report_filter_form.data('url'),
                        self.data,
                        {},
                        function() {
                            $_self.button('loading');
                            self.overlay('show');
                        },
                        function(data) {
                            self.parse_response(self.data.rules['report-type'], 'success', data, self.dom.$_report_result_area, self.dom.$_report_result_msg_area, self.dom.$_report_button_run);
                        },
                        function(data) {
                            self.parse_response(self.data.rules['report-type'], 'error', data, self.dom.$_report_result_area, self.dom.$_report_result_msg_area, self.dom.$_report_button_run);
                        }
                    );
                });

            },

            overlay: function(action) {

                if( action == 'show' )
                {
                    this.dom.$_report_result_area.trigger( "beforeSend" );
                } else
                if( action == 'hide')
                {
                    this.dom.$_report_result_area.trigger( "complete" );
                }
            },

            get_report: function (request_url, data, form_data, precall, callback_success, callback_error) {

                var self = this;
                var params = this.collect_filter_data(this.dom.$_report_filter_form, {
                    dimensions: data.filter_dimensions_selected,
                    conditions: data.filter_conditions_selected
                });

                $.extend(true, params, form_data);

                precall();
                $.ajax({
                    url: request_url,
                    headers: {},
                    dataType: "json",
                    type: 'POST',
                    data: params,
                    success: function(data) {
                        callback_success(data);

                    },
                    error: function(data) {
                        callback_error(data);
                    }
                });
            },

            parse_response: function (overlay_id, type, data, $_result_area, $_result_area_msg, $report_button_run) {
                var is_success = false;
                $_result_area_msg.empty();

                if ( type == 'error')
                {
                    var message = data['statusText'] || 'An error was encountered while processing this request';
                    $_result_area_msg.append('<div class="alert alert-danger"><i class="fa fa-remove"></i> ' + message + '</div>');
                }

                if ( type == 'success')
                {
                    if( typeof data['messages'] == 'object' && Object.keys(data['messages']).length > 0 )
                    {
                        if( typeof data['messages']['errors'] == 'object' && Object.keys(data['messages']['errors']).length > 0 )
                            $.each(data['messages']['errors'], function(index, value) {
                                $_result_area_msg.append('<div class="alert alert-danger"><i class="fa fa-remove"></i> ' +
                                    value +
                                    '</div>');
                            });
                    }
                }

                if ( typeof data['success'] !== 'undefined' && data['success'] === true)
                {
                    this.update_view(data);
                    is_success = true;
                }

                $report_button_run.button('reset');
                this.overlay('hide');

                return is_success;
            },

            update_view: function (data) {
                var self = this;
                var $table = this.dom.$_report_result_area.find('.table'),
                    $table_totals = this.dom.$_report_result_area.find('.reports-stats-totals'),
                    $table_body = $table.find("tbody"),
                    $table_head = $table.find("thead"),
                    $pagination_area = this.dom.$_report_result_area.find(".reports-stats-pagination"),
                    template_table_totals = this.templates.table_totals,
                    template_table_head = this.templates.table_head,
                    template_table_body = this.templates.table_body;

                $pagination_area.empty().append(data['stats_table_pagination']);
                $table_totals.empty();
                $table_head.empty();
                $table_body.empty();

                if(typeof data['stats_table'] == 'object' && !jQuery.isEmptyObject(data['stats_table']) && !$.isEmptyObject(data['stats_table']['data']) )
                {

                    var tbody = template_table_body(data['stats_table']['data']);
                    var thead = template_table_head(data['stats_table']['head']);

                    if ( typeof data['stats_table']['totals'] !== 'undefined' ) {

                        var ttotals = template_table_totals(data['stats_table']['totals']);
                        $table_totals.append(ttotals);
                    }

                    $table_head.append(thead);
                    $table_body.append(tbody);

                    self.update_pagination($pagination_area);
                    self.update_sorting($table_head);
                } else
                {
                    $table.append('<tr><td class="text-center">' +
                        '<strong>Howdy!</strong> Looks like there is no any record yet. ' +
                        '</td></tr>');
                }
            },

            update_pagination: function ($pagination_area) {
                var self = this;
                var $links = $pagination_area.find("li a");

                $links.on('click', function(event) {
                    var data = {},
                        $_self = $(this),
                        url = $_self.attr("href");

                    event.preventDefault();
                    $_self.removeAttr("href");
                    if(typeof (url) == 'undefined')
                    {
                        return false;
                    }

                    //page = parseInt(page.replace('/',''), 10);
                    //if (!isNaN (page)) data.page = page;
                    if (!jQuery.isEmptyObject(self.data.rules.filter_sort) ) data.sort = self.data.rules.filter_sort;

                    self.get_report(
                        url,
                        self.data,
                        data,
                        function() {
                            $_self.button('loading');
                            self.overlay('show');
                        },
                        function(data) {
                            self.parse_response(self.data.rules['report-type'], 'success', data, self.dom.$_report_result_area, self.dom.$_report_result_msg_area, self.dom.$_report_button_run);
                            $_self.attr("href", url);
                        },
                        function(data) {
                            self.parse_response(self.data.rules['report-type'], 'error', data, self.dom.$_report_result_area, self.dom.$_report_result_msg_area, self.dom.$_report_button_run);
                            $_self.attr("href", url);
                        }
                    );
                });
            },

            update_sorting: function ($table_head) {
                var self = this;
                var $_links = $table_head.find("th a");

                $_links.on('click', function() {
                    var data = {},
                        $_self = $(this),
                        sort = $_self.data('sort'),
                        direction = $_self.data('direction');
                    if(typeof (sort) == 'undefined')
                    {
                        return false;
                    }

                    direction = ( direction == 0 ) ? 1 : 0;
                    data.sort = self.data.rules.filter_sort = {
                        id: sort,
                        direction: direction
                    };

                    self.get_report(
                        self.dom.$_report_filter_form.data('url'),
                        self.data,
                        data,
                        function() {
                            $_self.button('loading');
                            self.overlay('show');
                        },
                        function(data) {
                            self.parse_response(self.data.rules['report-type'], 'success', data, self.dom.$_report_result_area, self.dom.$_report_result_msg_area, self.dom.$_report_button_run);
                        },
                        function(data) {
                            self.parse_response(self.data.rules['report-type'], 'error', data, self.dom.$_report_result_area, self.dom.$_report_result_msg_area, self.dom.$_report_button_run);
                        }
                    );
                });
            },

            collect_filter_data: function ($form, params) {
                var data = {
                    dimensions: {},
                    conditions: {}
                };

                if ( typeof params.dimensions !== 'undefined' && !jQuery.isEmptyObject(params.dimensions))
                {
                    for( var index in params.dimensions)
                    {
                        data.dimensions[index] = $form.find('.el-dimension-'+index).val();
                    }
                }

                if ( typeof params.conditions !== 'undefined' && !jQuery.isEmptyObject(params.conditions))
                {
                    for( var index in params.conditions)
                    {
                        if ( index == 'date' )
                        {
                            data.conditions[index] = {
                                start: $form.find('.el-condition-'+index+'-start').val(),
                                end: $form.find('.el-condition-'+index+'-end').val()
                            };
                        } else
                        {
                            data.conditions[index] = $form.find('.el-condition-'+index).val();
                        }
                    }
                }

                $.extend(true, data, this.data.form);
                return data;
            },

            apply_defaults: function (defaults) {
                if ( typeof defaults !== 'undefined')
                {
                    if ( !jQuery.isEmptyObject(defaults.dimensions) )
                    {
                        for (var index_d in defaults.dimensions)
                        {
                            this.dom.$_report_select_dimensions.find('.select-dimension-item-'+defaults.dimensions[index_d]).trigger( "click" );
                        }
                    }

                    if ( !jQuery.isEmptyObject(defaults.conditions) )
                    {
                        for (var index_c in defaults.conditions)
                        {
                            this.dom.$_report_select_conditions.find('.select-condition-item-'+defaults.conditions[index_c]).trigger( "click" );
                        }
                    }

                    if ( typeof defaults['autorun'] !== 'undefined' && defaults['autorun'] === true)
                    {
                        this.dom.$_report_button_run.trigger( "click" );
                    }
                }
            },

            check_area: function ($area, filter_object) {
                if ( !jQuery.isEmptyObject(filter_object))
                {
                    $area.show();
                } else
                {
                    $area.hide();
                }
            },

            add_dimensions: function (dimensions, filter_dimensions_selected, dimension_id, menu_el) {
                var self = this;
                var data = {
                    id: dimension_id,
                    title: dimensions[dimension_id]['title']
                };
                var dimension_item = $(this.templates.dimension_area_item(data));
                this.dom.$_report_dimension_area_list.append(dimension_item);

                dimension_item.on('click', function() {
                    self.remove_dimension(filter_dimensions_selected, dimension_id, menu_el);
                    self.check_area(self.dom.$_report_dimension_area, filter_dimensions_selected);
                });

                menu_el.addClass('active');
                filter_dimensions_selected[dimension_id] = true;
            },

            remove_dimension: function (filter_dimensions_selected, dimension_id, menu_el) {
                this.dom.$_report_dimension_area_list.find('.dimension-'+dimension_id).remove();
                menu_el.removeClass('active');
                delete filter_dimensions_selected[dimension_id];
            },

            add_condition: function (conditions, filter_condition_selected, condition_id, menu_el) {

                var self = this;
                var data = {
                        id: condition_id,
                        title: conditions[condition_id]['title'],
                        data: conditions[condition_id].data,
                        date: conditions['date']
                    },
                    condition_item;

                if ( typeof conditions[condition_id]['selected'] !== 'undefined')
                {
                    data['selected'] = conditions[condition_id]['selected'];
                }

                if ( typeof conditions[condition_id]['disabled'] !== 'undefined')
                {
                    data['disabled'] = conditions[condition_id]['disabled'];
                }

                if ( condition_id == 'date')
                {
                    condition_item = $(this.templates.condition_area_item_date(data));
                    this.dom.$_report_condition_area_list.append(condition_item);

                    if( typeof this.data.rules['date_params'] !== 'undefined' &&
                        this.data.rules['date_params']['handlerType'] === 'datepicker' )
                    {
                        var $datepicker = condition_item.find('.input-daterange');

                        $datepicker.daterangepicker(
                            this.data.rules['date_params']['options'],
                            this.data.rules['date_params']['callback']
                        );
                    }
                } else
                {
                    condition_item = $(this.templates.condition_area_item_select(data));
                    this.dom.$_report_condition_area_list.append(condition_item);
                }

                condition_item.find('button.close').on('click', function() {
                    self.remove_condition(filter_condition_selected, condition_id, menu_el);
                    self.check_area(self.dom.$_report_condition_area, filter_condition_selected);
                });

                menu_el.addClass('active');
                filter_condition_selected[condition_id] = true;
            },

            remove_condition: function (filter_condition_selected, condition_id, menu_el) {
                this.dom.$_report_condition_area_list.find('.condition-'+condition_id).remove();
                menu_el.removeClass('active');
                delete filter_condition_selected[condition_id];
            }

        };

        return Report;

    };
})( jQuery, _ );