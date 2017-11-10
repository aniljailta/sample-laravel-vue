@extends('esign._layout')

@section('title')
    E-sign order # {{ $params['order_id'] }}
@endsection

@section('content')
    <table height="100%" width="100%">
        <tr>
            <td>
                <div id="document-sign-container">
                    <div id="preloader" class="text-center">
                        <p class="text-muted">Loading document...</p>
                        <i class="fa fa-circle-o-notch fa-spin fa-4x text-muted"></i>
                    </div>
                </div>
            </td>
        </tr>
    </table>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript" src="https://s3.amazonaws.com/cdn.hellosign.com/public/js/hellosign-embedded.LATEST.min.js"></script>
    <script>
        var checkExist = setInterval(function() {
            if ($('#document-sign-container #hsEmbeddedFrame').length) {
                $('#preloader').remove();
                clearInterval(checkExist);
            }
        }, 100);

        function completeEsign(targerOrigin, fileID, signatureID) {
            window.parent.postMessage({
                event: 'signed',
                fileId: fileID
            }, targerOrigin);
        }

        jQuery(document).ready(function($) {
            HelloSign.init("{{ $params['client_id'] }}");
            HelloSign.open({
                url: "{{ $params['sign_url'] }}",
                allowCancel: false,
                messageListener: function(eventData) {
                    if (eventData.event == HelloSign.EVENT_SIGNED) {
                        HelloSign.close()
                        var targerOrigin = location.protocol + '//' + location.host;
                        var fileID = '{{ $params['file_id'] }}';
                        var signatureID = eventData['signature_id'];
                        completeEsign(targerOrigin, fileID, signatureID)
                        document.location = '{{ $params['thanks_url'] }}?signature_id=' + eventData['signature_id'];
                    }
                },
                skipDomainVerification: {{ json_encode(!app()->environment('production') ? true : false) }},
                debug: true,
                container: document.getElementById('document-sign-container'),
                // height: window.parent.$("iframe").parent().height(),
                uxVersion: 2
            });
        });
    </script>
@endsection

@section('styles')
    @parent
    <style type="text/css">
        #hsEmbeddedWrapper { text-align: center; width: 100%; height: 100% !important; overflow-y: hidden; }
        #document-sign-container #hsEmbeddedFrame {
            height: 100% !important;
        }

        #document-sign-container { overflow: auto; width: 100%; height: 100%}

        #document-sign-container
        #preloader {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            width: 50%;
            height: 20%;
            margin: auto;
        }
    </style>
@endsection