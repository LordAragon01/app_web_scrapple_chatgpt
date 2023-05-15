@extends('layout.index')

@section('content')

    <div class="row my-3">

        <div class="d-flex flex-column w-100">

            <h1 class="order-1">Penguin Dashboard B2C</h1>

            {{-- Generate Number --}}
            <div class="order-2 d-flex flex-row w-100 justify-content-center my-3">

                <h1 class="currentnumber d-flex flex-row justify-content-between">
                    <p>Seu número é:</p>
                    <strong class="ml-3 generatenumber" id="generatenumber">1</strong>
                </h1>

            </div>

            {{-- Infro from Current List of Number --}}
            <div class="order-3 d-flex flex-row w-100 justify-content-center my-3">

                <div class="d-flex flex-column">

                    <h1 class="currentnumber d-flex flex-row justify-content-between order-1">
                        <p>Número convocado:</p>
                        <strong class="ml-3 currentnumber" id="currentnumber">1</strong>
                    </h1>
    
                    <h1 class="currentnumber d-flex flex-row justify-content-between order-2">
                        <p>Para sua vez faltam:</p>
                        <strong class="ml-3 missingnumber" id="missingnumber">1</strong>
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