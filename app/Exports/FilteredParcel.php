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
use App\Models\Setting;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
class FilteredParcel implements  FromView, WithTitle, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $parcels;
    protected $total_parcels;

    public function __construct($parcels)
    {
        $this->parcels = $parcels;
    }

    public function title(): string
    {
        return 'Parcels';
    }

    public function view(): View
    {
        $parcels = $this->parcels->latest()->limit(8000)->get();
        $return_charge_type = Setting::where('title','return_charge_type')->first();
        $return_charge_dhaka = Setting::where('title','return_charge_dhaka')->first();
        $return_charge_sub_city = Setting::where('title','return_charge_sub_city')->first();
        $return_charge_outside_dhaka = Setting::where('title','return_charge_outside_dhaka')->first();
        $fragile_charge = Setting::where('title','fragile_charge')->first();
        $this->total_parcels = count($parcels);
        $data = [
            'parcels' => $parcels,
            'return_charge_type' => $return_charge_type->value,
            'return_charge_dhaka' => $return_charge_dhaka->value,
            'return_charge_sub_city' => $return_charge_sub_city->value,
            'return_charge_outside_dhaka' => $return_charge_outside_dhaka->value,
            'fragile_charge' => $fragile_charge->value
        ];
        return view('admin.exports.filtered-parcels', $data);
    }


    public function styles(Worksheet $sheet)
    {
        $total_item_row = $this->total_parcels + 2;
        $total_cash_collection_row = $total_item_row + 1;
        $total_delivery_chanrge_row = $total_cash_collection_row + 1;
        $total_merchant_payable_row = $total_delivery_chanrge_row + 1;
        return [
            1    => ['font' => ['bold' => true],
            $sheet->getStyle('A1:P1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]),
            $sheet->getStyle('A'.$total_item_row.':B'.$total_item_row)->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]),
            $sheet->getStyle('A'.$total_cash_collection_row.':B'.$total_cash_collection_row)->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]),
            $sheet->getStyle('A'.$total_delivery_chanrge_row.':B'.$total_delivery_chanrge_row)->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]),
            $sheet->getStyle('A'.$total_merchant_payable_row.':B'.$total_merchant_payable_row)->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]),

            $sheet->getStyle('A'.$total_item_row.':B'.$total_item_row)->getFont()->setBold(true),
            $sheet->getStyle('A'.$total_cash_collection_row.':B'.$total_cash_collection_row)->getFont()->setBold(true),
            $sheet->getStyle('A'.$total_delivery_chanrge_row.':B'.$total_delivery_chanrge_row)->getFont()->setBold(true),
            $sheet->getStyle('A'.$total_merchant_payable_row.':B'.$total_merchant_payable_row)->getFont()->setBold(true),
        ],
        ];
    }
}

