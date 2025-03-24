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

    <title>Edytuj test</title>
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
        <form action="{{route('tests.update', $test->id)}}" method="post">
            @csrf
            @method('patch')
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Edytuj test</h1>
            </div>

            <!-- Kierunek testu -->
            <h1 class="mt-4">Kierunek</h1>
            <input type="hidden" name="course" value="{{ $test->course->id }}">
            <p>{{$test->course->name}}</p>

            <!-- Przedmiot testu -->
            <h1 class="mt-4">Przedmiot</h1>
            <input type="hidden" name="subject" value="{{ $test->subject->id }}">
            <p>{{$test->subject->name}}</p>

            <!-- Wybór grupy -->
            <h1 class="mt-4">Grupa</h1>
            <input type="hidden" name="group" value="{{ $test->group->id }}">
            <p>{{ $test->group->name }}</p>

            <!-- Nazwa testu -->
            <h1 class="mt-4">Nazwa</h1>
            <input class="form-control mb-3" rows="4" name="name" placeholder="Wpisz nazwę testu" value="{{$test->name}}"></input>

            <!-- Limit czasowy -->
            <h1 class="mt-4">Okres dostępności</h1>
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="limitSwitch" name="limitSwitch" value="1" {{ $test->start_time && $test->end_time ? 'checked' : '' }}>
                    <label class="form-check-label" for="limitSwitch">Włącz/Wyłącz</label>
                </div>

                <div id="timeLimitSection" class="row g-2 mt-2 {{ $test->start_time && $test->end_time ? '' : 'd-none' }}">
                    <div class="col-md-6">
                        <label class="form-label">Od:</label>
                        <input type="datetime-local" class="form-control" name="start-time"
                               value="{{ $test->start_time}}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Do:</label>
                        <input type="datetime-local" class="form-control" name="end-time"
                               value="{{ $test->end_time }}">
                    </div>
                </div>
            </div>

            <!-- Czas trwania -->
            <h1 class="mt-4">Czas trwania (w minutach)</h1>
            <div class="mb-3">
                <input type="number" class="form-control" name="duration" placeholder="Wpisz liczbę minut" value="{{$test->duration}}">
            </div>

            <?php
            $number_of_trials = $test->number_of_trials ?? 0;
            $attemptsChecked = [
                'unlimited' => $number_of_trials == -1 ? 'checked' : '',
                'one' => $number_of_trials == 1 ? 'checked' : '',
                'multiple' => $number_of_trials > 1 ? 'checked' : ''
            ];
            ?>

                <!-- Ilość prób -->
            <h1 class="mt-4">Ilość prób</h1>
            <div class="mb-3">
                <!-- Opcja: Nieograniczona liczba prób -->
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="attempts" value="unlimited" id="attemptsUnlimited" {{ $attemptsChecked['unlimited'] }}>
                    <label class="form-check-label" for="attemptsUnlimited">Nieograniczona liczba</label>
                </div>

                <!-- Opcja: Jedno podejście -->
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="attempts" value="one" id="attemptsOne" {{ $attemptsChecked['one'] }}>
                    <label class="form-check-label" for="attemptsOne">Jedno podejście</label>
                </div>

                <!-- Opcja: Wiele podejść -->
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="attempts" value="multiple" id="attemptsMultiple" {{ $attemptsChecked['multiple'] }}>
                    <label class="form-check-label" for="attemptsMultiple">Wiele podejść</label>
                </div>

                <!-- Pole do wpisania liczby prób  -->
                <div class="mt-2 {{ $number_of_trials > 1 ? '' : 'd-none' }}" id="attemptsNumberSection">
                    <input type="number" class="form-control" id="number-of-attempts" name="number-of-attempts" min="1"
                           value="{{ $number_of_trials > 1 ? $number_of_trials : '' }}" placeholder="Wpisz liczbę">
                </div>
            </div>

            <!-- Wybór pytań -->
            <div class="mb-3">
                <h1>Pytania</h1>
                <select id="questions" name="questions[]" class="form-select" multiple>
                    @foreach($questions as $question)
                        <option value="{{ $question->id }}"
                                @if(in_array($question->id, $selectedQuestions)) selected @endif>
                            {{$question->subject->name}} {{$question->title}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex flex-row bd-highlight mb-3 gap-2">
                <button type="submit" class="btn btn-outline-danger mt-4 mb-5">Zapisz zmiany</button>
                <a href="{{ route('tests.show', $test->id) }}" class="btn btn-outline-danger mt-4 mb-5">Anuluj</a>
                <div class="ms-auto">
                    <a class="btn btn-outline-danger mt-4 mb-5" data-bs-toggle="modal" data-bs-target="#deleteModal">Usuń</a>
                </div>
            </div>


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

    <!-- Modal potwierdzenia usunięcia Grupy -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="card-title fs-4 mt-2" id="logoutModalLabel">Potwierdzenie usunięcia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Czy na pewno chcesz usunąć tę grupę?
                </div>
                <div class="modal-footer">
                    <form action="{{route('tests.delete', $test->id)}}" method="post">
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
    new MultiSelectTag('questions')  // id
</script>
<!-- multi_select.js -->

<!-- Скрипт для динамического переключения -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const limitSwitch = document.getElementById('limitSwitch');
        const timeLimitSection = document.getElementById('timeLimitSection');

        // Показывать блок, если switch включен (например, при редактировании теста)
        if (limitSwitch.checked) {
            timeLimitSection.classList.remove('d-none');
        }

        // Переключение видимости секции при изменении переключателя
        limitSwitch.addEventListener('change', function() {
            timeLimitSection.classList.toggle('d-none', !this.checked);
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const attemptsRadios = document.querySelectorAll('input[name="attempts"]');
        const attemptsNumberSection = document.getElementById('attemptsNumberSection');
        const numberOfAttemptsInput = document.getElementById('number-of-attempts');

        if (document.getElementById('attemptsMultiple').checked) {
            attemptsNumberSection.classList.remove('d-none');
        }

        attemptsRadios.forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (this.value === 'multiple') {
                    attemptsNumberSection.classList.remove('d-none');
                    numberOfAttemptsInput.value = numberOfAttemptsInput.value || 1;
                } else {
                    attemptsNumberSection.classList.add('d-none');
                    numberOfAttemptsInput.value = '';
                }
            });
        });
    });
</script>

</body>
</html>
