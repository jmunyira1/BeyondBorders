<?php

namespace App\Models;

use CodeIgniter\Model;

class EnquiryModel extends Model
{
    protected $table          = 'enquiries';
    protected $primaryKey     = 'id';
    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $returnType     = 'array';
    protected $allowedFields  = [
        'type', 'package_id', 'name', 'email', 'phone', 'subject', 'message',
        'trip_type', 'people', 'travel_dates', 'budget',
        'status', 'admin_notes', 'ip_address', 'user_agent',
    ];

    /**
     * Validation differs per form, so the controllers pass the rule set they
     * need rather than relying on a single $validationRules here.
     */
    public const RULES = [
        'contact' => [
            'name'    => 'required|max_length[150]',
            'email'   => 'required|valid_email|max_length[190]',
            'subject' => 'permit_empty|max_length[200]',
            'message' => 'required|min_length[10]',
        ],
        'custom_trip' => [
            'name'         => 'required|max_length[150]',
            'email'        => 'permit_empty|valid_email|max_length[190]',
            'phone'        => 'required|max_length[60]',
            'trip_type'    => 'permit_empty|max_length[120]',
            'people'       => 'permit_empty|is_natural_no_zero',
            'travel_dates' => 'permit_empty|max_length[120]',
            'budget'       => 'permit_empty|max_length[120]',
            'message'      => 'permit_empty',
        ],
        'booking' => [
            'name'         => 'required|max_length[150]',
            'email'        => 'required|valid_email|max_length[190]',
            'phone'        => 'required|max_length[60]',
            'people'       => 'permit_empty|is_natural_no_zero',
            'travel_dates' => 'permit_empty|max_length[120]',
            'message'      => 'permit_empty',
        ],
    ];

    public const LABELS = [
        'contact'     => 'Contact message',
        'custom_trip' => 'Custom trip enquiry',
        'booking'     => 'Booking enquiry',
    ];

    /** Enquiries for the admin inbox, newest first, with the package title. */
    public function inbox(array $filters = [])
    {
        $builder = $this->select('enquiries.*, packages.title AS package_title')
            ->join('packages', 'packages.id = enquiries.package_id', 'left')
            ->orderBy('enquiries.created_at', 'DESC');

        if (! empty($filters['type'])) {
            $builder->where('enquiries.type', $filters['type']);
        }
        if (! empty($filters['status'])) {
            $builder->where('enquiries.status', $filters['status']);
        }
        if (! empty($filters['q'])) {
            $builder->groupStart()
                ->like('enquiries.name', $filters['q'])
                ->orLike('enquiries.email', $filters['q'])
                ->orLike('enquiries.phone', $filters['q'])
                ->orLike('enquiries.message', $filters['q'])
                ->groupEnd();
        }

        return $builder;
    }

    public function countNew(): int
    {
        return $this->where('status', 'new')->countAllResults();
    }
}
