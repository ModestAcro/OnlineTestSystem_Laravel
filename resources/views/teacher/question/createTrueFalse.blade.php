<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5.3 css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <title>Utwórz pytanie</title>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold">
                {{Auth::guard('teacher')->user()->name . " " . Auth::guard('teacher')->user()->surname}}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon custom-toggler"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('questions.index')}}">Lista pytań</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <a href="#" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#logoutModal">Wyloguj</a>
                </form>
            </div>
        </div>
    </nav>
</header>
<main class="main my-5">
    <div class="container card shadow p-4">
        <form action="{{route('questionTrueFalse.store')}}" method="POST" enctype="multipart/form-data">
            @csrf

            @error('subject')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('title')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('answer')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('points')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="d-flex justify-content-between align-items-center">
                <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Prawda/fałsz</h1>
            </div>

            <h1 class="mt-5">Przedmiot</h1>
            <select name="subject" class="form-select mb-4">
                <option value="" disabled {{ old('subject') == '' ? 'selected' : '' }}>Wybierz przedmiot</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ old('subject') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>

            <h1>Treść</h1>
            <textarea class="form-control mb-3 editor" rows="7" name="title" id="question_text" placeholder="Wpisz treść pytania">{{old('title')}}</textarea>

            <h1 class="mt-3">Odpowiedź</h1>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="answer" id="true_answer" value="1"
                    {{ old('answer') == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="true_answer">
                    Prawda
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="answer" id="false_answer" value="0"
                    {{ old('answer') == '0' ? 'checked' : '' }}>
                <label class="form-check-label" for="false_answer">
                    Fałsz
                </label>
            </div>
            <div class="col-auto">
                <h1 class="mt-4">Punkty</h1>
                <input type="number" class="form-control" name="points" value="0">
            </div>

            <button type="submit" class="btn btn-outline-danger mt-4 mb-5">Zapisz pytanie</button>
        </form>

    </div>

    <!-- Modal potwierdzenia wylogowania -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="card-title fs-4 mt-2" id="logoutModalLabel">Potwierdź wylogowanie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Czy na pewno chcesz się wylogować?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Anuluj</button>
                    <form action="{{route('logout')}}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">Wyloguj</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Tiny -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.6.0/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector: '.editor'
    });
</script>
</body>
</html>
