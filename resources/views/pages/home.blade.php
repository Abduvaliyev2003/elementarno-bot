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

<div class="category">
    <img class="category-icon" src="/icons/Group 1.png" alt="Icon">
    <h2>Самые популярные</h2>
</div>

<div class="word-container">
    <div class="word-item">
        <img class="word-image" src="/img/Rectangle 91.png" alt="Image">
        <div class="word-text">
            <h1 class="word-title">You</h1>
            <div class="transcription">
                <h2>ю</h2>
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
@endsection
