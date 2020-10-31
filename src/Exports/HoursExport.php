<?php

namespace Dainsys\Timy\Exports;

use Dainsys\Timy\Resources\TimerDownloadResource;
use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Models\Timer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HoursExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    public $date_from;

    public $date_to;

    public function __construct($date_from, $date_to)
    {
        $this->date_from = $date_from;
        $this->date_to = $date_to;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return TimerDownloadResource::collection(
            Timer::with('user', 'disposition', 'user.timy_role')
                ->whereDate('started_at', '>=', $this->date_from)
                ->whereDate('finished_at', '<=', $this->date_to)
                ->get()
        );
    }

    public function headings(): array
    {
        return [
            'ID Usuario',
            'Nombre',
            'Disposicion',
            'Rol',
            'Hora Inicion',
            'Hora Final',
            'Total de Horas',
            'Horas Pagables',
            'Horas Facturables',
        ];
    }
}
