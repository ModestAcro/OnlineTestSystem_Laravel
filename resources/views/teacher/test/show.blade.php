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

    <title>Test</title>
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
                        <a class="nav-link" href="{{route('tests.index')}}">Testy</a>
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
                <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Informacja o teście</h1>
                @if($test->start_time <= now() && $test->end_time >= now())
                    <!-- Test jest aktywny - przycisk NIE działa -->
                    <button class="btn btn-outline-secondary" disabled>
                        <i class="bi bi-pencil-square"></i> <!-- Ikona widoczna zawsze -->
                        <span class="d-none d-sm-inline">Edytuj test</span> <!-- Tekst tylko na dużych ekranach -->
                    </button>
                @else
                    <!-- Test NIE jest aktywny - przycisk działa -->
                    <a class="btn btn-outline-danger" href="{{ route('tests.edit', $test->id) }}">
                        <i class="bi bi-pencil-square"></i> <!-- Ikona widoczna zawsze -->
                        <span class="d-none d-sm-inline">Edytuj test</span> <!-- Tekst tylko na dużych ekranach -->
                    </a>
                @endif
            </div>

            <div class="d-flex align-items-center">
                <p><strong>Data stworzenia:</strong> {{$test->created_at->format('Y-m-d')}}</p>
            </div>

            <div class="d-flex align-items-center">
                <p><strong>Kierunek:</strong> {{$test->course->name}}</p>
            </div>

            <div class="d-flex align-items-center">
                <p><strong>Przedmiot:</strong> {{$test->subject->name}}</p>
            </div>

            <div class="d-flex align-items-center">
                <p><strong>Nazwa:</strong> {{$test->name}}</p>
            </div>

            <div class="d-flex align-items-center">
                <p><strong>Data rozpoczęcia:</strong>
                @if ($test->start_time == null)
                    {{'Nieustalona'}}
                @else
                    {{\Carbon\Carbon::parse($test->start_time)->format('Y-m-d H:i') }}
                @endif
                </p>
            </div>

            <div class="d-flex align-items-center">
                <p><strong>Data zakończenia:</strong>
                    @if ($test->end_time == null)
                        {{'Nieustalona'}}
                    @else
                        {{\Carbon\Carbon::parse($test->end_time)->format('Y-m-d H:i') }}
                    @endif
                </p>
            </div>

            <div class="d-flex align-items-center">
                <p><strong>Czas trwania:</strong> {{$test->duration}} min.</p>
            </div>

            <div class="d-flex align-items-center">
                <p><strong>Liczba podejść:</strong>
                @if ($test->number_of_trials == -1)
                        {{'Nieograniczona'}}
                @else
                    {{$test->number_of_trials}}
                @endif
                </p>
            </div>

            <div class="d-flex align-items-center">
                <p><strong>Grupa:</strong> {{$test->group->year}}, {{$test->group->name}}</p>
            </div>

            <p><strong>Lista studentów ({{ $test->group->students->count() }}):</strong></p>
            <div class="d-flex align-items-center">
                <ul>
                    @foreach ($test->group->students as $student)
                        <li>{{$student->album_number}} - {{ $student->name }} {{$student->surname}}</li>
                    @endforeach
                </ul>
            </div>



            <p><strong>Lista pytań ({{ $test->questions->count() }}):</strong></p>
            <div class="d-flex align-items-center">
                <ul>
                    @foreach ($test->questions as $index => $question)
                        <li><strong>Pytanie {{ $index + 1 }}:</strong> <pre>{!! strip_tags($question->title, '<strong><b><i><em>') !!}</pre></li>
                    @endforeach
                </ul>
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
