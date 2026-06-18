@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        @include('backend.section.breadcrumb', ['title' => 'Website KB', 'sub_title' => 'Quan ly knowledge base'])

        <div style="display: flex; align-items:center; justify-content:space-between">
            <h6 class="mb-0 text-uppercase">Tai lieu huong dan he thong</h6>
            <a href="{{ route('admin.website-kb.create') }}" class="btn btn-primary">Them tai lieu</a>
        </div>
        <hr />

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row mb-3">
            <div class="col-md-12">
                <form action="{{ route('admin.website-kb.index') }}" method="GET" class="row g-2">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="Tim theo tieu de, slug, noi dung..."
                            value="{{ $search }}">
                    </div>
                    <div class="col-md-3">
                        <select name="doc_type" class="form-select">
                            <option value="">Tat ca loai</option>
                            @foreach ($docTypes as $item)
                                <option value="{{ $item }}" @selected($docType === $item)>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Tat ca trang thai</option>
                            @foreach ($statuses as $item)
                                <option value="{{ $item }}" @selected($status === $item)>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-secondary w-100">Tim</button>
                        <a href="{{ route('admin.website-kb.index') }}" class="btn btn-light w-100">Xoa</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tieu de</th>
                                <th>Loai</th>
                                <th>Trang thai</th>
                                <th>Nguon</th>
                                <th>Cap nhat</th>
                                <th>Hanh dong</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($documents as $index => $item)
                                <tr>
                                    <td>{{ $documents->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $item->title }}</div>
                                        <div class="text-muted small">{{ $item->slug }}</div>
                                    </td>
                                    <td>{{ $item->doc_type }}</td>
                                    <td>
                                        <span class="badge {{ $item->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td>{{ $item->source_type }}</td>
                                    <td>{{ optional($item->updated_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.website-kb.edit', $item->id) }}" class="btn btn-primary btn-sm">
                                            Sua
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm delete-kb"
                                            data-id="{{ $item->id }}">
                                            Xoa
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Chua co tai lieu knowledge base nao.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $documents->appends(request()->query())->links() }}
                </div>
            </div>
        </div>

        <form id="delete-form" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection

@push('script')
    <script>
        $(document).on('click', '.delete-kb', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let deleteUrl = "{{ route('admin.website-kb.destroy', ':id') }}".replace(':id', id);

            Swal.fire({
                title: 'Ban co chac chan muon xoa?',
                text: 'Tai lieu se bi xoa khoi knowledge base.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Co'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#delete-form').attr('action', deleteUrl).submit();
                }
            });
        });
    </script>
@endpush
