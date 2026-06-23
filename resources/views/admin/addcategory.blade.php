@extends('admin.maindesign')

@section('title', 'Add Category')
@section('page_title', 'Add Category')

@section('content')
    <div class="page-intro">
        <div class="page-intro-copy">
            <h2>Create a product category</h2>
            <p>Add a clear category name so customers can browse your fashion catalog more easily.</p>
        </div>
        <a href="{{ route('admin.viewcategory') }}" class="admin-button admin-button-secondary">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
            View categories
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
        <form action="{{ route('admin.postaddcategory') }}" method="POST" class="form-card">
            @csrf

            <div class="form-card-header">
                <h3>Category details</h3>
                <p>Use a short, recognizable name such as Dresses, Shoes, Bags, or Accessories.</p>
            </div>

            <div class="form-group">
                <label class="form-label" for="category">Category name <span>*</span></label>
                <input
                    class="admin-input"
                    id="category"
                    type="text"
                    name="category"
                    value="{{ old('category') }}"
                    placeholder="e.g. Women's Dresses"
                    maxlength="255"
                    required
                    autofocus
                >
                @error('category')
                    <span class="field-error">{{ $message }}</span>
                @enderror
                <span class="form-hint">Category names must be unique.</span>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.dashboard') }}" class="admin-button admin-button-secondary">Cancel</a>
                <button type="submit" class="admin-button admin-button-primary">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                    Add category
                </button>
            </div>
        </form>

        <aside class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h3>Category tips</h3>
                    <p>Keep the catalog simple and consistent.</p>
                </div>
            </div>
            <div class="admin-card-body">
                <div class="quick-actions">
                    <div class="quick-action">
                        <span class="quick-action-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M5 7h10M5 17h8"/></svg></span>
                        <span class="quick-action-copy"><strong>Keep names concise</strong><span>Two or three words are usually enough.</span></span>
                    </div>
                    <div class="quick-action">
                        <span class="quick-action-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg></span>
                        <span class="quick-action-copy"><strong>Use consistent wording</strong><span>Avoid duplicates with different spelling.</span></span>
                    </div>
                    <div class="quick-action">
                        <span class="quick-action-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm10 0h6v6h-6v-6Z"/></svg></span>
                        <span class="quick-action-copy"><strong>Think like a shopper</strong><span>Choose labels customers will recognize.</span></span>
                    </div>
                </div>
            </div>
        </aside>
    </div>
@endsection
