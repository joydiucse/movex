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


use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
class AgentSalesSummary implements  FromView, WithTitle, ShouldAutoSize, WithStyles
{
    use Exportable;
    protected $date;
    protected $data;
    protected $agent_info;

    public function __construct($date, $data, $agent_info)
    {
        $this->date = $date;
        $this->data = $data;
        $this->agent_info = $agent_info;

    }

    public function title(): string
    {
        return 'summary';
    }

    public function view(): View
    {
        $date = $this->date;
        $summary = $this->data;
        $agent_info = $this->agent_info;
        //$parcels = $this->parcels->latest()->limit(8000)->get();
        return view('admin.exports.agent-sales-summary', compact('summary', 'agent_info', 'date'));
    }


    public function styles(Worksheet $sheet)
    {
        return [
            1    => [
                'font' => ['bold' => true],
                 $sheet->mergeCells('A1:B1'),
                 $sheet->mergeCells('A2:B2'),
                 $sheet->mergeCells('A3:B3'),
                 $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center'),
                 $sheet->getStyle('A5:B5')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]),
                 $sheet->getStyle('A1')->getFont()->setSize(18),
                 $sheet->getStyle('A5:B5')->getFont()->setBold(true),
    
                ],
        ];
    }

    
}
?>
