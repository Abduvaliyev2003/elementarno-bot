@extends('layout.app')


@php

    app()->setlocale(request('lang') ?? 'ru');
    $lang =  app()->getLocale();
@endphp

@section('main')
<button id="menu-button">Menu</button>
@include('components.category-list', ['categories' => $categories, 'lang' => $lang])
<div class="word-container" >
    <div class="word-item">
        <img class="word-image" src="https://a82f-213-230-118-217.ngrok-free.app/storage/{{$word->image}}" alt="Image">
        <div class="word-text">
            <h1 class="word-title">{{ $word->name }}</h1>
            <div class="transcription">
                {{-- <h2>{{ $word->translations['uz'] }}</h2>
                <p>|</p> --}}
                <h2 class="pron">{{ $word->pronunciation }}</h2>
            </div>
        </div>
    </div>
    <div class="translation">
        <div class="translation-item">
            <h2>{{$word->translations['ru']}}</h2>
        </div>
        <hr>
        <div class="translation-item">
            <h2>{{$word->translations['uz']}}</h2>
        </div>
        <div class="play-content">
            <img class="play-button" src="/icons/Play.png" alt="Play Button" onclick="playAudio({{ $word->id }})">
            <audio id="audio-{{$word->id }}" src="{{ $word->audio }}"></audio>
        </div>
    </div>
</div>
{{-- <div class="word-container">
    <div class="word-item">
        <img class="word-image" src="/img/Rectangle 91.png" alt="Image">
        <div class="word-text">
            <h1 class="word-title">You</h1>
            <div class="transcription">
                {{-- <h2>ю</h2>
                <p>|</p>
                <h2 class="pron">[ ju: ]</h2>
            </div>
        </div>
    </div>
    <div class="translation">
        <div class="translation-item">
            <h2>вы, вами, вас, вам:</h2>
        </div>
        <div class="translation-item">
            <h2>ты, тебя, тобой, тебе:</h2>
        </div>
        <hr>

        <div class="play-content">
            <img class="play-button" src="/icons/Play.png" alt="Play Button" onclick="playAudio()">
            <audio id="audio" src="https://commondatastorage.googleapis.com/codeskulptor-demos/DDR_assets/Kangaroo_MusiQue_-_The_Neverwritten_Role_Playing_Game.mp3"></audio>
        </div>
    </div>
</div>
--}}

@endsection
