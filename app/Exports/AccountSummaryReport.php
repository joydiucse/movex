<?php

namespace App\Exports;

use App\Models\Parcel;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use phpDocumentor\Reflection\Types\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;


class AccountSummaryReport implements  FromView, WithTitle, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $start_date;
    protected $end_date;
    protected $summary_info;

    public function __construct($start_date, $end_date, $summary_info)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->summary_info = $summary_info;
    }

    public function title(): string
    {
        return 'summary';
    }

    public function view(): View
    {
            $start_date = $this->start_date;
            $end_date = $this->end_date;
            $summary_info = $this->summary_info;

         return view('admin.exports.account-summary', compact('start_date', 'end_date', 'summary_info'));
    }


    public function styles(Worksheet $sheet)
    {
        $total_row = count($this->summary_info['date']);
        $merge = "A" . ($total_row + 6) . ":P" . ($total_row + 6) . "";
        return [
            1    => [
                'font' => ['bold' => true],
                 $sheet->mergeCells('A1:P1'),
                 $sheet->mergeCells('A2:P2'),
                 $sheet->mergeCells('A3:P3'),
                 $sheet->getStyle('A1:P3')->getAlignment()->setHorizontal('center'),
                 $sheet->getStyle('A5:P5')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]),
                 $sheet->getStyle('A1')->getFont()->setSize(18),
                 $sheet->getStyle('A5:P5')->getFont()->setBold(true),
                 $sheet->getStyle($merge)->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]),
                ],
        ];
    }


}

