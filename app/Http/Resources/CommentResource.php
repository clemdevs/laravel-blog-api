<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'user' => $this->user()->pluck('name')[0],
            'email' => $this->when($request->user()->is_Admin(), $this->user()->pluck('email')[0]), //only if the user is admin can see the user's email
            'message' => $this->message,
            'created_at' => $this->created_at,
            'updated_at' => $this->when($request->user()->is_Admin, $this->updated_at)
        ];
    }
}
