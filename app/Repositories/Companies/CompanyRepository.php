<?php

namespace App\Repositories\Companies;

use App\Models\Company;
use App\Repositories\BaseRepository;

class CompanyRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Company::class;

    /**
     * CompanyRepository constructor.
     */
    public function __construct()
    {
    }

    /**
     * Save model
     * @param Company $item
     * @return Company
     */
    public function save(Company $item): Company
    {
        $item->save();
        return $item;
    }

    /**
     * @param string $domain
     * @param bool   $isActive
     * @return Company|null
     */
    public function findByDomain(string $domain, bool $isActive = true): ?Company
    {
        return $this->query()
            ->where('domain', $domain)
            ->where('is_active', $isActive)
            ->first();
    }
}