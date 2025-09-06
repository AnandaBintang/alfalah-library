<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class GenerateImportTemplate extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'generate:import-template';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Generate template Excel dan CSV untuk import user';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $this->info('Generating import templates...');

    // Create directory if not exists
    $templatePath = storage_path('app/templates');
    if (!file_exists($templatePath)) {
      mkdir($templatePath, 0755, true);
    }

    // Generate template dengan sample data
    $this->generateTemplateWithSample($templatePath);


    $this->info('All templates generated successfully:');
    $this->line('Excel with sample: ' . $templatePath . '/user_import_template.xlsx');
    $this->line('CSV with sample: ' . $templatePath . '/user_import_template.csv');

    return 0;
  }

  protected function generateTemplateWithSample($templatePath)
  {
    // Create spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Template Import User');

    // Headers
    $headers = [
      'A1' => 'name',
      'B1' => 'email',
      'C1' => 'password',
      'D1' => 'nis',
      'E1' => 'nisn',
      'F1' => 'class',
      'G1' => 'gender',
      'H1' => 'address',
      'I1' => 'phone'
    ];

    // Set headers
    foreach ($headers as $cell => $value) {
      $sheet->setCellValue($cell, $value);
    }

    // Style headers
    $sheet->getStyle('A1:I1')->applyFromArray([
      'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
      'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '16a34a']
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
      ]
    ]);

    // Add sample data
    $sampleData = [
      ['Ahmad Fauzi', 'ahmad.fauzi@siswa.alfalah.sch.id', 'password123', '2024001', '1234567890', '7A', 'L', 'Jl. Melati No.1 Sidoarjo', '081234567890'],
      ['Siti Aminah', 'siti.aminah@siswa.alfalah.sch.id', 'password123', '2024002', '1234567891', '7A', 'P', 'Jl. Mawar No.2 Sidoarjo', '081234567891'],
      ['Muhammad Rizki', 'muhammad.rizki@siswa.alfalah.sch.id', 'password123', '2024003', '1234567892', '7B', 'L', 'Jl. Anggrek No.3 Sidoarjo', '081234567892'],
      ['Fatimah Zahra', 'fatimah.zahra@siswa.alfalah.sch.id', 'password123', '2024004', '1234567893', '7B', 'P', 'Jl. Kenanga No.4 Sidoarjo', '081234567893'],
      ['Ali Akbar', 'ali.akbar@siswa.alfalah.sch.id', 'password123', '2024005', '1234567894', '8A', 'L', 'Jl. Dahlia No.5 Sidoarjo', '081234567894'],
    ];

    $row = 2;
    foreach ($sampleData as $data) {
      $col = 'A';
      foreach ($data as $value) {
        $sheet->setCellValue($col . $row, $value);
        $col++;
      }
      $row++;
    }

    // Auto-size columns
    foreach (range('A', 'I') as $col) {
      $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Add instructions sheet
    $instructionSheet = $spreadsheet->createSheet();
    $instructionSheet->setTitle('Petunjuk Penggunaan');

    $instructions = [
      ['PETUNJUK PENGGUNAAN TEMPLATE IMPORT USER'],
      ['SMP AL FALAH DARUSSALAM'],
      [''],
      ['KOLOM WAJIB:'],
      ['• name: Nama lengkap siswa'],
      ['• email: Email unik untuk login'],
      ['• password: Password minimal 6 karakter'],
      ['• nis: Nomor Induk Siswa (unik)'],
      ['• class: Kelas siswa (contoh: 7A, 8B, 9C)'],
      ['• gender: L untuk Laki-laki, P untuk Perempuan'],
      [''],
      ['KOLOM OPSIONAL:'],
      ['• nisn: Nomor Induk Siswa Nasional'],
      ['• address: Alamat lengkap'],
      ['• phone: Nomor HP/WA'],
      [''],
      ['ATURAN PENTING:'],
      ['• Email harus unik dan valid'],
      ['• NIS harus unik'],
      ['• Gender hanya boleh L atau P'],
      ['• Password minimal 6 karakter'],
      ['• Hapus data contoh sebelum import'],
      [''],
      ['LANGKAH IMPORT:'],
      ['1. Isi data sesuai format'],
      ['2. Simpan sebagai Excel (.xlsx) atau CSV'],
      ['3. Upload di menu Import User & Profile'],
      ['4. Assign role "siswa" setelah import'],
      ['5. Aktivasi akun siswa secara manual'],
    ];

    $row = 1;
    foreach ($instructions as $instruction) {
      $instructionSheet->setCellValue('A' . $row, $instruction[0]);
      $row++;
    }

    // Style instruction title
    $instructionSheet->getStyle('A1:A2')->applyFromArray([
      'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '16a34a']],
      'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
    ]);

    $instructionSheet->getColumnDimension('A')->setWidth(50);

    // Save Excel
    $writer = new Xlsx($spreadsheet);
    $writer->save($templatePath . '/user_import_template.xlsx');

    // Save CSV (only data sheet)
    $spreadsheet->setActiveSheetIndex(0);
    $csvWriter = new Csv($spreadsheet);
    $csvWriter->save($templatePath . '/user_import_template.csv');
  }
}
