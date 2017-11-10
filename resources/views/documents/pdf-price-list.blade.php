<html>
<head>
    <title>Order Price List</title>
    <link rel="stylesheet" href="{{ url('vendor/font-awesome/css/font-awesome.min.css') }}">
    <style type="text/css">
        * {
            overflow: visible !important;

            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        body {
            margin: 0;
        }

        .page-break {
            page-break-after: always;
            clear: both;
            display: block;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
        .border-bottom {
            border-bottom: 1px solid #393939;
        }

        table.collapsed {
            font-size: 13px;
            margin: 0;
            padding: 0;
            border-collapse: collapse;
            border-spacing: 0;
        }

        tr th {
            padding-top: 1em;
            padding-left: 1em;
            padding-right: 1em;
        }

        tr, td, th, tbody, thead, tfoot {
            page-break-inside: avoid !important;
        }

        .container {
            /* You *must* define a fixed height which is
               large enough to fit the whole content,
               otherwise the layout is unpredictable. */
            height: 90%;
            /* Width and count aren't respected, but you
               have to give at least some dummy value (??). */
            -webkit-columns: 0 0;
            /* This is the strange way to define the number of columns:
               50% = 2 columns, 33% = 3 columns 25% = 4 columns */
            width: 50%;
            /* Gap and rule do work. */
            -webkit-column-gap: 2em;
            -webkit-column-rule: 1px solid black;
            text-align: justify;
        }
    </style>

</head>
<body style="width: 800px;">

    <h1 style="text-align: center">Model Price List</h1>
    <div class="container">
        <table class="collapsed" style="margin: 0 auto;">

            @foreach($styles as $style)
                <tr>
                    <th>{{ $style->name }}</th>
                    <th>Size</th>
                    <th>Shell Price</th>
                </tr>
                @foreach($style->building_models as $model)
                    <tr class="border-bottom">
                        <td class="border-bottom">{{ $style->short_code }}-{{ $model->size_short_code }}</td>
                        <td class="border-bottom" align="center">{{ str_pad($model->width, 2, '0', STR_PAD_LEFT) }} x {{ str_pad($model->length, 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="border-bottom" align="center">{{ '$'.number_format($model->shell_price, 2) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </table>
    </div>

    <div class="page-break"></div>
    <h1 style="text-align: center">Option Price List</h1>

    <div class="container">
        <table class="collapsed" style="margin: 0 auto">

            @foreach($categories as $category)
                <tr>
                    <th>{{ $category->name }}</th>
                    <th>Price</th>
                </tr>
                @foreach($category->options as $option)
                    <tr class="border-bottom">
                        <td class="border-bottom">{{ $option->name }}</td>
                        <td class="border-bottom" align="center">{{ '$'.number_format($option->unit_price, 2) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </table>
    </div>
</body>
</html>