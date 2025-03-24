<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5.3 css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    <title>Panel wykładowcy</title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('questions.index')}}">Pytania</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('groups.index')}}">Grupy studentów</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('completed-tests.index')}}">Wyniki testów</a>
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
    <div class="container py-4">
        <div class="row g-3">
            <!-- Testy -->
            <div class="col-md-6 col-lg-3">
                <a href="{{route('tests.index')}}" class="text-decoration-none text-dark">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <img src="{{asset('images/icons/online-test.svg')}}" alt="Teacher Icon" class="img-fluid mb-2" style="width: 50px;">
                            <h3 class="card-title">Testy</h3>
                            <p class="card-text">{{$testsCount}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Testy -->

            <!-- Pytania-->
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('questions.index') }}" class="text-decoration-none text-dark">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <img src="{{asset('images/icons/question.svg')}}" alt="Student Icon" class="img-fluid mb-2" style="width: 50px;">
                            <h3 class="card-title">Pytania</h3>
                            <p class="card-text">{{$questionsCount}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Pytania -->

            <!-- Grupy studentów -->
            <div class="col-md-6 col-lg-3">
                <a href="{{route('groups.index')}}" class="text-decoration-none text-dark">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <img src="{{asset('images/icons/group.svg')}}" alt="Subject Icon" class="img-fluid mb-2" style="width: 50px;">
                            <h3 class="card-title">Grupy studentów</h3>
                            <p class="card-text">{{$groupsCount}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Grupy studentów -->

            <!-- Wyniki testów -->
            <div class="col-md-6 col-lg-3">
                <a href="{{route('completed-tests.index')}}" class="text-decoration-none text-dark">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <img src="{{asset('images/icons/checked.svg')}}" alt="Subject Icon" class="img-fluid mb-2" style="width: 50px;">
                            <h3 class="card-title">Wyniki testów</h3>
                            <p class="card-text">{{$completedTestCount}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Wyniki testów -->
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
