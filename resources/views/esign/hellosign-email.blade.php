@extends('esign._layout')
@section('title')
    E-sign order via email
@endsection

@section('content')
    <table height="100%" width="100%">
        <tr style="vertical-align: top">
            <td>
                <div class="container info text-center">
                    <h1>
                        @if($file->category_id === 'order_forms')
                            Order Forms
                        @endif
                        @if($file->category_id === 'rto_docs')
                            RTO Documents
                        @endif
                    </h1>

                    <p>Order documents will be sent to <em>{{ $order->order_reference->customer_name }}</em> by email <em>{{ $order->order_reference->email }}</em>.</p>
                    <p>If you have any questions, please don't hesitate to get in touch with us.</p>

                    <a href="{{ url()->current() }}?confirm=1" role="button" class="btn btn-success btn-md continue">
                        <span class="submit-label">Send</span> <i class="fa fa-arrow-right fa-fw" aria-hidden="true"></i>
                    </a>
                </div>
            </td>
        </tr>
    </table>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.continue').click(function () {
                var $button = $(this)
                $button.find('.submit-label').text('Sending..')
                $button.find('.fa-arrow-right').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')
                $button.addClass('disabled')
            });
        });
    </script>
@endsection

@section('styles')
    @parent

    <style type="text/css">

        .container.info {
            padding: 40px;
        }

        .container.info h1 {
            font-size: 34px;
        }

        .container.info p em {
            font-weight: bold;
            color: #333;
        }

        .container.info p {
            font-size: 14px;
            color: #666;
        }
    </style>
@endsection