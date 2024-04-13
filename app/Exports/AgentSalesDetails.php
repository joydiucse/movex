<?php
namespace App\Exports;

use App\Models\Parcel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use phpDocumentor\Reflection\Types\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Merchant;
class AgentSalesDetails implements  FromView, WithTitle, ShouldAutoSize, WithStyles
{
    use Exportable;
    protected $date;
    protected $details;
    protected $agent_info;

    public function __construct($date, $details, $agent_info)
    {
        $this->date = $date;
        $this->details = $details;
        $this->agent_info = $agent_info;

    }

    public function title(): string
    {
        return 'details';
    }

    public function view(): View
    {
        $date = $this->date;
        $details = $this->details;
        $agent_info = $this->agent_info;

        //dd($details);
        return view('admin.exports.agent-sales-Details', compact('details', 'agent_info', 'date'));
    }


    public function styles(Worksheet $sheet)
    {
        $details = $this->details;
        if(count($details) > 0):
            $row = 5;
            for($i=0; $i< count($details); $i++):
                if($i==0):
                    $row = 5;
                else:
                    $row += 10;
                endif;

                $style= [
                    1    => [
                        'font' => ['bold' => true],
                        $sheet->mergeCells('A1:B1'),
                        $sheet->mergeCells('A2:B2'),
                        $sheet->mergeCells('A3:B3'),
                        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center'),
                        $sheet->getStyle('A5:B5')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]),
                        $sheet->getStyle('A1')->getFont()->setSize(18),
                        $sheet->getStyle('A5:B5')->getFont()->setBold(true),
                        $sheet->getStyle('A'.$row.':B'.$row)->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]),
                    ],

                ];

            endfor;

            return $style;
        else:
            return [
                1    => [
                    'font' => ['bold' => true],
                    $sheet->mergeCells('A1:B1'),
                    $sheet->mergeCells('A2:B2'),
                    $sheet->mergeCells('A3:B3'),
                    $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center'),
                    $sheet->getStyle('A1')->getFont()->setSize(18),
                ],

            ];
        endif;

    }



}
?>
