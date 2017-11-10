<!doctype html>
<html>
<head>
    <style type="text/css">
        html, body {
            font-family: Arial, sans-serif;
            font-size: 13px;
        }

        p {
            margin: 0;
            padding: 0;
        }

        th {
            font-weight: normal;
            text-align: left;
        }

        p h4 {
            text-align: left;
        }
    </style>
    <link rel="stylesheet" href="{{ url('vendor/bootstrap/css/bootstrap.min.css') }}">
    <script type="text/javascript">
        @if(\Input::get('print')=="now")
        window.print();
        @endif
    </script>
</head>
<body>


<div class="container">
    <button type="button" class="btn btn-primary hidden-print pull-right" onclick="window.print();"
            style="margin-top:10px;padding:10px;">Print Now
    </button>
    <div class="page-header">
        <table style="margin-bottom: 15px;">
            <tr>
                @if($companySettings->company_logo_public_path)
                <td style="width: 125px; text-align: left;">
                    <img src="{{ url($companySettings->company_logo_public_path) }}">
                </td>
                @endif
                <td style="text-align: left;">
                    <div style="font-size: 22px; margin-top: 25px;">
                        {{ config('app.url') }}<br>
                        {{ $dealer->email ?? $companySettings->email }}<br>
                        {{ $dealer->phone ?? $companySettings->phone }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="row">
        <div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center">
                    <img src="{{$item->public_path}}" class="img-rounded">
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center">
                    <p>
                    <h2>{{$label}} </h2></p>
                    <p><h4>Building Serial Number: <strong> {{ $item->serial_number }} </strong></h4></p>
                    <p> <h4>Expiration : <strong>{{$item->expire_on->format('m/d/Y')}}</strong></h4></p>
                </div>
            </div>
        </div>
    </div>

</div>
</body>
</html>



