@if ($errors->any())
    <script>
        jQuery(document).ready(function() {
            var txt =""
            @foreach ($errors->all() as $error)
                txt+= "<li>{{ $error }}</li>";
            @endforeach
            new PNotify({title: 'We found a few problems...', text: txt, type: 'error'});
        });


    </script>
@endif

@if (Session::has('messages'))
    <?php $msg = Session::get('messages'); ?>
    <script>
        jQuery(document).ready(function() {
            new PNotify({title: "{{ $msg['title'] }}", text: "{{ $msg['text'] }}", type: "{{ $msg['type'] }}"});
        });
    </script>
@endif


