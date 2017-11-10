<html>
    <head>
        <title>{{ config('app.name') }} Form</title>
        <style type="text/css">
            * {
                font-size: 13px;
                overflow: visible !important;

                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                padding: 0;
            }

            body {
                margin: 0;
                padding: 0;
                height: 100%;
            }

            table {
                padding: 0;
                margin: 0;
            }

            .page-break {
                page-break-after: always;
                clear: both;
                display: block;
            }

            img {
                padding: 0;
                margin: 0;
                border: 0;
                resize:none;
                background:none;
            }

            .stamp {
                width: 100%;
                position: fixed;
                text-align: center;
                background: #fff
            }

            .stamp > div {
                margin-top: 2px;
                background: #fff;
                border-bottom: 1px solid #6f6f6f;
                text-align: center;
                font-weight: bold;
                font-size: 13px;
            }
        </style>
        @yield('pdf-style')
    </head>
    <body>
        @yield('pdf-content')
    </body>
</html>
