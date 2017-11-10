<div class="hr" style="height:1px;border-bottom:1px solid #cccccc;clear: both;"></div>
<br>

<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
    We appreciate your business! <br>
    Best, <br>
    {{ config('app.name') }} Sale Team <br>
    <br>
    <div class="hr" style="height:1px;border-bottom:1px solid #cccccc;clear: both;"></div>
    <br>
    @if($companySettings->facebook)
    <a href="//www.facebook.com/{{ $companySettings->facebook }}/" target="_blank"><img width="32" height="32" src="{{ asset('images/social-media/facebook.png') }}" alt="//www.facebook.com/{{ $companySettings->facebook }}"></a>
    @endif

    @if($companySettings->gplus)
    <a href="//plus.google.com/{{ $companySettings->gplus }}" target="_blank"><img width="32" height="32" src="{{ asset('images/social-media/pale blue square.png') }}" alt="//plus.google.com/{{ $companySettings->gplus }}"></a>
    @endif

    @if($companySettings->instagram)
    <a href="//www.instagram.com/{{ $companySettings->instagram }}" target="_blank"><img width="32" height="32" src="{{ asset('images/social-media/instagram duck egg blue.png') }}" alt="//www.instagram.com/{{ $companySettings->instagram }}"></a>
    @endif
</div>
<br>