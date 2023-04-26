@extends('layout.index')

@section('content')

      <div class="row my-3">

          <div class="d-flex flex-column w-100">

              <form class="searchurl_form w-100 order-1 my-3" method="post">
  
                  @csrf
  
                  <div class="form-group">
  
                    <label for="urlsearch">Inform URL for search</label>
                    <input type="text" name="indicateurl" class="form-control" id="urlsearch" aria-describedby="serachUrl" value="">
                    
                  </div>
              
                  <button type="submit" class="btn btn-primary float-right">Search Button</button>
  
              </form>

              <table class="table mt-2 order-2">

                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Site</th>
                      <th scope="col">Title</th>
                      <th scope="col">Price</th>
                      <th scope="col">Reviews</th>
                      <th scope="col">Stars</th>
                      <th scope="col">Vendedor</th>
                      <th scope="col">Última Atualização</th>
                    </tr>
                  </thead>

                  <tbody>
                  </tbody>
                  
              </table>    
  
          </div>

      </div>

@endsection