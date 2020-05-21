<?php

namespace Dainsys\Timy\Controllers;

use Dainsys\Timy\Models\Disposition;

class DispositionController extends BaseController
{
    public function index()
    {
        return response()->json([
            'data' => Disposition::orderBy('name')->get(),
            'status' => 200
        ]);
    }

    public function show(Disposition $timy_disposition)
    {
        return response()->json(['data' => $timy_disposition]);
    }

    protected function store()
    {
        $this->validate(request(), [
            'name' => 'required'
        ]);

        $disposition = Disposition::create(request()->all());

        return response()->json(['data' => $disposition]);
    }

    public function update(Disposition $timy_disposition)
    {
        $this->validate(request(), [
            'name' => 'required'
        ]);

        $timy_disposition->update(request()->all());

        return response()->json(['data' => $timy_disposition]);
    }
}
