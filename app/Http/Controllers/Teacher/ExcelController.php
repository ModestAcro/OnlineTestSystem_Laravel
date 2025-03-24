<?php

namespace App\Http\Controllers\Teacher;

use App\Models\ResultTest;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ExcelController extends Controller
{
    public function export($test_id)
    {
        // Pobierz wszystkie wyniki powiązane z danym testem
        $results = ResultTest::where('test_id', $test_id)
            ->with('student')
            ->get();

        // Tworzymy nowy plik Excel
        $spreadsheet = new Spreadsheet();

        // Arkusz 1: Wszystkie wyniki
        $sheet1 = $spreadsheet->createSheet(0);
        $sheet1->setTitle('Wszystkie próby');

        // Nagłówki kolumn w Excelu
        $sheet1->setCellValue('A1', 'Imię');
        $sheet1->setCellValue('B1', 'Nazwisko');
        $sheet1->setCellValue('C1', 'Nr Albumu');
        $sheet1->setCellValue('D1', 'Punkty');
        $sheet1->setCellValue('E1', 'Ocena');
        $sheet1->setCellValue('F1', 'Wynik (%)');
        $sheet1->setCellValue('G1', 'Data Rozpoczęcia');
        $sheet1->setCellValue('H1', 'Data Zakończenia');

        // Arkusz 2: Najlepsze wyniki każdego ucznia
        $sheet2 = $spreadsheet->createSheet(1);
        $sheet2->setTitle('Najlepsza próba');

        // Nagłówki kolumn w Arkuszu 2
        $sheet2->setCellValue('A1', 'Imię');
        $sheet2->setCellValue('B1', 'Nazwisko');
        $sheet2->setCellValue('C1', 'Nr Albumu');
        $sheet2->setCellValue('D1', 'Punkty');
        $sheet2->setCellValue('E1', 'Ocena');
        $sheet2->setCellValue('F1', 'Wynik (%)');
        $sheet2->setCellValue('G1', 'Data Rozpoczęcia');
        $sheet2->setCellValue('H1', 'Data Zakończenia');

        // Tablica do przechowywania najlepszych wyników
        $bestResults = [];

        // Wyszukiwanie najlepszych wyników dla każdego ucznia
        foreach ($results as $result) {
            // Jeśli jeszcze nie ma wyniku dla danego ucznia lub jest on lepszy niż poprzedni
            if (!isset($bestResults[$result->student->id]) || $bestResults[$result->student->id]->earned_score < $result->earned_score) {
                $bestResults[$result->student->id] = $result;
            }

            // Zapisz wszystkie wyniki do pierwszego arkusza
            $row1 = $sheet1->getHighestRow() + 1;
            $sheet1->setCellValue('A' . $row1, $result->student->name);
            $sheet1->setCellValue('B' . $row1, $result->student->surname);
            $sheet1->setCellValue('C' . $row1, $result->student->album_number);
            $sheet1->setCellValue('D' . $row1, $result->earned_score . ' / ' . $result->max_score);
            $sheet1->setCellValue('E' . $row1, $result->result);
            $sheet1->setCellValue('F' . $row1, $result->percent_score . '%');
            $sheet1->setCellValue('G' . $row1, $result->start_time);
            $sheet1->setCellValue('H' . $row1, $result->end_time);
        }

        // Wstawianie najlepszych wyników do drugiego arkusza
        $row2 = 2; // Zaczynamy od drugiego wiersza, bo pierwszy to nagłówki
        foreach ($bestResults as $bestResult) {
            $sheet2->setCellValue('A' . $row2, $bestResult->student->name);
            $sheet2->setCellValue('B' . $row2, $bestResult->student->surname);
            $sheet2->setCellValue('C' . $row2, $bestResult->student->album_number);
            $sheet2->setCellValue('D' . $row2, $bestResult->earned_score . ' / ' . $bestResult->max_score);
            $sheet2->setCellValue('E' . $row2, $bestResult->result);
            $sheet2->setCellValue('F' . $row2, $bestResult->percent_score . '%');
            $sheet2->setCellValue('G' . $row2, $bestResult->start_time);
            $sheet2->setCellValue('H' . $row2, $bestResult->end_time);
            $row2++;
        }

        // Zapisujemy plik Excel w odpowiedzi na żądanie
        $writer = new Xlsx($spreadsheet);
        $filename = "Wyniki_Testu_$test_id.xlsx";

        return response()->stream(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => "attachment; filename=$filename",
                'Cache-Control' => 'max-age=0'
            ]
        );
    }

}
