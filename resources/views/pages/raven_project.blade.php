@extends('layout.index')

@section('content')

    <div class="row my-3">

        <div class="d-flex flex-column w-100">

            <h1 class="order-1">ChatGpt Search</h1>

            <form class="searchchatgpt_form w-100 order-2 my-3" method="post">
  
                @csrf

                <div class="form-group">

                  <label for="promptsearch">Inform your prompt </label>
                  <input type="text" name="indicateprompt" class="form-control" id="promptsearch" aria-describedby="promptsearch" value="{{ old('indicateprompt') }}">
                  
                </div>
            
                <button type="submit" class="btn btn-primary float-right">Send</button>

            </form>

            <div class="result order-3" id="resultgpt"></div>

        </div>
        
    </div>

@endsection

{{-- Add Scripts for Page --}}
@section('scripts')
<script src="{{ url(mix('js/scriptcon.min.js')) }}"></script>
@endsection