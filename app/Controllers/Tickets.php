<?php

namespace App\Controllers;

use App\Models\TicketModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Tickets extends BaseController
{
    /** Public ticket page, reached by its unguessable token. */
    public function show(string $token): string
    {
        $ticket = (new TicketModel())->findByToken($token);

        if ($ticket === null) {
            throw PageNotFoundException::forPageNotFound('That ticket could not be found.');
        }

        return view('tickets/show', ['ticket' => $ticket]);
    }
}
