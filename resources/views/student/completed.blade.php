<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5.3 css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <title>Wykonane testy</title>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold">
                {{Auth::guard('student')->user()->name . " " . Auth::guard('student')->user()->surname}}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon custom-toggler"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('student.dashboard')}}">Panel główny</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <a href="#" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#logoutModal">Wyloguj</a>
                </form>
            </div>
        </div>
    </nav>
</header>
<main class="main">
    <div class="container mt-5">
        <div class="title mb-5">
            <h1 class="fs-2 fs-md-3 fs-lg-5">Wykonane testy</h1>
            <p>Ilość: {{$completedTestCount}}</p>
        </div>

        <div class="row">
            @foreach($bestTests as $test)
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title fs-4 mt-2">{{ $test->test->name }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{$test->test->subject->name}}</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Wykładowca:</strong> {{ $test->test->teacher->name}}  {{ $test->test->teacher->surname}}</p>
                            <p><strong>Data rozpoczęcia:</strong> {{ $test->start_time }}</p>
                            <p><strong>Data zakończenia:</strong> {{ $test->end_time }}</p>
                            <p><strong>Zdobyto punktów:</strong> {{ $test->earned_score }} /  {{ $test->max_score }}</p>
                            <p><strong>Wynik procentowy:</strong> {{$test->percent_score}}%</p>
                            <p><strong>Ocena:</strong> {{ $test->result }}</p>
                            <p><strong>Status:</strong>
                                @if($test->result >= 3)
                                    <span class="text-success">Zaliczony</span>
                                @else
                                    <span class="text-danger">Niezaliczony</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
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
</main>
</body>
</html>
