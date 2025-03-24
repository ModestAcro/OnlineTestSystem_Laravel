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

    <title>Edytuj pytanie</title>
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
                        <a class="nav-link" href="{{route('questions.index')}}">Pytania</a>
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
        <form action="{{route('questionTrueFalse.update', $question->id)}}" method="post">
            @csrf
            @method('patch')

            @error('subject')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('title')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('answer')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('points')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="d-flex justify-content-between align-items-center">
                <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Edytuj pytanie</h1>
            </div>

            <h1 class="mt-5">Przedmiot</h1>
            <select name="subject" class="form-select mb-4" required>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}"
                        {{ $question->subject_id == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>

            <h1 class="mt-2">Treść</h1>
            <textarea class="form-control mb-3 editor" rows="7" name="title" id="question_text" placeholder="Wpisz treść pytania">{{$question->title}}</textarea>

            <h1 class="mt-3">Odpowiedź</h1>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="answer" id="true_answer" value="1">
                <label class="form-check-label" for="true_answer">
                    Prawda
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="radio" name="answer" id="false_answer" value="0">
                <label class="form-check-label" for="false_answer">
                    Fałsz
                </label>
            </div>


            <div class="col-auto">
                <h1 class="mt-4">Punkty</h1>
                @foreach($question->answers as $answer)
                    @if($answer->is_correct == 1)
                        <input type="number" class="form-control" name="points" value="{{ $answer->points }}">
                    @endif
                @endforeach
            </div>


            <div class="d-flex flex-row bd-highlight mb-3 gap-2">
                <button type="submit" class="btn btn-outline-danger mt-4 mb-5">Zapisz zmiany</button>
                <a href="{{ route('questionTrueFalse.show', $question->id) }}" class="btn btn-outline-danger mt-4 mb-5">Anuluj</a>
                <div class="ms-auto">
                    <a class="btn btn-outline-danger mt-4 mb-5" data-bs-toggle="modal" data-bs-target="#deleteModal">Usuń</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal potwierdzenia usunięcia Pytania -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="card-title fs-4 mt-2" id="logoutModalLabel">Potwierdzenie usunięcia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Czy na pewno chcesz usunąć te pytanie?
                </div>
                <div class="modal-footer">
                    <form action="{{route('questionTrueFalse.delete', $question->id)}}" method="post">
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let container = document.getElementById('answers-container');
        let answerIndex = {{ count($question->answers) }}; // Считаем существующие ответы

        function renumberAnswers() {
            let answers = container.querySelectorAll('.answer-options');
            answers.forEach((answer, index) => {
                answer.querySelector('label').innerText = `Odpowiedź: ${index + 1}:`;

                // Обновляем атрибуты name, чтобы Laravel получил правильные данные
                answer.querySelector('textarea').setAttribute('name', `answers[${index}][text]`);
                answer.querySelector('input[type="number"]').setAttribute('name', `answers[${index}][points]`);
                answer.querySelector('input[type="checkbox"]').setAttribute('name', `answers[${index}][is_correct]`);
            });
        }

        document.getElementById('add-answer-btn').addEventListener('click', function () {
            let newAnswer = document.createElement('div');
            newAnswer.className = "answer-options d-flex flex-wrap align-items-center gap-3 mt-3 p-2 border rounded shadow-sm";
            newAnswer.innerHTML = `
            <div class="flex-grow-1">
                <label class="form-label">Odpowiedź: ${answerIndex + 1}:</label>
                <textarea type="text" class="form-control" name="answers[${answerIndex}][text]" required></textarea>
            </div>
            <div class="col-auto">
                <label class="form-label">Punkty</label>
                <input type="number" class="form-control" name="answers[${answerIndex}][points]" required>
            </div>
            <div class="col-auto d-flex align-items-center">
                <div class="form-check mt-3">
                    <input type="checkbox" class="form-check-input" name="answers[${answerIndex}][is_correct]" value="1">
                    <label class="form-check-label">Poprawna</label>
                </div>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-danger remove-answer-btn">Usuń</button>
            </div>
        `;
            container.appendChild(newAnswer);
            answerIndex++;
            renumberAnswers();
        });

        container.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-answer-btn')) {
                event.target.closest('.answer-options').remove();
                renumberAnswers();
            }
        });
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
