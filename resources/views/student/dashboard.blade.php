<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5.3 css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    <title>Panel studenta</title>
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
                        <a class="nav-link" href="{{route('student.competed-tests')}}">Wykonane testy</a>
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
        <h1 class="fs-2 fs-md-3 fs-lg-5 mb-4">Testy do wykonania</h1>
        <div class="row">
            @foreach($tests as $test)
                    <div class="col-lg-4">
                        <div class="card card-margin mb-4">
                            <div class="card-header">
                                <h5 class="card-title fs-4 mt-2">{{$test->name}}</h5>
                            </div>
                            <div class="card-body pt-0">
                                <div class="widget-49">
                                    <div class="widget-49-title-wrapper d-flex flex-column">
                                        <div class="widget-49-meeting-info mt-3">
                                            <p class="mb-1"><strong>Liczba pytań:</strong> {{$test->questions->count()}}</p>
                                            <p class="mb-1"><strong>Czas trwania:</strong> {{$test->duration}} min.</p>
                                            <p class="mb-1"><strong>Liczba prób:</strong>
                                                @if($test->number_of_trials > 0)
                                                    {{ $test->number_of_trials - ($attemptsUsed[$test->id] ?? 0) }}
                                                @elseif($test->number_of_trials == -1)
                                                    {{'Nieograniczona'}}
                                                @endif
                                            </p>
                                            <p class="mb-1"><strong>Data ważności:<br></strong>
                                                {{$test->start_time}} <br> {{$test->end_time}}
                                            </p>
                                            <p class="mb-1"><strong>Nauczyciel:</strong> {{$test->teacher->name}} {{$test->teacher->surname}}</p>
                                        </div>
                                    </div>
                                    <div class="widget-49-meeting-action mt-3">
                                        <form action="{{route('student.test.start', $test->id)}}" method="GET">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                {{ $test->number_of_trials - ($attemptsUsed[$test->id] ?? 0) == 0 ? 'disabled' : '' }}>
                                                Rozpocznij test
                                            </button>
                                        </form>
                                    </div>
                                </div>
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
    </div>

</main>



</body>
</html>
