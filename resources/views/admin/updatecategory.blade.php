@extends('admin.maindesign')

@section('title', 'Edit Category')
@section('page_title', 'Edit Category')

@section('content')
    <div class="page-intro">
        <div class="page-intro-copy">
            <h2>Update category</h2>
            <p>Renaming this category also updates the category name on every product currently using it.</p>
        </div>
        <a href="{{ route('admin.viewcategory') }}" class="admin-button admin-button-secondary">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
            Back to categories
        </a>
    </div>

    @if($errors->any())
        <div class="validation-summary">
            <strong>Please review the following:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-layout">
        <form action="{{ route('admin.postupdatecategory', $category->id) }}" method="POST" class="form-card">
            @csrf

            <div class="form-card-header">
                <h3>Category details</h3>
                <p>Update the name while keeping it clear and consistent with the rest of the catalog.</p>
            </div>

            <div class="form-group">
                <label class="form-label" for="category">Category name <span>*</span></label>
                <input
                    class="admin-input"
                    id="category"
                    type="text"
                    name="category"
                    value="{{ old('category', $category->category) }}"
                    maxlength="255"
                    required
                    autofocus
                >
                @error('category')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.viewcategory') }}" class="admin-button admin-button-secondary">Cancel</a>
                <button type="submit" class="admin-button admin-button-primary">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12 9 16 19 6"/></svg>
                    Save changes
                </button>
            </div>
        </form>

        <aside class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h3>Category information</h3>
                    <p>Reference details for this record.</p>
                </div>
            </div>
            <div class="admin-card-body">
                <div class="quick-actions">
                    <div class="quick-action">
                        <span class="quick-action-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16v16H4zM8 9h8M8 13h6"/></svg></span>
                        <span class="quick-action-copy"><strong>Category ID</strong><span>#{{ str_pad($category->id, 4, '0', STR_PAD_LEFT) }}</span></span>
                    </div>
                    <div class="quick-action">
                        <span class="quick-action-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span>
                        <span class="quick-action-copy"><strong>Last updated</strong><span>{{ $category->updated_at?->format('M d, Y · H:i') ?? 'Not available' }}</span></span>
                    </div>
                </div>
            </div>
        </aside>
    </div>
@endsection
