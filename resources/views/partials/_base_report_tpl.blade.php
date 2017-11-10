@section('specifics-scripts')
    @parent

    <script type="text/template" id="report-dimension-area-item">
        <li class="dimension-<%=o.id%>">
            <a class="cursor-pointer" data-dimension="<%=o.id%>"> <%=o.title%> <span aria-hidden="true">&times;</span></a>
            <input type="hidden" name="dimensions[]" value="<%=o.id%>" class="el-dimension-<%=o.id%>">
        </li>
    </script>

    <script type="text/template" id="report-condition-area-item-date">
        <li class="list-group-item condition-<%=o.id%>">

            <label><%=o.title%>:</label>
            <div class="div-close">
                <button type="button" class="close" aria-label="Close">
                    <span aria-hidden="true">remove &times;</span>
                </button>
            </div>
            <div class="filter-selector">
                <div class="input-group-fix">

                    <div id="input-daterange" class="input-daterange form-control input-sm">
                        <input type="hidden" name="conditions[date][start]" class="el-condition-<%=o.id%>-start" value="<%=o.date['start_formatted']%>">
                        <input type="hidden" name="conditions[date][end]" class="el-condition-<%=o.id%>-end" value="<%=o.date['end_formatted']%>">

                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span>
                            <%=o.date['start_formatted']%> - <%=o.date['end_formatted']%>
                        </span>
                    </div>
                </div>

                <div class="clearfix"></div>
            </div>

        </li>
    </script>

    <script type="text/template" id="report-condition-area-item-select">
        <li class="list-group-item condition-<%=o.id%>">

            <label><%=o.title%>:</label>
            <div class="div-close">
                <button type="button" class="close" aria-label="Close">
                    <span aria-hidden="true">remove &times;</span>
                </button>
            </div>

            <div class="filter-selector">
                <div class="input-group-fix">
                    <select
                            class="form-control input-sm el-condition-<%=o.id%>"
                            name="conditions[<%=o.id%>]"
                            <% if ( typeof o.disabled !== 'undefined' && o.disabled == true) { %> disabled="disabled" <% } %>
                            >
                        <% for (var index in o.data) { %>

                        <% if ( typeof o.data[index] === 'object') { %>

                        <option value="<%=o.data[index].id%>"

                                <% if ( typeof o.selected !== 'undefined' && o.selected == o.data[index].id) { %>
                                selected="selected"
                        <% } %>

                                ><%=o.data[index].title%></option>

                        <% } else { %>

                        <option value="<%=index%>"><%=o.data[index]%></option>

                        <% } %>
                        <% } %>
                    </select>
                </div>

                <div class="clearfix"></div>
            </div>

        </li>
    </script>

    <script type="text/template" id="report-stat-table-head">

        <tr class="text-center">
            <% for (var index_row in o) { %>

            <% if (typeof o[index_row]['title'] !== 'undefined') { %>
            <th class="th-sort th-sort-<%=index_row%>">
                <a class="cursor-pointer" data-sort="<%=index_row%>" data-direction="<%=o[index_row]['direction']%>" ><%=o[index_row]['title']%>

                    <% if (typeof o[index_row]['direction'] !== 'undefined' && o[index_row]['direction'] == 1) { %>
                    <i class="fa fa-sort-desc report-col-sorted"></i>
                    <% } else if (typeof o[index_row]['direction'] !== 'undefined' && o[index_row]['direction'] == 0) { %>
                    <i class="fa fa-sort-asc report-col-sorted"></i>
                    <% } else { %>
                    <i class="fa fa-sort"></i>
                    <% } %>

                </a>
            </th>
            <% } %>

            <% } %>
        </tr>

    </script>

    <script type="text/template" id="report-stat-table-totals">

        <% if( typeof o['items'] !== 'undefined' ) { %>

        <dl class="dl-horizontal">
            <% for (var index_row in o['items']) { console.log(o) %>

            <dt>
                <% if ( typeof(o['params'][index_row] !== 'undefined') ) { %>
                    <%=o['params'][index_row]['title']%>
                <% } else { %>
                    <%=index_row %>
                <% } %>
            </dt>

            <dd>
                <%= o['items'][index_row] %>
            </dd>

            <% } %>
        </dl>

        <% } %>

    </script>

@endsection