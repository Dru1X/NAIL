<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntryRequest;
use App\Models\Competition;
use App\Models\Entry;

class EntryController extends Controller
{
    public function index() {}

    public function create(Competition $competition) {}

    public function store(EntryRequest $request, Competition $competition) {}

    public function show(Competition $competition, Entry $entry) {}

    public function edit(Competition $competition, Entry $entry) {}

    public function update(EntryRequest $request, Competition $competition, Entry $entry) {}

    public function destroy(Competition $competition, Entry $entry) {}
}
