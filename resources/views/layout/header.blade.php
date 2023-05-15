<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Fox Page')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ url(mix('css/style.min.css')) }}" />
</head>
<body>

    <div class="w-100 loading" id="loadingdefault">

        <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>

    </div>

    <header>
        <nav class="d-flex flex-row justify-content-end w-100 mt-3">

            <ul class="list-group list-group-horizontal d-flex flex-row justify-content-between mr-3 navlistdefault">

                <li class="list-group-item">
                    <a href="{{ route('home') }}" class="my-3 linkweb" target="_self">Home</a>
                </li>
    
                <li class="list-group-item">
                    <a href="{{ route('webcrawler') }}" class="my-3 linkweb" target="_self">Web Crawler</a>
                </li>
    
                <li class="list-group-item">
                    <a href="{{ route('fox_search') }}" class="my-3 linkweb" target="_self">Search Data</a>
                </li>

                <li class="list-group-item">
                    <a href="{{ route('chatgpt') }}" class="my-3 linkweb" target="_self">Q&AGpt</a>
                </li>

                <li class="list-group-item">
                    <a href="{{ route('chatgptconversation') }}" class="my-3 linkweb" target="_self">ChatGpt</a>
                </li>

                <li class="list-group-item penguinnav">
                    <div>
                        <span>Penguin</span>
                        <ul class="penguindrop">
                            <li>
                                <a href="{{ route('penguinb2b') }}" class="my-3 linkweb" target="_self">Penguin B2B</a>
                            </li>
                            <li>
                                <a href="{{ route('penguinb2c') }}" class="my-3 linkweb" target="_self">Penguin B2C</a>
                            </li>
                        </ul>
                    </div>
                </li>
    
            </ul>
    
        </nav>
    </header>

    <h1 class="w-100 text-center my-3">MVP Projects</h1>