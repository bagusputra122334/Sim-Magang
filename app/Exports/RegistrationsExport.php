<?php

namespace App\Exports;

use App\Enums\RegistrationStatus;
use App\Models\Registration;
use App\Repositories\RegistrationRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RegistrationsExport implements
    FromQuery,
    WithMapping,
    WithHeadings,
    ShouldAutoSize,
    WithColumnFormatting,
    WithStyles,
    WithEvents,
    WithTitle
{
    /** @var array<string, mixed> */
    protected array $filters;

    /**
     * @param  array<string, mixed>  $filters
     */
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Pendaftaran Magang';
    }

    /** @return array<int, string> */
    public function headings(): array
    {
        return [
            'No. Pendaftaran',
            'Nama Peserta',
            'Jenis & Instansi',
            'Posisi Magang',
            'Tgl Submit',
            'Status',
        ];
    }

    /**
     * @param  Registration  $reg
     * @return array<int, mixed>
     */
    public function map($reg): array
    {
        $prof = $reg->user?->profile;

        if ($prof) {
            $jenis = $prof->isSiswa() ? 'Siswa' : 'Mahasiswa';
            $instansi = trim((string) ($prof->institusi ?? ''));
            $jenisInstansi = $instansi !== '' ? "{$jenis} - {$instansi}" : $jenis;
        } else {
            $jenisInstansi = 'Profil Belum Terisi';
        }

        $tglSubmit = $reg->tanggal_submit instanceof Carbon
            ? $reg->tanggal_submit->format('d-m-Y H:i')
            : ($reg->tanggal_submit ? Carbon::parse($reg->tanggal_submit)->format('d-m-Y H:i') : '-');

        $status = $reg->status instanceof RegistrationStatus
            ? $reg->status->label()
            : (is_string($reg->status) ? (RegistrationStatus::tryFrom($reg->status)?->label() ?? $reg->status) : (string) $reg->status);

        return [
            (string) ($reg->nomor_pendaftaran ?? '-'),
            (string) ($reg->user?->name ?? '—'),
            (string) $jenisInstansi,
            (string) ($reg->position?->nama_posisi ?? '—'),
            (string) $tglSubmit,
            (string) $status,
        ];
    }

    public function query(): Builder
    {
        return app(RegistrationRepository::class)->adminSearchQuery($this->filters);
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold'  => true,
                    'size'  => 11,
                    'color' => ['rgb' => '111827'],
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E8F5E9'], // Soft green professional theme
                ],
                'alignment' => [
                    'vertical'   => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();

                // Freeze top row
                $sheet->freezePane('A2');

                // Header row height
                $sheet->getRowDimension(1)->setRowHeight(28);

                // Enable AutoFilter on data range
                $sheet->setAutoFilter($sheet->calculateWorksheetDimension());

                $highestRow = $sheet->getHighestRow();
                if ($highestRow >= 2) {
                    // Center align No. Pendaftaran, Tgl Submit, Status
                    $sheet->getStyle("A2:A{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("E2:F{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Set left alignment for Names, Instansi, and Positions
                    $sheet->getStyle("B2:D{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                    // Add thin borders across table
                    $sheet->getStyle("A1:F{$highestRow}")
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN)
                        ->getColor()
                        ->setRGB('D1D5DB');

                    // Set standard data row height
                    for ($r = 2; $r <= $highestRow; $r++) {
                        $sheet->getRowDimension($r)->setRowHeight(20);
                    }
                }
            },
        ];
    }
}
