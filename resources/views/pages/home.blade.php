@extends('layout.app')


@php

    app()->setlocale(request('lang') ?? 'ru');
    $lang =  app()->getLocale();
@endphp
@section('main')

<!-- =============== Слово -->
<div class="word-section">

    @foreach ($categories as $category)
    <a href={{ url('/word-category/'.$category->id) . '/?lang=' . $lang }} class="word-block popular">
        <h4>{{$lang == 'ru' ? $category->title_ru : $category->title_uz }}</h4>
        <div class="word-content">
            <img class="icon" src={{  $category->image }} alt="Icon">
            <h1 class="word-count">{{ $category->words->count() ?? 0 }} {{ $lang == 'ru' ?  "Слово" : "Gap" }}</h1>
        </div>
    </a>
    @endforeach
</div>
@foreach ($categories as $category)
      @if ($category->words->isNotEmpty())

        <div class="category">
            <img class="category-icon" src="{{ $category->image }}" alt="Icon">
            <h2>{{$lang == 'ru' ? $category->title_ru : $category->title_uz }}</h2>
        </div>

        @foreach ($category->words->slice(0, 3) as $word)
             <a href={{ url('/word/'.$word->id) . '/?lang=' . $lang }} class="word-container" >
                <div class="word-item">
                    <img class="word-image" src="{{ env('APP_URL') }}/storage/{{$word->image}}" alt="Image">
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

                    <div class="play-content" onclick="event.stopPropagation();">
                        <button class="play-button" onclick="playAudio(event, '{{ $word->name }}')">
                            <img src="/icons/Play.png" alt="Play Button">
                        </button>
                    </div>

                </div>
            </a>
        @endforeach
        @endif

    @endforeach

@endsection


