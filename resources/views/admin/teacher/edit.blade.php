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

    <title>Wykładowcy</title>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold">
                {{Auth::guard('admin')->user()->name . " " . Auth::guard('admin')->user()->surname}}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon custom-toggler"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('teachers.index')}}">Wykładowcy</a>
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
        <form action="{{route('teachers.update', $teacher->id)}}" method="post">
            @csrf
            @method('patch')
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Edytuj wykładowcę</h1>
            </div>

            <div class="mb-3">
                <label class="form-label">Kierunki</label>
                <select id="courses" name="courses[]" class="form-select" multiple>
                    @foreach($courses as $course)
                        <option value="{{$course->id}}"
                            @if(in_array($course->id, $selectedCourses)) selected @endif>
                            {{$course->name}}
                        </option>
                    @endforeach
                </select>
            </div>

            <h1 class="mt-2">Stopień</h1>
            <input class="form-control mb-3" rows="4" name="degree" placeholder="Wpisz stopień wykładowcy" value="{{$teacher->degree}}">

            <h1 class="mt-2">Imię</h1>
            <input class="form-control mb-3" rows="4" name="name" placeholder="Wpisz imię wykładowcy" value="{{$teacher->name}}">

            <h1 class="mt-2">Nazwisko</h1>
            <input class="form-control mb-3" rows="4" name="surname" placeholder="Wpisz nazwisko wykładowcy" value="{{$teacher->surname}}">

            <h1 class="mt-2">Email</h1>
            <input type="email" class="form-control mb-3" rows="4" name="email" placeholder="Wpisz email wykładowcy" value="{{$teacher->email}}">

            <h1 class="mt-2">Hasło</h1>
            <input type="password" class="form-control mb-3" rows="4" name="password" placeholder="Wpisz hasło wykładowcy" value="">

            <h1>Uwagi</h1>
            <textarea class="form-control mb-3" rows="4" name="comments" placeholder="Wpisz uwagi" >{{$teacher->comments}}</textarea>

            <button type="submit" class="btn btn-outline-danger mb-5">Zapisz zmiany</button>
            <a class="btn btn-outline-danger mb-5" data-bs-toggle="modal" data-bs-target="#deleteModal">Usuń</a>

            @error('courses')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('degree')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('surname')
            <div class="alert alert-danger ">{{ $message }}</div>
            @enderror
            @error('album_number')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('email')
            <div class="alert alert-danger ">{{ $message }}</div>
            @enderror
            @error('password')
            <div class="alert alert-danger ">{{ $message }}</div>
            @enderror
        </form>
    </div>

    <!-- Modal potwierdzenia usunięcia Kierunku -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="card-title fs-4 mt-2" id="logoutModalLabel">Potwierdzenie usunięcia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Czy na pewno chcesz usunąć tego wykładowcę?
                </div>
                <div class="modal-footer">
                    <form action="{{route('teachers.delete', $teacher->id)}}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-outline-danger">Usuń</button>
                    </form>
                    <button class="btn btn-outline-danger" data-bs-dismiss="modal">Anuluj</button>
                </div>
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

<script src="{{ asset('assets/multi_select.js') }}"></script>

<!-- multi_select.js -->
<script>
    new MultiSelectTag('courses')  // id
</script>
<!-- multi_select.js -->

</body>
</html>
