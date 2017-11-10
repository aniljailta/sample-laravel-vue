<div id="modalWindow" class="modal-block modal-block-primary mfp-hide {!! $modalSize !!}">
    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">{!! $modalTitle !!}</h2>
        </header>
        @yield('modalContent')
    </section>
</div>