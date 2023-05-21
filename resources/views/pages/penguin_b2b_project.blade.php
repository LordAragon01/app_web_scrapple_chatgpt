@extends('layout.index')

@section('content')

    <div class="row my-3">

        <div class="d-flex flex-column w-100">

            <h1 class="order-1" >Penguin Dashboard B2B</h1>

            {{-- Show the current number --}}
            <div class="order-2 d-flex flex-row w-100 justify-content-start" style="margin-top:2rem;">
                
                <h1 class="currentnumber d-flex flex-row justify-content-between">
                    <p>Próximo Número a Chamar:</p>
                    <strong class="ml-3 nextnumber" id="nextnumber" data-convocate="@if(!is_null($convocate_number)){{$convocate_number}}@else 1 @endif">@if(!is_null($convocate_number) && is_string($convocate_number)){{$convocate_number}}@else{{$convocate_number}} Posição @endif</strong>
                </h1>

            </div>


            {{-- Click for generate QrCode/Url and call a new number --}}
            <div class="order-3 d-flex flex-row w-100 justify-content-center my-3">

                <div class="d-flex flex-column">

                    <figure class="d-flex flex-column align-items-center justify-content-center">

                        {{-- <img src="{{ asset('images/qr-code.png') }}" alt="QrCode" style="width: 100%;height: 20rem;object-fit: contain;"> --}}

                        <figcaption>
                            <button type="button" class="btn btn-primary callnumber" id="callnumber"> Chamar </button>
                        </figcaption>

                    </figure>

                </div>

            </div>

            {{-- Total of number created --}}
            <div class="order-4 d-flex flex-row w-100 justify-content-end mt-3">

                <div class="d-flex flex-column">

                    <h1 class="currentnumber d-flex flex-row justify-content-between order-1">
                        <p>Total de Clientes do Dia:</p>
                        <strong class="totalclientes" id="totalclientes">@if(!is_null($totalcustomer)){{$totalcustomer}}@endif</strong>
                    </h1>

                    <h1 class="currentnumber d-flex flex-row justify-content-between order-2">
                        <p>Total de Números que faltam chamar:</p>
                        <strong class="ml-3 totalnumber" id="totalnumber" data-totalcustomer="@if(!is_null($totalcustomerlist)){{$totalcustomerlist}}@endif">10</strong>
                    </h1>

                </div>

            </div>

        </div>

    </div>
   
@endsection

{{-- Add Scripts for Page --}}
@section('scripts')
<script src="{{ url(mix('js/scriptpenguin.min.js')) }}"></script>
@endsection