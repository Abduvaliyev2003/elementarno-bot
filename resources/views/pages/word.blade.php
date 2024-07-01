@extends('layout.app')


@section('main')

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
