<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table         = 'tickets';
    protected $primaryKey    = 'id';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $returnType    = 'array';
    protected $allowedFields = [
        'ticket_ref', 'token', 'package_id', 'departure_id',
        'adventure_name', 'event_date', 'event_location', 'ticket_type',
        'pickup_point', 'pickup_time', 'includes', 'image',
        'guest_name', 'guest_email', 'guest_phone', 'quantity',
        'amount', 'currency', 'payment_status', 'payment_provider', 'payment_ref', 'paid_at',
    ];

    public const STATUS = [
        'pending'   => 'Pending',
        'paid'      => 'Paid',
        'failed'    => 'Failed',
        'cancelled' => 'Cancelled',
    ];

    public function findByToken(string $token): ?array
    {
        return $this->where('token', $token)->first();
    }

    public function findByRef(string $ref): ?array
    {
        return $this->where('ticket_ref', $ref)->first();
    }

    /** Unguessable token for the public ticket URL. */
    public function makeToken(): string
    {
        do {
            $t = bin2hex(random_bytes(16));
        } while ($this->where('token', $t)->countAllResults() > 0);

        return $t;
    }

    /**
     * Human ticket reference like MG-2026-001245, sequential-looking (from the
     * row id) so tickets read as issued in order.
     */
    public function refForId(int $id): string
    {
        return 'MG-' . date('Y') . '-' . str_pad((string) $id, 6, '0', STR_PAD_LEFT);
    }

    /** Decode the includes JSON into [['icon'=>..,'label'=>..], ...]. */
    public static function decodeIncludes(?string $json): array
    {
        if ($json === null || $json === '') {
            return [];
        }
        $data = json_decode($json, true);

        return is_array($data) ? $data : [];
    }
}
