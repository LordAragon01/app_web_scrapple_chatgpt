@section('title', $title)

@include('layout.header')

    <div class="container">

        {{-- Get Dynamic Base Url --}}
        <span style="display:none;" data-url="{{ url('/') }}" id="baseurl"></span>

        @yield('content')

    </div>
        
@include('layout.footer')    