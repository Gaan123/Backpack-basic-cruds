<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $path = ($this->directory . '/' . $this->filename . '.' . $this->extension);
        return [
            'path' => $this->getUrl(),
            'filename' => $this->filename,
            'id' => $this->id,
            'caption' => $this->caption
        ];
    }
}
