<div class="header bg-gradient-primary py-7 py-lg-8">
    <div class="container">
        @if (Session::has('status'))
    <div class="alert alert-success pull-right fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <p>{{ Session::get('status') }}</p>
        
    </div>
@endif
    </div>
    <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
            <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
    </div>
</div>