<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Rozpocznij test</title>

</head>
<body>
<main class="main">
    <div class="container mt-5">
        <form action="{{route('student.test.store', $test->id)}}" method="POST" id="test-form">
            @csrf
            <input type="hidden" name="start_time" value="{{ $start_time->toDateTimeString()}}">

            <div class="tests-box">
                <div class="d-flex justify-content-between align-items-center mt-4 mb-5">
                    <!-- Licznik pytań -->
                    <div class="text-left">
                        <span id="question-counter"></span>
                    </div>
                    <!-- Timer -->
                    <div class="text-right">
                        <div class="timer" id="timer"></div>
                    </div>
                </div>

                <!-- Test Questions -->
                <div id="questions-container">
                    @foreach($questions as $index => $question)
                        <div class="question-card card mb-4">
                            <div class="card-header">
                                <h5><pre>{!! strip_tags($question->title, '<strong><b><i><em>') !!}</pre></h5>

                                <label class="card-subtitle text-muted">
                                    @if($question->type == 'true_false')
                                        {{'Prawda/Fałsz'}}
                                    @elseif($question->type == 'multi_choice')
                                        {{'Wielokrotnego wyboru'}}
                                    @endif
                                </label>
                            </div>
                            <div class="card-body">
                                @if($question->type == 'true_false')
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="question_{{ $question->id }}" value="1">
                                        <label class="form-check-label">Prawda</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="question_{{ $question->id }}" value="0">
                                        <label class="form-check-label">Fałsz</label>
                                    </div>
                                @else
                                    @foreach($question->answers as $answer)
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="question_{{ $question->id }}[]" value="{{ $answer->id }}">
                                            <label class="form-check-label"><pre>{{ $answer->title }}</pre></label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <!-- Pagination -->
                    <nav>
                        <ul class="pagination mb-0">
                            <li class="page-item">
                                <button type="button" class="btn btn-outline-danger" id="prev-btn" disabled>Poprzednie</button>
                            </li>
                            <li class="page-item mx-2">
                                <button type="button" class="btn btn-outline-danger" id="next-btn">Następne</button>
                            </li>
                        </ul>
                    </nav>
                    <!-- Submit Button -->
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmModal">
                        Zakończ test
                    </button>
                </div>

            </div>
        </form>
    </div>

    <!-- Modal potwierdzenia zakończenia testu -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="card-title fs-4 mt-2" id="logoutModalLabel">Potwierdzenie zakończenia testu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Czy na pewno chcesz zakończyć test? Nie będzie już można wprowadzić zmian.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-outline-danger" form="test-form">Zakończ test</button>
                </div>
            </div>
        </div>
    </div>
</main>


<!-- JavaScript do paginacji -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let currentQuestion = 0;
        const questions = document.querySelectorAll(".question-card");
        const totalQuestions = questions.length;

        const prevBtn = document.getElementById("prev-btn");
        const nextBtn = document.getElementById("next-btn");
        const questionCounter = document.getElementById("question-counter");

        function updatePagination() {
            questions.forEach((q, index) => {
                q.style.display = index === currentQuestion ? "block" : "none";
            });

            prevBtn.disabled = currentQuestion === 0;
            nextBtn.disabled = currentQuestion === totalQuestions - 1;

            // Aktualizacja licznika pytań
            questionCounter.textContent = `Pytanie ${currentQuestion + 1} z ${totalQuestions}`;
        }

        prevBtn.addEventListener("click", function (event) {
            event.preventDefault();
            if (currentQuestion > 0) {
                currentQuestion--;
                updatePagination();
            }
        });

        nextBtn.addEventListener("click", function (event) {
            event.preventDefault();
            if (currentQuestion < totalQuestions - 1) {
                currentQuestion++;
                updatePagination();
            }
        });

        updatePagination();
    });
</script>

<script>
    // Pobierz czas zakończenia z PHP
    var czasZakonczenia = new Date().getTime() + {{ $test->duration }} * 60 * 1000; // Dodanie czasu trwania testu w milisekundach

    function odliczanie() {
        var teraz = new Date().getTime();
        var roznica = czasZakonczenia - teraz;

        var minuty = Math.floor((roznica % (1000 * 60 * 60)) / (1000 * 60));
        var sekundy = Math.floor((roznica % (1000 * 60)) / 1000);

        document.getElementById("timer").innerHTML = minuty + "m " + sekundy + "s ";

        if (roznica < 0) {
            clearInterval(x);
            document.getElementById("timer").innerHTML = "KONIEC CZASU";
            // Automatyczne wysłanie formularza po upływie czasu
            document.querySelector('form').submit();
        }
    }

    var x = setInterval(odliczanie, 1000);
</script>

</body>
</html>
