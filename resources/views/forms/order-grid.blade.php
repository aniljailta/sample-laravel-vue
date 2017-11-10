<style type="text/css">
    .order-grid {
        border-spacing: 0px;
        border-collapse: collapse;
        margin: 0 auto;
    }

    .order-grid td {
        border: 1px solid #9c9a93;
        width: 24px;
        height: 24px;
    }

    .grid-desc {
        font-size: 35px;
        clear: both;
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .page {
        background: #fff;
        width: 1050px;

        -webkit-transform: translateY(1050px) rotate(270deg); /* Chrome, Safari 3.1+ */
        -moz-transform: translateY(1050px) rotate(270deg); /* Firefox 3.5+ */
        -o-transform: translateY(1050px) rotate(270deg); /* Opera 10.5-12.0 */
        -ms-transform: translateY(1050px) rotate(270deg); /* IE 9 */
        transform: translateY(1050px) rotate(270deg); /* IE 10+, Firefox 16.0+, Opera 12.1+ */
        filter: progid:DXImageTransform.Microsoft.Matrix(M11=6.123031769111886e-17, M12=1, M21=-1, M22=6.123031769111886e-17, SizingMethod='auto expand'); /* IE 6-7 */
        -ms-filter: "progid:DXImageTransform.Microsoft.Matrix(M11=6.123031769111886e-17, M12=1, M21=-1, M22=6.123031769111886e-17, SizingMethod='auto expand')"; /* IE 8 */

        -webkit-transform-origin: 0% 0%;
        -moz-transform-origin: 0% 0%;
        -o-transform-origin: 0% 0%;
        -ms-transform-origin: 0% 0%;
        transform-origin: 0% 0%;
    }
</style>

<div class="page">
    <div class="grid-desc text-center">{{ config('app.name') }} Custom Order Building Configuration</div>

    <table class="order-grid">
        @for($row = 0; $row <= 13; $row++)
            <tr>
                @for($col = 0; $col <= 41; $col++)
                    <td></td>
                @endfor
            </tr>
        @endfor
    </table>

    <div class="grid-desc text-center">(1 block = 1')</div>
    <div class="grid-desc text-center">(Not required for building packages)</div>
</div>