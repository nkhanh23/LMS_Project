@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        @include('backend.section.breadcrumb', ['title' => 'Website KB', 'sub_title' => 'Them tai lieu'])

        <div class="row">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body p-4">
                        <div style="display: flex; align-items:center; justify-content:space-between">
                            <h5 class="mb-4">Them tai lieu knowledge base</h5>
                            <a href="{{ route('admin.website-kb.index') }}" class="btn btn-primary px-4">Danh sach</a>
                        </div>

                        <form class="row g-3" method="POST" action="{{ route('admin.website-kb.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            @php($document = null)
                            @php($submitLabel = 'Luu tai lieu')
                            @include('backend.admin.website-kb._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
