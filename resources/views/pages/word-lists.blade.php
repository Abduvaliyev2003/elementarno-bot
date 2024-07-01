
@extends('layout.app')


@section('main')
  <button id="menu-button">Menu</button>
  @include('components.category-list', ['categories' => $categories])
  <div class="category">
    <img class="category-icon" src="/icons/Group 1.png" alt="Icon">
    <h2>{{ $category->title_ru }}</h2>
  </div>
  @foreach ($words as $item)
  <div class="word-container">
    <div class="word-item">
        <img class="word-image" src="/img/Rectangle 91.png" alt="Image">
        <div class="word-text">
            <h1 class="word-title">{{ $item->name }}</h1>
            <div class="transcription">
                <h2>{{ $item->translations['uz'] }}</h2>
            </div>
        </div>
    </div>
  </div>
  @endforeach

@endsection
