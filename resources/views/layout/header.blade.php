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

    <header>
        <nav class="d-flex flex-row justify-content-end w-100 mt-3">

            <ul class="list-group list-group-horizontal d-flex flex-row justify-content-between mr-3">

                <li class="list-group-item" style="width:10rem;height:3rem;border:none;">
                    <a href="{{ route('home') }}" class="my-3 linkweb" target="_self">Home</a>
                </li>
    
                <li class="list-group-item" style="width:10rem;height:3rem;border:none;">
                    <a href="{{ route('webcrawler') }}" class="my-3 linkweb" target="_self">Web Crawler</a>
                </li>
    
                <li class="list-group-item" style="width:10rem;height:3rem;border:none;">
                    <a href="{{ route('fox_search') }}" class="my-3 linkweb" target="_self">Search Data</a>
                </li>

                <li class="list-group-item" style="width:10rem;height:3rem;border:none;">
                    <a href="{{ route('chatgpt') }}" class="my-3 linkweb" target="_self">ChatGpt</a>
                </li>
    
            </ul>
    
        </nav>
    </header>

    <h1 class="w-100 text-center my-3">MVP Projects</h1>