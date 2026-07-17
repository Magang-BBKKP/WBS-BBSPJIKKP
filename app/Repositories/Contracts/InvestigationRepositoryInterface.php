<?php

namespace App\Repositories\Contracts;

use App\Repositories\RepositoryInterface;
use App\Models\Investigation;
use Illuminate\Support\Collection;

interface InvestigationRepositoryInterface extends RepositoryInterface
{
    /**
     * Get all investigations assigned to a specific investigator.
     *
     * @param int $investigatorId
     * @return Collection
     */
    public function getByInvestigator(int $investigatorId): Collection;

    /**
     * Find investigation with all loaded relationships.
     *
     * @param int $id
     * @return Investigation|null
     */
    public function findWithDetails(int $id): ?Investigation;
}
