@extends('layout.app')

@section('main')

  <!-- ======================= search -->


<!-- =============== Слово -->
<div class="word-section">
    @foreach ($categories as $category)
    <a href={{ url('/word-category/'.$category->id) }} class="word-block popular">
        <h4>{{ $category->title_ru }}</h4>
        <div class="word-content">
            <img class="icon" src={{  $category->image }} alt="Icon">
            <h1 class="word-count">100 Слово</h1>
        </div>
    </a>
    @endforeach
</div>
@foreach ($categories as $category)
      @if ($category->words->isNotEmpty())

        <div class="category">
            <img class="category-icon" src="{{ $category->image }}" alt="Icon">
            <h2>{{ $category->title_ru }}</h2>
        </div>

        @foreach ($category->words->slice(0, 3) as $word)
            <div class="word-container">
                <div class="word-item">
                    <img class="word-image" src="http://127.0.0.1:8000/storage/{{$word->image}}" alt="Image">
                    <div class="word-text">
                        <h1 class="word-title">{{ $word->name }}</h1>
                        <div class="transcription">
                            <h2>{{ $word->translations['uz'] }}</h2>
                            <p>|</p>
                            <h2 class="pron">{{ $word->pronunciation }}</h2>
                        </div>
                    </div>
                </div>
                <div class="translation">
                    <div class="translation-item">
                        <h2>{{ $word->translations['ru'] }}</h2>
                    </div>
                    <hr>

                    <div class="play-content">
                        <img class="play-button" src="/icons/Play.png" alt="Play Button" onclick="playAudio({{ $word->id }})">
                        <audio id="audio-{{$word->id }}" src="{{ $word->audio }}"></audio>
                    </div>
                </div>
            </div>
        @endforeach
        @endif

    @endforeach

@endsection
