@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="col-md-8">
    <label for="title" class="form-label">Tieu de</label>
    <input type="text" class="form-control" id="title" name="title"
        value="{{ old('title', $document->title ?? '') }}" required>
</div>

<div class="col-md-4">
    <label for="slug" class="form-label">Slug</label>
    <input type="text" class="form-control" id="slug" name="slug"
        value="{{ old('slug', $document->slug ?? '') }}">
</div>

<div class="col-md-4">
    <label for="doc_type" class="form-label">Loai tai lieu</label>
    <select class="form-select" id="doc_type" name="doc_type" required>
        @foreach ($docTypes as $item)
            <option value="{{ $item }}" @selected(old('doc_type', $document->doc_type ?? 'feature_how_to') === $item)>
                {{ $item }}
            </option>
        @endforeach
    </select>
</div>

<div class="col-md-4">
    <label for="status" class="form-label">Trang thai</label>
    <select class="form-select" id="status" name="status" required>
        @foreach ($statuses as $item)
            <option value="{{ $item }}" @selected(old('status', $document->status ?? 'draft') === $item)>
                {{ $item }}
            </option>
        @endforeach
    </select>
</div>

<div class="col-md-4">
    <label for="sort_order" class="form-label">Thu tu</label>
    <input type="number" min="0" class="form-control" id="sort_order" name="sort_order"
        value="{{ old('sort_order', $document->sort_order ?? 0) }}">
</div>

<div class="col-md-12">
    <label for="markdown_file" class="form-label">Upload file Markdown (.md, .txt)</label>
    <input type="file" class="form-control" id="markdown_file" name="markdown_file" accept=".md,.txt">
    @if (! empty($document?->file_name))
        <small class="text-muted">File hien tai: {{ $document->file_name }}</small>
    @endif
</div>

<div class="col-md-12">
    <label for="content_markdown" class="form-label">Noi dung Markdown</label>
    <textarea class="form-control" id="content_markdown" name="content_markdown" rows="18"
        placeholder="# Huong dan su dung&#10;&#10;Nhap noi dung markdown tai day...">{{ old('content_markdown', $document->content_markdown ?? '') }}</textarea>
</div>

<div class="col-md-12">
    <div class="d-md-flex d-grid align-items-center gap-3">
        <button type="submit" class="btn btn-primary px-4">{{ $submitLabel }}</button>
        <a href="{{ route('admin.website-kb.index') }}" class="btn btn-light px-4">Quay lai</a>
    </div>
</div>
