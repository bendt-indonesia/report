<?php

namespace Bendt\Report\Export;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;

class GeneralReport implements FromView, WithEvents
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view($this->data['view'], $this->data);
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                $event->writer
                    ->getProperties()
                    ->setCreator('PT Bendt Sistem Indonesia');
            },
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getPageSetup()->setOrientation($this->data['orientation']);
                if ($this->data['autoSize']) $event->sheet->autoSize();
            },
        ];
    }

}
