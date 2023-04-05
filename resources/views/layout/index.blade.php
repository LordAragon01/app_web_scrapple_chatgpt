@section('title', $title)

@include('layout.header')

    <div class="container">

        @yield('content')

    </div>
        
@include('layout.footer')    