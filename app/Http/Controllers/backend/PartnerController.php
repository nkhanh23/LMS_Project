<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerRequest;
use App\Models\Partner;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_partners = Partner::all();
        return view('backend.admin.partner.index', compact('all_partners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.admin.partner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PartnerRequest $request)
    {
        $validated_data = $request->validated();
        Partner::create($validated_data);
        return redirect()->back()->with('success', 'Nhà tài trợ đã được thêm thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $partner = Partner::findOrFail($id);
        return view('backend.admin.partner.edit', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PartnerRequest $request, string $id)
    {
        $validated_data = $request->validated();
        $partner = Partner::findOrFail($id);
        $partner->update($validated_data);
        return redirect()->back()->with('success', 'Nhà tài trợ đã được cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();
        return redirect()->back()->with('success', 'Nhà tài trợ đã được xóa thành công');
    }
}
