<?php

namespace Dainsys\Timy\Http\Controllers;

use Dainsys\Timy\Disposition;
use Illuminate\Http\Request;

class DispositionController extends BaseController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateRequest();

        Disposition::create($request->all());

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Disposition  $disposition
     * @return \Illuminate\Http\Response
     */
    public function edit(Disposition $disposition)
    {
        return view('timy::disposition-edit', compact('disposition'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Disposition  $disposition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Disposition $disposition)
    {
        $this->validateRequest();

        $disposition->update($request->all());

        return redirect()->route('super_admin_dashboard');
    }

    public function validateRequest()
    {
        if (request('invoiceable') == null) {
            request()->merge(['invoiceable' => '0']);
        }
        if (request('payable') == null) {
            request()->merge(['payable' => '0']);
        }

        return $this->validate(request(), [
            'name' => 'required|min:3|unique:timy_dispositions,name,' . optional(request()->route('disposition'))->id,
            'payable' => 'nullable|in:1,0',
            'invoiceable' => 'nullable|in:1,0',
        ]);
    }
}
