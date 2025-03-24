<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5.3 css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <title>Szczegóły testu</title>
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
                        <a class="nav-link" href="{{route('completed-tests.index')}}">Wykonane testy</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <a href="#" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#logoutModal">Wyloguj</a>
                </form>
            </div>
        </div>
    </nav>
</header>

<main class="container my-5">
    <div class="container card shadow p-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Wyniki testu</h1>
        </div>

        <div class="row mt-5">
            @foreach($results as $result)
                <!-- Używamy klas col-sm-6 na tabletach i col-md-4 na średnich ekranach -->
                <div class="col-sm-12 col-md-6 col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">Student: {{ $result->student->name }}</h5> <!-- Wyświetlanie imienia studenta -->
                        </div>
                        <div class="card-body">
                            <p><strong>Nr Albumu:</strong> {{ $result->student->album_number }}</p> <!-- Numer albumu -->
                            <p><strong>Punkty:</strong> {{ $result->earned_score }} / {{ $result->max_score }}</p> <!-- Punkty zdobyte -->
                            <p><strong>Ocena:</strong> {{ $result->result }}</p> <!-- Ocena -->
                            <p><strong>Wynik:</strong> {{ $result->percent_score }}%</p> <!-- Wynik procentowy -->
                            <p><strong>Data rozpoczęcia:</strong> {{ $result->start_time }}</p> <!-- Data rozpoczęcia -->
                            <p><strong>Data zakończenia:</strong> {{ $result->end_time }}</p> <!-- Data zakończenia -->
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <a href="{{ route('export.results', ['test_id' => $test->id]) }}" class="btn btn-outline-danger">
            Pobierz wyniki (Excel)
        </a>
    </div>
</main>
</body>
</html>
