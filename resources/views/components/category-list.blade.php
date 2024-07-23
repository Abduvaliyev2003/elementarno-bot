
<div id="word-section" class="word-section" style="display: none;">
    <a href="{{ url('/') . '/?lang=' . $lang}}"   class="word-block  popular home" >
        <i class="fas fa-home"></i>
        <h4>{{ $lang == 'ru' ?  "Главны мену" : "Bosh sahifa" }} </h4>
    </a>
    @foreach ($categories as $category)
    <a href="{{ url('/word-category/'.$category->id) . '/?lang=' . $lang}}" class="word-block popular">
        <h4>{{$lang == 'ru' ? $category->title_ru : $category->title_uz }}</h4>
        <div class="word-content">
            <img class="icon" src="{{ $category->image }}" alt="Icon">
            <h1 class="word-count">{{ $category->words->count() ?? 0 }} {{ $lang == 'ru' ?  "Слово" : "Gap" }} </h1>
        </div>
    </a>
    @endforeach
</div>
