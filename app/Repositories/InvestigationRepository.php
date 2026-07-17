<?php

namespace App\Repositories;

use App\Models\Investigation;
use App\Repositories\Contracts\InvestigationRepositoryInterface;
use Illuminate\Support\Collection;

class InvestigationRepository extends BaseRepository implements InvestigationRepositoryInterface
{
    /**
     * InvestigationRepository constructor.
     *
     * @param Investigation $model
     */
    public function __construct(Investigation $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all investigations assigned to a specific investigator.
     *
     * @param int $investigatorId
     * @return Collection
     */
    public function getByInvestigator(int $investigatorId): Collection
    {
        return $this->model->newQuery()
            ->with(['laporan.kategori'])
            ->where('investigator_id', $investigatorId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find investigation with all loaded relationships.
     *
     * @param int $id
     * @return Investigation|null
     */
    public function findWithDetails(int $id): ?Investigation
    {
        return $this->model->newQuery()
            ->with([
                'laporan.kategori', 
                'laporan.buktis',
                'timelines', 
                'documents.uploader', 
                'investigator'
            ])
            ->find($id);
    }
}
