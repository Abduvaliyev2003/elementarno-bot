<div id="word-section" class="word-section" style="display: none;">
    <a href="{{ url('/') }}" class="word-block popular home" >
        <i class="fas fa-home"></i>
        <h4>Bosh sahifa</h4>
    </a>
    @foreach ($categories as $category)
    <a href="{{ url('/word-category/'.$category->id) }}" class="word-block popular">
        <h4>{{ $category->title_ru }}</h4>
        <div class="word-content">
            <img class="icon" src="{{ $category->image }}" alt="Icon">
            <h1 class="word-count">100 Слово</h1>
        </div>
    </a>
    @endforeach
</div>
