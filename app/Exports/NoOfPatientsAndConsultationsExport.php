<?php
/*   __________________________________________________
    |  Obfuscated by YAK Pro - Php Obfuscator  2.0.16  |
    |              on 2025-07-22 06:50:08              |
    |    GitHub: https://github.com/pk-fr/yakpro-po    |
    |__________________________________________________|
*/
 namespace App\Exports; use Maatwebsite\Excel\Concerns\FromCollection; use Maatwebsite\Excel\Concerns\WithColumnFormatting; use Maatwebsite\Excel\Concerns\WithHeadings; use Maatwebsite\Excel\Concerns\WithStyles; use PhpOffice\PhpSpreadsheet\Style\NumberFormat; use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; class NoOfPatientsAndConsultationsExport implements QvQny, OKXFu, OtI5U, b05CD { protected $data; public function __construct($data) { $this->data = $data; } public function collection() { return collect($this->data); } public function headings() : array { return ['Department', 'No of Patients Encoded', 'No of Consultations Encoded']; } public function columnFormats() : array { return ['B' => '#,##0', 'C' => '#,##0']; } public function styles(Worksheet $sheet) { foreach (range('A', 'S') as $columnID) { $sheet->getColumnDimension($columnID)->setAutoSize(true); gzDd8: } Zw87T: return [1 => ['font' => ['bold' => true], 'alignment' => ['wrapText' => true, 'horizontal' => 'center']], 'A' => ['font' => ['bold' => true]]]; } }
