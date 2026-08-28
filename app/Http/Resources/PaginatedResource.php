<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\AbstractPaginator;
use Override;

class PaginatedResource extends JsonResource
{
    protected $resourceClass;
    
    public function __construct(AbstractPaginator $paginator, string $resourceClass)
    {
        parent::__construct($paginator);
        $this->resourceClass = $resourceClass;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'items' => ($this->resourceClass)::collection($this->resource->items()),
            'pagination' => [
                'current_page' => $this->resource->currentPage(),
                'per_page' => $this->resource->perPage(),
                'last_page' => $this->resource->lastPage(),
                'total' => $this->resource->total(),
            ],
        ];
    }
}
