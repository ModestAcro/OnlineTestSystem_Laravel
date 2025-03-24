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

    <title>Utwórz grupę</title>
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
                        <a class="nav-link" href="{{route('groups.index')}}">Grupy</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <a class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#logoutModal">Wyloguj</a>
                </form>
            </div>
        </div>
    </nav>
</header>
<main class="main my-5">
    <div class="container card shadow p-4">
        <form action="{{route('groups.store')}}" method="post">
            @csrf
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Utwórz grupę</h1>
            </div>

            <h1 class="mt-5">Nazwa</h1>
            <input class="form-control mb-3" rows="4" name="name" id="question_text" placeholder="Wpisz nazwę grupy" value="{{old('name')}}">

            <h1 class="mt-3">Rok</h1>
            <input class="form-control mb-3" rows="4" name="year" id="question_text" placeholder="Wpisz rok grupy" value="{{old('year')}}">

            <h1 class="mt-4">Kierunek</h1>
            <select name="course" id="course" class="form-select mb-4">
                <option value="" disabled selected>Wybierz kierunek</option>
                @foreach($courses as $course)
                    <option value="{{$course->id}}">{{$course->name}}</option>
                @endforeach
            </select>

            <!-- Przedmiot testu -->
            <h1 class="mt-4">Przedmiot</h1>
            <select name="subject" id="subject" class="form-select mb-4">
                <option value="" disabled selected>Wybierz przedmiot</option>
            </select>

            <div class="mb-3">
                <h1>Studeńci</h1>
                <select id="students" name="students[]" class="form-select"  multiple>
                        @foreach($students as $student)
                        <option value="{{$student->id}}">
                            {{$student->album_number}} - {{$student->name}} {{$student->surname}}
                        </option>
                        @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-outline-danger mb-5">Utwórz grupę</button>

            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('year')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('course')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('subject')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('students')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
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

<script src="{{ asset('assets/multi_select.js') }}"></script>

<!-- multi_select.js -->
<script>
    new MultiSelectTag('students')  // id
</script>
<!-- multi_select.js -->

<!-- AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Когда выбран курс
        $('#course').change(function() {
            var courseId = $(this).val();
            if (courseId) {
                // Отправить AJAX запрос, чтобы получить связанные предметы
                $.ajax({
                    url: '/get/group/subjects/' + courseId, // Ваш маршрут для получения предметов
                    type: 'GET',
                    success: function(data) {
                        // Очистить старые элементы
                        $('#subject').empty();
                        $('#subject').append('<option value="" disabled selected>Wybierz przedmiot</option>');
                        // Добавить новые предметы
                        $.each(data.subjects, function(key, subject) {
                            $('#subject').append('<option value="'+subject.id+'">'+subject.name+'</option>');
                        });
                    }
                });
            } else {
                $('#subject').empty();
                $('#subject').append('<option value="" disabled selected>Wybierz przedmiot</option>');
            }
        });
    });
</script>

</body>
</html>
