<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\InfoRequest;
use App\Models\InfoBox;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $firstInfo = InfoBox::first();
        $secondInfo = InfoBox::where('id', 2)->first();
        $thirdInfo = InfoBox::where('id', 3)->first();
        return view('backend.admin.info.index', compact('firstInfo', 'secondInfo', 'thirdInfo'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(InfoRequest $request, string $id)
    {
        InfoBox::updateOrCreate(
            ['id' => $id],
            $request->validated()
        );
        return redirect()->route('admin.info.index')->with('success', 'Cập nhật thông tin thành công');
    }
}
