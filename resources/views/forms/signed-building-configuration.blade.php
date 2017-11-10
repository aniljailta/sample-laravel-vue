<style type="text/css">

    #driver-license table {
        margin: 0;
        padding: 0;
        border-collapse: collapse;
        border-spacing: 0;
    }

    #driver-license h1 {
        font-size: 42px;
    }

    #driver-license h3 {
        font-size: 18px;
        margin: 0;
        padding: 0;
    }

</style>

<div id="driver-license">
    <table width="800">
        <tr>
            <td width="100%" height="30%" class="text-center">
                <img src="{{ url($signedBuildingConfiguration->direct_path) }}" width="{{ $signedBuildingConfiguration->width }}" height="{{ $signedBuildingConfiguration->height }}">
            </td>
        </tr>
    </table>
</div>