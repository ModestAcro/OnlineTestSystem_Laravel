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

    <title>Utwórz pytanie</title>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a href="{{route('teacher.dashboard')}}" class="navbar-brand fw-bold">
                {{Auth::guard('teacher')->user()->name . " " . Auth::guard('teacher')->user()->surname}}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon custom-toggler"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('questions.index')}}">Lista pytań</a>
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
        <form action="{{route('questionMulti.store')}}" method="post">
            @csrf

            @error('title')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('subject')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('answers')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('points')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('is_correct')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="d-flex justify-content-between align-items-center">
                <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Wielokrotnego wyboru</h1>
            </div>


            <h1 class="mt-5">Przedmiot</h1>
            <select name="subject" class="form-select mb-4">
                <option value="" disabled {{ old('subject') == '' ? 'selected' : '' }}>Wybierz przedmiot</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ old('subject') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>


            <h1 class="mt-3">Treść</h1>
            <textarea class="form-control mb-3 editor" rows="7" name="title" id="question_text" placeholder="Wpisz treść pytania">{{old('title')}}</textarea>

            <div id="answers-container">
                @php
                    $oldAnswers = old('answers', []);
                    $oldPoints = old('points', []);
                    $oldCorrect = old('is_correct', []);
                @endphp

                @foreach ($oldAnswers as $index => $answer)
                    <div class="answer-options d-flex flex-wrap align-items-center gap-3 mt-3 p-2 border rounded shadow-sm">
                        <div class="flex-grow-1">
                            <h4 class="answer-number">Odpowiedź {{ $index + 1 }}</h4>
                            <textarea class="form-control" name="answers[]" rows="2" required>{{ $answer }}</textarea>
                        </div>
                        <div class="col-auto">
                            <h4>Punkty</h4>
                            <input type="number" class="form-control" name="points[]" value="{{ $oldPoints[$index] ?? 0 }}" required>
                        </div>
                        <div class="col-auto d-flex align-items-center">
                            <div class="form-check mt-3">
                                <input type="checkbox" class="form-check-input" name="is_correct[]" value="Option {{ $index }}"
                                    {{ in_array("Option $index", $oldCorrect) ? 'checked' : '' }}>
                                <label class="form-check-label">Poprawna</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-danger remove-answer-btn">Usuń</button>
                        </div>
                    </div>
                @endforeach
            </div>


            <div class="d-flex justify-content-between align-items-center mt-5 mb-5">
                <button class="btn btn-outline-danger" id="add-answer-btn">
                    <i class="bi bi-plus-circle"></i>
                    <span>Dodaj odpowiedź</span>
                </button>
            </div>
            <button type="submit" class="btn btn-outline-danger mb-5">Zapisz pytanie</button>
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

<script>

    document.addEventListener("DOMContentLoaded", function () {
        let answerIndex = document.querySelectorAll("#answers-container .answer-options").length; // Ustalamy liczbę istniejących odpowiedzi

        document.getElementById("add-answer-btn").addEventListener("click", function (event) {
            event.preventDefault();
            addAnswer();
        });

        function addAnswer(text = "", points = 0, isChecked = false) {
            const answersContainer = document.getElementById("answers-container");
            const newAnswerDiv = document.createElement("div");

            newAnswerDiv.className = "answer-options d-flex flex-wrap align-items-center gap-3 mt-3 p-2 border rounded shadow-sm";
            newAnswerDiv.innerHTML = `
        <div class="flex-grow-1">
            <h4 class="answer-number">Odpowiedź ${answerIndex + 1}</h4>
            <textarea class="form-control" name="answers[]" rows="2" required>${text}</textarea>
        </div>
        <div class="col-auto">
            <h4>Punkty</h4>
            <input type="number" class="form-control" name="points[]" value="${points}" required>
        </div>
        <div class="col-auto d-flex align-items-center">
            <div class="form-check mt-3">
                <input type="checkbox" class="form-check-input" name="is_correct[]" value="Option ${answerIndex}" ${isChecked ? "checked" : ""} id="checkbox_${answerIndex}" ${isChecked ? "checked" : ""}>
                <label class="form-check-label" for="checkbox_${answerIndex}">Poprawna</label>
            </div>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-danger remove-answer-btn">Usuń</button>
        </div>
    `;

            answersContainer.appendChild(newAnswerDiv);
            answerIndex++;
            updateAnswerNumbers();

            newAnswerDiv.querySelector(".remove-answer-btn").addEventListener("click", function () {
                answersContainer.removeChild(newAnswerDiv);
                updateAnswerNumbers();
            });
        }

        function updateAnswerNumbers() {
            document.querySelectorAll("#answers-container .answer-options").forEach((el, index) => {
                el.querySelector(".answer-number").textContent = `Odpowiedź ${index + 1}`;
            });
        }

        // Jeśli nie ma poprzednich odpowiedzi, dodaj domyślnie kilka pustych pól
        if (answerIndex === 0) {
            addAnswer();
            addAnswer();
            addAnswer();
        }
    });


</script>
<!-- Tiny -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.6.0/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector: '.editor'
    });
</script>
</body>
</html>
