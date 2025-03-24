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

    <title>Testy</title>
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
                        <a class="nav-link" href="{{route('teacher.dashboard')}}">Panel główny</a>
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
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Lista testów</h1>
            <a class="btn btn-outline-danger" href="{{route('tests.create')}}">
                <i class="bi bi-plus-circle"></i>
                <span class="d-none d-sm-inline">Utwórz test</span>
            </a>
        </div>
        <p>Ilość: {{$testsCount}}</p>

        <!-- SEARCH -->
        <input class="form-control" id="myInput" type="text" placeholder="Szukaj...">
        <!-- SEARCH -->

        <div class="table-responsive mt-4 d-none d-md-block">
            <table class="table">
                <thead class="table-active">
                <tr>
                    <th>Nr.</th>
                    <th>Nazwa</th>
                    <th>Kierunek</th>
                    <th>Przedmiot</th>
                    <th>Czas trawania</th>
                    <th>Liczba podejść</th>
                    <th>Liczba pytań</th>
                    <th>Status</th>
                    <th>Akcje</th>
                </tr>
                </thead>
                <tbody id="myTable">
                @foreach($tests as $test)
                    <tr>
                        <td>{{ $loop->iteration + $tests->firstItem() - 1 }}</td>
                        <td>{{$test->name}}</td>

                        <td>
                            {{$test->course->name}}
                        </td>
                        <td>
                            {{$test->subject->name}}
                        </td>

                        <td>{{$test->duration}}</td>
                        <td>
                            @if($test->number_of_trials == -1)
                                Nieograniczona
                            @else
                                {{$test->number_of_trials}}
                            @endif
                        </td>
                        <td>
                            {{$test->questions->count()}}
                        </td>
                        <td>
                            @if(!$test->start_time && !$test->end_time)
                                {{'Nierozpoczęty'}}
                            @elseif($test->end_time && $test->end_time > now())
                                {{'W trakcie'}}
                            @elseif($test->end_time && $test->end_time < now())
                                {{'Zakończony'}}
                            @endif
                        </td>

                        <td>
                            <a href="{{route('tests.show', $test->id)}}" class="btn btn-outline-danger">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Widok dla tabletów -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 mt-4 d-block d-md-none">
            @foreach($tests as $test)
                <div class="col mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Nr. {{ $loop->iteration + $tests->firstItem() - 1 }}</h5>
                            <p class="card-text">
                                <strong>Nazwa: </strong> {{$test->name}} <br>
                                <strong>Kierunek:</strong> {{$test->course->name}}<br>
                                <strong>Przedmiot:</strong> {{$test->subject->name}}<br>
                                <strong>Czas trwania:</strong> {{$test->duration}} min.<br>
                                <strong>Liczba podejść: </strong>
                                @if($test->number_of_trials == -1)
                                    Nieograniczona
                                @else
                                    {{$test->number_of_trials}}
                                @endif
                                <br>
                                <strong>Liczba pytań:</strong> {{$test->questions->count()}}<br>
                                <strong>Status: </strong>
                                @if(!$test->start_time && !$test->end_time)
                                    Nierozpoczęty
                                @elseif($test->end_time && $test->end_time > now())
                                    W trakcie
                                @elseif($test->end_time && $test->end_time < now())
                                    Zakończony
                                @endif
                            </p>
                        </div>
                        <div class="card-footer text-end">
                            <a href="{{route('tests.show', $test->id)}}" class="btn btn-outline-danger">
                                <i class="bi bi-pencil-square"></i> Edytuj
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4 ">
            {{ $tests->links('pagination::bootstrap-4') }}
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


<script>
    $(document).ready(function(){
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            // Filtering table rows on larger screens
            $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>

</body>
</html>
