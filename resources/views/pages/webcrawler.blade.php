@extends('layout.index')

@section('content')

    <div class="container">

        <div class="row d-flex flex-column">

            <h1 class="my-3">Web Crawler & Web Scrapping</h1>

            <h2 class="mb-2">Site de Referência: <a href="{{ url('https://www.noticeboardcompany.com/lockable-indoor-notice-boards/hinged-door/') }}" target="_blank" role="link">Notice Board Company</a></h2>

        </div>

        <div class="row mt-3">

            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Produto</th>
                    <th scope="col">Valor</th>
                    <th scope="col">Última Atualização</th>
                  </tr>
                </thead>
                <tbody>

                    @if(is_array($product_data) && count($product_data) > 0)

                        @foreach ($product_data  as $key => $value)
                         
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ $value->product_name }}</td>
                                <td>&pound; {{ $value->product_price }}</td>
                                <td>{{ $value->last_att }}</td>

                            </tr>

                        @endforeach

                    @else

                        <h1> Nenhum dado a apresentar </h1>

                    @endif

                </tbody>
              </table>

        </div>

    </div>
   

@endsection