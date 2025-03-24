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

    <title>Utwórz test</title>
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
                        <a class="nav-link" href="{{route('tests.index')}}">Lista testów</a>
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
        <form action="{{route('tests.store')}}" method="post">
            @csrf
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Utwórz test</h1>
            </div>

            <!-- Kierunek testu -->
            <h1 class="mt-4">Kierunek</h1>
            <select name="course" id="course" class="form-select mb-4">
                <option value="" disabled selected>Wybierz kierunek</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
            </select>

            <!-- Przedmiot testu -->
            <h1 class="mt-4">Przedmiot</h1>
            <select name="subject" id="subject" class="form-select mb-4">
                <option value="" disabled selected>Wybierz przedmiot</option>
            </select>

            <!-- Wybór grupy -->
            <h1 class="mt-4">Grupa</h1>
            <select name="group" id="group" class="form-select mb-4">
                <option value="" disabled selected>Wybierz grupę</option>
            </select>

            <!-- Nazwa testu -->
            <h1 class="mt-4">Nazwa</h1>
            <input class="form-control mb-3" rows="4" name="name" placeholder="Wpisz nazwę testu" value="{{old('name')}}">

            <!-- Limit czasowy -->
            <h1 class="mt-4">Okres dostępności</h1>
            <div class="mb-3">
                <!-- Przełącznik (switch) do włączania/wyłączania limitu czasowego -->
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="limitSwitch">
                    <label class="form-check-label" for="limitSwitch">Włącz/Wyłącz</label>
                </div>

                <!-- Sekcja z wyborem daty, domyślnie ukryta -->
                <div id="timeLimitSection" class="row g-2 mt-2 d-none">
                    <div class="col-md-6">
                        <label class="form-label">Od:</label>
                        <input type="datetime-local" class="form-control" name="start-time">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Do:</label>
                        <input type="datetime-local" class="form-control" name="end-time">
                    </div>
                </div>
            </div>

            <!-- Czas trwania -->
            <h1 class="mt-4">Czas trwania (w minutach)</h1>
            <div class="mb-3">
                <input type="number" class="form-control" name="duration" placeholder="Wpisz liczbę minut" value="{{old('duration')}}">
            </div>

            <!-- Ilość prób -->
            <h1 class="mt-4">Ilość prób</h1>
            <div class="mb-3">
                <!-- Opcja: Nieograniczona liczba prób -->
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="attempts" value="unlimited" id="attemptsUnlimited" checked>
                    <label class="form-check-label" for="attemptsUnlimited">Nieograniczona liczba</label>
                </div>

                <!-- Opcja: Jedno podejście -->
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="attempts" value="one" id="attemptsOne">
                    <label class="form-check-label" for="attemptsOne">Jedno podejście</label>
                </div>

                <!-- Opcja: Wiele podejść -->
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="attempts" value="multiple" id="attemptsMultiple">
                    <label class="form-check-label" for="attemptsMultiple">Wiele podejść</label>
                </div>

                <!-- Pole do wpisania liczby prób (domyślnie ukryte) -->
                <div class="mt-2 d-none" id="attemptsNumberSection">
                    <input type="number" class="form-control" id="number-of-attempts" name="number-of-attempts" min="1" placeholder="Wpisz liczbę">
                </div>
            </div>

            <!-- Wybór pytań -->
            <h1 class="mt-4">Pytania</h1>
            <div class="mb-3">
                <select id="questions" name="questions[]" class="form-select" multiple>
                    @foreach($questions as $question)
                        <option value="{{$question->id}}">
                            {{$question->subject->name}} - {{$question->title}}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-outline-danger mb-5">Zapisz test</button>
            @error('course')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('subject')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('group')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('duration')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('attempts')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('questions')
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
    new MultiSelectTag('questions')  // id
</script>
 <!-- multi_select.js -->

<!-- Skrypt do obsługi przełącznika daty ważności testu-->
<script>
    document.getElementById('limitSwitch').addEventListener('change', function() {
        const timeLimitSection = document.getElementById('timeLimitSection');
        if (this.checked) {
            timeLimitSection.classList.remove('d-none');
        } else {
            timeLimitSection.classList.add('d-none');
        }
    });
</script>

<!-- Skrypt do obsługi przełączania ilości podejść testu -->
<script>
    document.querySelectorAll('input[name="attempts"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const attemptsNumberSection = document.getElementById('attemptsNumberSection');

            if (this.value === 'multiple') {
                attemptsNumberSection.classList.remove('d-none');
            } else {
                attemptsNumberSection.classList.add('d-none');
            }
        });
    });
</script>

<!-- AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#course').change(function() {
            var courseId = $(this).val();
            if (courseId) {
                $.ajax({
                    url: '/get-subjects/' + courseId,
                    type: 'GET',
                    success: function(data) {
                        // Очистить старые элементы
                        $('#subject').empty();
                        $('#subject').append('<option value="" disabled selected>Wybierz przedmiot</option>');
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

        $('#subject').change(function() {
            var subjectId = $(this).val();
            if (subjectId) {
                $.ajax({
                    url: '/get-groups/' + subjectId,
                    type: 'GET',
                    success: function(data) {
                        $('#group').empty();
                        $('#group').append('<option value="" disabled selected>Wybierz grupę</option>');
                        // Добавить новые группы
                        $.each(data.groups, function(key, group) {
                            $('#group').append('<option value="'+group.id+'">'+group.name+' ('+group.year+')</option>');

                        });
                    }
                });
            } else {
                $('#group').empty();
                $('#group').append('<option value="" disabled selected>Wybierz grupę</option>');
            }
        });
    });
</script>

</body>
</html>
