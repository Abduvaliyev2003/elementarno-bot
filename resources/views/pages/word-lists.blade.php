
@extends('layout.app')

@php
    app()->setlocale(request('lang') ?? 'ru');
    $lang =  app()->getLocale();
@endphp
@section('main')
  <button id="menu-button">Menu</button>
  @include('components.category-list', ['categories' => $categories, 'lang' => $lang])
  <div class="category">
    <img class="category-icon" src="/icons/Group 1.png" alt="Icon">
    <h2>{{ $category->title_ru }}</h2>
  </div>
  @forelse ($words as $item)
  <a href={{ url('/word/'.$item->id) . '/?lang=' . $lang }} class="word-container">
    <div class="word-item">
        <img class="word-image" src="{{ env('APP_URL') }}/storage/{{$item->image}}" alt="Image">
        <div class="word-text">
            <h1 class="word-title">{{ $item->name }}</h1>
            <div class="transcription">
                <h2>{{ $lang == 'ru' ? $item->translations['ru'] : $item->translations['uz'] }}</h2>
            </div>
        </div>
    </div>
    <div class="translation">
        <div class="translation-item">
            <h2>{{$item->translations['ru']}}</h2>
        </div>
        <hr>
        <div class="translation-item">
            <h2>{{$item->translations['uz']}}</h2>
        </div>
        <div class="play-content" onclick="event.stopPropagation();">
            <button class="play-button" onclick="playAudio(event, '{{ $item->name }}')">
                <img src="/icons/Play.png" alt="Play Button">
            </button>
        </div>
    </div>
  </a>
  @empty
  <p>Result yo`q</p>
  @endforelse
@endsection
