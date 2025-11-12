<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Collection;

class ClientService
{
    public function list() : Collection
    {
        return Client::all(["name"]);
    }

    public function getById(int $id) : ?Client
    {
        return Client::with('transactions')->find($id);
    }
}