<!doctype html>
<html>
    <head>
        <style type="text/css">
            html, body {
                font-family: Arial, sans-serif;
                font-size: 13px;
            }
            p { margin: 0; padding: 0; }
            th {
                font-weight: normal;
                text-align: left;
            }
            .text-left {
                text-align: left;
            }
            .text-center {
                text-align: center;
            }
            .text-right {
                text-align: right;
            }
        </style>
    </head>
	<body>
        <table style="margin-bottom: 15px;">
            <tr>
                <td style="width: 125px; text-align: left;">
                    @if($companySettings->company_logo_public_path)
                    <img src="{{ url($companySettings->company_logo_public_path) }}">
                    @endif
                </td>
                <td style="text-align: left;">
                    <div style="font-size: 22px; margin-top: 25px;">
                        <strong>{{ config('app.url') }}</strong>
                        <br>
                        {{ url($companySettings->company_logo_public_path) }}
                        <br>
                        {{ url($companySettings->company_logo_public_path) }}
                    </div>
                </td>
            </tr>
        </table>
        @yield('content')
	</body>
</html>
