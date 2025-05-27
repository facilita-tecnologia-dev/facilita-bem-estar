<?php

namespace App\Exports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class FeedbacksExport implements FromCollection, WithHeadings, WithStyles, WithEvents, WithColumnWidths
{
    public function collection()
    {
        $feedbacks = Company::firstWhere('id', session('company')->id)->feedbacks()->whereYear('created_at', now()->year)->with('parentUser')->get();
        $formatted = $feedbacks->map(function($feedback){
            return [
                $feedback->parentUser->department,
                $feedback->parentUser->work_shift,
                $feedback->content,
            ];
        });

        return $formatted;
    }

    public function headings(): array
    {
        return ['Setor', 'Turno', 'Comentário'];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilização do cabeçalho
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => Color::COLOR_WHITE],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '333333'],
            ],
        ]);

        // Estilo das células abaixo do cabeçalho (linhas de dados)
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("A2:C$highestRow")->applyFromArray([
            'font' => [
                'color' => ['argb' => '333333'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFFFF'],
            ],
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 25,
            'C' => 50,
        ];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                $sheet->getStyle("A1:C$highestRow")
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // Aplica somente bordas verticais nas linhas de dados (não no cabeçalho)
                for ($row = 2; $row <= $highestRow; $row++) {
                    foreach (range('A', 'C') as $col) {
                        $styleArray = [
                            'borders' => [
                                'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                                'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                                'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                                'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                            ],
                        ];
    
                        $sheet->getStyle("{$col}{$row}")->applyFromArray($styleArray);
                    }
                }

                $sheet->getStyle("C2:C$highestRow")->getAlignment()->setWrapText(true);
            },
        ];
    }
}
