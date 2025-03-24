<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wyniki testu</title>

    <!-- Bootstrap 5.3 css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../assets/css/main.css">

</head>
<body>
<main class="main">
    <div class="container card shadow mt-5 text-center">
        <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2 mt-5 mb-5">Test zakończony!</h1>
        <p class="lead">Czas trwania testu:
            @php
                $startTime = \Carbon\Carbon::parse($resultTest->start_time);
                $endTime = \Carbon\Carbon::parse($resultTest->end_time);
                $duration = $startTime->diff($endTime);
            @endphp
            {{$duration->format('%H godzin %I minut %S sekund')}}
        </p>
        <p class="lead">Zdobyłeś:  {{$resultTest->earned_score}} z {{$resultTest->max_score}} punktów</p>
        <p class="lead">Wynik procentowy: {{$resultTest->percent_score}}%</p>
        <p class="lead">Twoja ocena: {{$resultTest->result}} </p>
        <br>
        <a class="btn btn-outline-danger mb-5" href="{{route('student.dashboard')}}">Dalej</a>
    </div>
</main>
</body>
</html>
