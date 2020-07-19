<?php

namespace Dainsys\Timy\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => optional($this->user)->name,
            'path' => $this->path,
            'disposition_id' => optional($this->disposition)->id,
            'disposition' => optional($this->disposition)->name,
            'started_at' => optional($this->started_at)->format('Y-M-d H:i:s'),
            'finished_at' => optional($this->finished_at)->format('Y-M-d H:i:s'),
            'is_payable' => optional($this->disposition)->payable,
            'is_invoiceable' => optional($this->disposition)->invoiceable,
            'total_hours' => !$this->finished_at ? 0 : $this->started_at->floatDiffInHours($this->finished_at),
            'payable_hours' => (bool) optional($this->disposition)->payable == true && $this->finished_at
                ? $this->started_at->floatDiffInHours($this->finished_at) : 0,
            'invoiceable_hours' => (bool) optional($this->disposition)->invoiceable == true && $this->finished_at
                ? $this->started_at->floatDiffInHours($this->finished_at) : 0,
        ];
    }
}
