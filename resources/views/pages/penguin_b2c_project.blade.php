@extends('layout.index')

@section('content')

    <div class="row my-3">

        <div class="d-flex flex-column w-100">

            <h1 class="order-1">Penguin Dashboard B2C</h1>

            {{-- Generate Number --}}
            <div class="order-2 d-flex flex-row w-100 justify-content-center my-3">

                <h1 class="currentnumber d-flex flex-row justify-content-between">
                    <p>Seu número é:</p>
                    <strong class="ml-3 generatenumber" id="generatenumber" data-selectid="@if(isset($currentSelectId)){{$currentSelectId}}@endif" data-ipcurrent="@if(isset($currentcustomerIp)){{$currentcustomerIp}}@endif"  data-prevnumber="@if(isset($prevcustomernumber)){{$prevcustomernumber}}@endif"></strong>
                </h1>

            </div>

            {{-- Infro from Current List of Number --}}
            <div class="order-3 d-flex flex-row w-100 justify-content-center my-3">

                <div class="d-flex flex-column">

                    <h1 class="currentnumber d-flex flex-row justify-content-between order-1">
                        <p>Número convocado:</p>
                        <strong class="ml-3 callcurrentnumber" id="callcurrentnumber">@if(!is_null($convocate_number)){{$convocate_number}}@else 0 @endif</strong>
                    </h1>
    
                    <h1 class="currentnumber d-flex flex-row justify-content-between order-2">
                        <p>Para sua vez faltam:</p>
                        <strong class="ml-3 missingnumber" id="missingnumber" data-totalcustomer="@if(!is_null($totalcustomerlist)){{$totalcustomerlist}}@endif"></strong>
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