<?php

namespace Dainsys\Timy\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class TimerDownloadResource extends JsonResource
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
            'user_id' => $this->user->id,
            'name' => optional($this->user)->name,
            'disposition' => optional($this->disposition)->name,
            'role' => ucwords(str_replace('Timy-', '', optional($this->user->timy_role)->name)),
            'started_at' => optional($this->started_at)->format('Y-M-d H:i:s'),
            'finished_at' => optional($this->finished_at)->format('Y-M-d H:i:s'),
            'total_hours' => $this->started_at->floatDiffInHours($this->finished_at),
            'payable_hours' => (bool) optional($this->disposition)->payable == true ? $this->started_at->floatDiffInHours($this->finished_at) : '0',
            'invoiceable_hours' => (bool) optional($this->disposition)->invoiceable == true ? $this->started_at->floatDiffInHours($this->finished_at) : '0',
        ];
    }
}
