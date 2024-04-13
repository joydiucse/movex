<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AgentSalesReport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithMultipleSheets, WithCustomValueBinder
{
    protected $date;
    protected $data;
    protected $details;
    protected $agent_info;



    public function __construct($date, $data, $agent_info, $details)
    {
        $this->date = $date;
        $this->data = $data;
        $this->agent_info = $agent_info;
        $this->details = $details;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new AgentSalesSummary($this->date, $this->data, $this->agent_info);
        $sheets[] = new AgentSalesDetails($this->date, $this->details, $this->agent_info);

        return $sheets;
    }

}
