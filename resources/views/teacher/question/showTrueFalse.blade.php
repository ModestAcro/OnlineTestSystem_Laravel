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

    <title>Pytanie Prawda/Fałsz</title>
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
                        <a class="nav-link" href="{{route('questions.index')}}">Pytania</a>
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
        <div>

            <div class="d-flex justify-content-between align-items-center mb-5">
                <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Informacja o pytaniu</h1>
                <a class="btn btn-outline-danger" href="{{route('questionTrueFalse.edit', $question->id)}}">
                    <span class="d-none d-sm-inline">Edytuj pytanie</span>
                </a>
            </div>

            <h1 class="mb-4">Pytanie</h1>

            <div class="d-flex align-items-center">
                <p><strong>Treść:</strong></p>
            </div>
            <td><pre>{!! strip_tags($question->title, '<strong><b><i><em>') !!}</pre></td>

            <div class="d-flex align-items-center">
                <p><strong>Typ:</strong> @if($question->type == 'true_false') Prawda/Fałsz @endif</p>
            </div>

            <div class="d-flex align-items-center">
                <p><strong>Przedmiot:</strong> {{$question->subject->name}}</p>
            </div>

            <div class="d-flex align-items-center">
                <p><strong>Punkty za pytanie: </strong> {{$question->answers->sum('points')}}</p>
            </div>

            <div class="d-flex align-items-center">
                @foreach($question->answers->where('is_correct', 1) as $answer)
                    <p><strong>Odpowiedź:</strong>
                    @if($answer->title == 'False')
                        {{'Fałsz'}}
                        @else
                        {{'Prawda'}}
                        @endif
                    </p>
                @endforeach
            </div>
        </div>
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

</body>
</html>
