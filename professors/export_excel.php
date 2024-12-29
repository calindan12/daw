<?php
require_once '../helper/db_helper.php';
require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("ID-ul cursului nu a fost specificat.");
}

$course_id = $_GET['id'];

// Query pentru obținerea datelor
$query = "
    SELECT u.nume AS student_name, u.prenume AS student_firstname, e.grade, e.grade_date 
    FROM enrollments e
    INNER JOIN users u ON e.user_id = u.id
    WHERE e.course_id = ?
";
$students = db_select($query, [$course_id]);

// Creează un fișier Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Studenți');

// Adaugă antetele tabelului
$sheet->setCellValue('A1', 'Nume');
$sheet->setCellValue('B1', 'Prenume');
$sheet->setCellValue('C1', 'Notă');
$sheet->setCellValue('D1', 'Data Notării');

// Populează tabelul cu date
$row = 2;
foreach ($students as $student) {
    $sheet->setCellValue('A' . $row, $student['student_name']);
    $sheet->setCellValue('B' . $row, $student['student_firstname']);
    $sheet->setCellValue('C' . $row, $student['grade'] ?? 'Lipsă');
    $sheet->setCellValue('D' . $row, $student['grade_date'] ?? 'Lipsă');
    $row++;
}

// Setează header-ele pentru descărcare
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="studenti_curs.xlsx"');
header('Cache-Control: max-age=0');

// Generează și descarcă fișierul Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;

?>
