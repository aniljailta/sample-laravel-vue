@extends('partials.modal')

@section('modalContent')
<div class="panel-body">
    <div class="modal-wrapper">
        <div class="modal-icon">
            <i class="fa fa-question-circle"></i>
        </div>
        <div class="modal-text">
            <p>This action is permanent, do you want to continue?</p>
        </div>
    </div>
</div>

<footer class="panel-footer">
    <div class="row">
        <div class="col-md-12 text-right">
            {!! Form::open(['id' => 'delete-form', 'method' => 'DELETE', 'url'=>$params['route'], 'class'=>'form-inline']) !!}
            {!! Form::button('Cancel', ['type' => 'button', 'class' => 'btn btn-default modal-close']) !!}
            {!! Form::button('Delete Record', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
            {!! Form::close() !!}
        </div>
    </div>
</footer>
@endsection



@section('footer-scripts')
    @parent
    <script type="text/javascript" charset="utf-8">
        (function( $ ) {
            'use strict';

            $('.confirmation').magnificPopup({
                type: 'inline',
                preloader: false,
                modal: true
            });


            $(document).on('click', '.confirmation', function (e) {
                var delRoute = "{{ url($params['route']) }}/" + $(this).attr('data-id');
                $('#delete-form').attr("action", delRoute);
            });


            //Modal Close
            $(document).on('click', '.modal-close', function (e) {
                e.preventDefault();
                $.magnificPopup.close();
            });

        }).apply( this, [ jQuery ]);
    </script>
@endsection
