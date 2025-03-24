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

    <title>Pytania</title>
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
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Lista pytań</h1>
            <div class="dropdown">
                <a class="btn btn-outline-danger dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Utwórz pytanie
                </a>

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{route('questionMulti.create')}}">Wielokrotnego wyboru</a></li>
                    <li><a class="dropdown-item" href="{{route('questionTrueFalse.create')}}">Prawda/Fałsza</a></li>
                </ul>
            </div>
        </div>
        <p>Ilość: {{$questionsCount}}</p>

        <!-- SEARCH -->
        <input class="form-control" id="myInput" type="text" placeholder="Szukaj...">
        <!-- SEARCH -->

        <div class="table-responsive mt-4 d-none d-sm-block">
            <table class="table">
                <thead class="table-active">
                <tr>
                    <th>Nr.</th>
                    <th>Przedmiot</th>
                    <th>Pytanie</th>
                    <th>Typ</th>
                    <th>Akcje</th>
                </tr>
                </thead>
                <tbody id="myTable">
                @foreach($questions as $question)
                    <tr>
                        <td>{{ $loop->iteration + $questions->firstItem() - 1 }}</td>
                        <td>{{$question->subject->name}}</td>
                        <td><pre>{!! strip_tags($question->title, '<strong><b><i><em>') !!}</pre></td>
                        <td>
                            @if($question->type == 'multi_choice')
                                Wielokrotnego wyboru
                            @elseif($question->type == 'true_false')
                                Prawda/Fałsza
                            @endif
                        </td>
                        <td>
                            @if($question->type === 'true_false')
                                <a href="{{ route('questionTrueFalse.show', $question->id) }}" class="btn btn-outline-danger">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            @else
                                <a href="{{ route('questionMulti.show', $question->id) }}" class="btn btn-outline-danger">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Widok dla tabletów -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 mt-4 d-block d-sm-none">
            @foreach($questions as $question)
                <div class="col mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <p class="card-text"><strong>Nr. {{ $loop->iteration + $questions->firstItem() - 1 }}</strong></p>
                            <p class="card-text"><strong>Predmiot:</strong> {{$question->subject->name}}</p>
                            <p class="card-text">
                                @if($question->type == 'multi_choice')
                                    <strong>Typ:</strong> Wielokrotnego wyboru
                                @elseif($question->type == 'true_false')
                                    <strong>Typ:</strong> Prawda/Fałsza
                                @endif
                            </p>
                            <td><pre>{!! strip_tags($question->title, '<strong><b><i><em>') !!}</pre></td>
                            <div class="card-footer text-end">
                                <a href="{{ route($question->type === 'true_false' ? 'questionTrueFalse.show' : 'questionMulti.show', $question->id) }}" class="btn btn-outline-danger">
                                    <i class="bi bi-pencil-square"></i> Edytuj
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4 ">
            {{ $questions->links('pagination::bootstrap-4') }}
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
