<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageDepartureModel extends Model
{
    protected $table         = 'package_departures';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $returnType    = 'array';
    protected $allowedFields = ['package_id', 'depart_date', 'return_date', 'spots', 'note', 'sort_order'];

    /** All departures for a package (admin view), soonest first. */
    public function forPackage(int $packageId): array
    {
        return $this->where('package_id', $packageId)
            ->orderBy('depart_date', 'ASC')
            ->findAll();
    }

    /** Upcoming departures only (today onward) — for the public detail page. */
    public function upcoming(int $packageId): array
    {
        return $this->where('package_id', $packageId)
            ->where('depart_date >=', date('Y-m-d'))
            ->orderBy('depart_date', 'ASC')
            ->findAll();
    }
}
