<?php

namespace App\Models;

trait CompanyTrait {

    /**
     * A company setting belongs to company
     * @return \App\Models\Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class, COMPANY_ID);
    }

    /**
     * A company settings has one file
     * @return \App\Models\File
     */
    public function logo() {
        return $this->hasOne(File::class, 'storable_id', COMPANY_ID)
            ->where('category_id', '=', 'company_logo')
            ->latest();
    }

    /**
     * @return null|string
     */
    public function getCompanyLogoPublicPathAttribute(): ?string {
        if ($this->logo) {
            return $this->logo->public_path;
        }

        return null;
    }

}