@extends('layout.index')

@section('content')

    <div class="row my-3">

        <div class="d-flex flex-column w-100">

            <h1 class="order-1">ChatGpt Conversation</h1>

            <form class="searchchatgpt_form w-100 order-2 my-3" method="post">

                @csrf

                <div class="form-group">

                <label for="chatpromptsearch">Inform your prompt </label>
                <input type="text" name="chatindicateprompt" class="form-control" id="chatpromptsearch" aria-describedby="ChatGpt Conversation" value="{{ old('chatindicateprompt') }}">
                
                </div>
            
                <button type="submit" class="btn btn-primary float-right" id="chatgptbtnconv">Send</button>

            </form>

            <div class="result order-3" id="resultgptchat">
            {{--  <p class="typedtext"><span class="cursor blink"></span></p> --}}
            </div>

        </div>
        
    </div>

@endsection