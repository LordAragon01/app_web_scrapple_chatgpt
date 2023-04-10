@extends('layout.index')

@section('content')

    <div class="container">

        <div class="row my-5">

            <form class="searchurl_form w-100" method="post">

                @csrf

                <div class="form-group">

                  <label for="urlsearch">Inform URL for search</label>
                  <input type="text" name="indicateurl" class="form-control" id="urlsearch" aria-describedby="serachUrl">
                 
                </div>
           
                <button type="submit" class="btn btn-primary float-right">Search</button>

            </form>

        </div>

    </div>
   

@endsection