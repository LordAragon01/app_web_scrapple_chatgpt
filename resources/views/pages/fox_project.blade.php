@extends('layout.index')

@section('content')

    <div class="container">

        <div class="row my-5">

            <form class="searchurl_form" method="get">

                @csrf

                <div class="form-group">

                  <label for="urlsearch">Inform URL for search</label>
                  <input type="search" class="form-control" id="urlsearch" aria-describedby="serachUrl">
                 
                </div>
           
                <button type="submit" class="btn btn-primary">Search</button>

            </form>

        </div>

    </div>
   

@endsection