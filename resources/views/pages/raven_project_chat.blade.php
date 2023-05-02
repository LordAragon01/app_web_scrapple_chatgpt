@extends('layout.index')

@section('content')

    <div class="row my-3">

        <div class="d-flex flex-column w-100">

            <h1 class="order-1">ChatGpt Conversation</h1>

            <div class="result order-2 my-3" id="resultgptchat">
                {{--  <p class="typedtext"><span class="cursor blink"></span></p> --}}
            </div>

            <form class="searchchatgpt_form chatgptform w-100 order-3 my-3" id="chatgptform" method="post">

                @csrf

                <div class="w-100 loadingform mb-1" id="loadingform">

                    <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
            
                </div>

                <div class="form-group">

                    <label for="chatpromptsearch">Inform your prompt </label>
                    <input type="text" name="chatindicateprompt" class="form-control" id="chatpromptsearch" aria-describedby="ChatGpt Conversation" value="">
                
                </div>

                <div class="d-flex-flex-row justify-content-between float-right">

                    <button type="submit" class="btn btn-primary" id="chatgptbtnconv">Send</button>
                    <button type="button" class="btn btn-danger" id="chatgptbtnconvdel">Clean Chat</button>

                </div>

            </form>

        </div>
        
    </div>

@endsection

{{-- Add Scripts for Page --}}
@section('scripts')
<script src="{{ url(mix('js/scriptchat.min.js')) }}"></script>
@endsection