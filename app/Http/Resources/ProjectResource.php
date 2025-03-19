<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'project_name' => $this->project_name,
            'coverage_segment' => $this->coverage_segment,
            'description' => $this->description,
            'project_status' => $this->project_status,
            'start_date' => $this->start_date ?? '',
            'end_date' => $this->end_date ?? '',
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
