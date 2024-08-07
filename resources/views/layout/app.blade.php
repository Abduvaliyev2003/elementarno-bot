<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ secure_asset('css/style.css') }}">

</head>

<body>
    <div class="container">
        <div class="main">
            <form class="search">
                <img class="search-icon" src="/icons/search 1.png" alt="Search Icon">
                <input  id="search-input" placeholder="Введите необходимое слово..." type="text">

             </form>
             <div class="search-content">
                <ul id="search-results"></ul>
             </div>

            @yield('main')
        </div>
    </div>

    <script src="{{ secure_asset('js/script.js')}}"></script>
</body>

</html>
