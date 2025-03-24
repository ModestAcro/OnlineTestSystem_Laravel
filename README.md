# System do torzenia i przeprowadzania testów wyboru

## Opis projektu
Ten system to aplikacja webowa przeznaczona dla administratorów, nauczycieli i uczniów, umożliwiająca tworzenie oraz przeprowadzanie testów online. System został zaprojektowany w celu usprawnienia zarządzania użytkownikami, tworzenia testów i analizowania wyników.

---

## Funkcjonalności

### Administrator systemu:
- **Rejestrowania i edytowania nauczycieli oraz uczniów.**
- **Zarządzanie przedmiotami (dodawanie, edytowanie, usuwanie).**
- **Zarządzanie kierunkami (dodawanie, edytowanie, usuwanie).**

### Dla nauczycieli:
- **Logowanie**
- **Tworzyć grupy studentów.**
- **Dodawać pytania do bazy pytań.**
- **Tworzyć testy z wykorzystaniem wcześniej utworzonych pytań.**
- **Sprawdzać odpowiedzi i wyniki studentów na przeprowadzonych testach.**

### Dla uczniów:
- **Logowanie**
- **Rozwiązywanie testów.**
- **Przeglądania wyników swoich testów po ich zakończeniu.**

---

## Technologie
<!-- https://github.com/marwin1991/profile-technology-icons -->

- <img src="public/images/README/Laravel.png" alt="Laravel" style="width:30px; vertical-align:middle;"> <span>Laravel</span>

- <img src="public/images/README/PHP.png" alt="PHP" style="width:30px; vertical-align:middle;"> <span>PHP</span>

- <img src="public/images/README/HTML5.png" alt="HTML" style="width:30px; vertical-align:middle;"> <span>HTML</span>

- <img src="public/images/README/Bootstrap.png" alt="Bootstrap" style="width:30px; vertical-align:middle;"> <span>Bootstrap</span>

- <img src="public/images/README/CSS3.png" alt="CSS" style="width:30px; vertical-align:middle;"> <span>CSS</span>

- <img src="public/images/README/Sass.png" alt="Sass" style="width:30px; vertical-align:middle;"> <span>Sass</span>

- <img src="public/images/README/JavaScript.png" alt="JavaScript" style="width:30px; vertical-align:middle;"> <span>JavaScript</span>

- <img src="public/images/README/MySQL.png" alt="MySQL" style="width:30px; vertical-align:middle;"> <span>MySQL</span>

- <img src="public/images/README/Git.png" alt="Git" style="width:30px; vertical-align:middle;"> <span>Git</span>

- <img src="public/images/README/phpspreadsheet.png" alt="jQuery" style="width:30px; vertical-align:middle;"> <span>PhpSpreadSheet<span>

- <img src="public/images/README/Composer.png" alt="jQuery" style="width:30px; vertical-align:middle;"> <span>Composer<span>



---

## Instalacja

1. **Klonowanie repozytorium**:

   `git clone https://github.com/ModestAcro/OnlineTestSystem_Laravel.git`

2. **Instalacja wymaganych narzędzi**:
    - MAMP: [https://www.mamp.info/en/mac/](https://www.mamp.info/en/mac/)
    - XAMPP: [https://www.apachefriends.org/](https://www.apachefriends.org/)

3. **Konfiguracja bazy danych**:  
    - Uruchom MAMP/XAMPP i włącz serwer MySQL.
    - Otwórz nową bazę danych.
    - Skopiuj plik `.env.example` i zmień jego nazwę na `.env`
    - Otwórz `.env` i dostosuj ustawienia bazy danych (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
    - Wykonaj migracje `"php artisan migrate"`.
   
4. **Uruchomienie serwera**:  
    - Uruchom serwer aplikacji: `"php artisan serve"`
    - Otwórz przeglądarkę i przejdź do: `"http://127.0.0.1:8000"`

---

## Autor

Modest Semionow





