<?php

namespace Dainsys\Timy\Controllers;

use Dainsys\Timy\Disposition;
use Illuminate\Validation\Rule;

class DispositionController extends BaseController
{
    public function index()
    {
        return response()->json([
            'data' => Disposition::orderBy('name')->get(),
            'status' => 200
        ]);
    }

    public function show(Disposition $disposition)
    {
        return response()->json(['data' => $disposition]);
    }

    protected function store()
    {
        $this->validate(request(), [
            'name' => 'required|unique:Dainsys\Timy\Disposition'
        ]);

        $disposition = Disposition::create(request()->all());

        return response()->json(['data' => $disposition]);
    }

    public function update(Disposition $disposition)
    {
        $this->validate(request(), [
            'name' => [
                'required',
                Rule::unique(Disposition::class)->ignore($disposition->id)
            ]
        ]);

        $disposition->update(request()->all());

        return response()->json(['data' => $disposition]);
    }
}
