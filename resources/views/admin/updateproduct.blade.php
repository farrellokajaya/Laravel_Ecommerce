@extends('admin.maindesign')

@section('title', 'Edit Product')
@section('page_title', 'Edit Product')

@section('content')
    <div class="page-intro">
        <div class="page-intro-copy">
            <h2>Edit {{ $product->product_title }}</h2>
            <p>Update product information, inventory, pricing, category, or imagery.</p>
        </div>
        <a href="{{ route('admin.viewproduct') }}" class="admin-button admin-button-secondary">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
            Back to products
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

    <form action="{{ route('admin.postupdateproduct', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-layout">
            <div class="form-stack">
                <section class="form-card">
                    <div class="form-card-header">
                        <h3>Product information</h3>
                        <p>Keep the title and description accurate for customers.</p>
                    </div>

                    <div class="form-grid">
                        <div class="form-group full">
                            <label class="form-label" for="product_title">Product title <span>*</span></label>
                            <input
                                class="admin-input"
                                id="product_title"
                                type="text"
                                name="product_title"
                                value="{{ old('product_title', $product->product_title) }}"
                                maxlength="255"
                                required
                            >
                            @error('product_title')<span class="field-error">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group full">
                            <label class="form-label" for="product_description">Description <span>*</span></label>
                            <textarea
                                class="admin-textarea"
                                id="product_description"
                                name="product_description"
                                required
                            >{{ old('product_description', $product->product_description) }}</textarea>
                            @error('product_description')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </section>

                <section class="form-card">
                    <div class="form-card-header">
                        <h3>Pricing and inventory</h3>
                        <p>Adjust the price, available stock, and category assignment.</p>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label" for="product_prices">Price (USD) <span>*</span></label>
                            <input
                                class="admin-input"
                                id="product_prices"
                                type="number"
                                name="product_prices"
                                value="{{ old('product_prices', $product->product_prices) }}"
                                min="0"
                                step="1"
                                required
                            >
                            @error('product_prices')<span class="field-error">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="product_quantity">Stock quantity <span>*</span></label>
                            <input
                                class="admin-input"
                                id="product_quantity"
                                type="number"
                                name="product_quantity"
                                value="{{ old('product_quantity', $product->product_quantity) }}"
                                min="0"
                                step="1"
                                required
                            >
                            @error('product_quantity')<span class="field-error">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group full">
                            <label class="form-label" for="product_category">Category <span>*</span></label>
                            <select class="admin-select" id="product_category" name="product_category" required>
                                @foreach($categories as $category)
                                    <option
                                        value="{{ $category->category }}"
                                        @selected(old('product_category', $product->product_category) === $category->category)
                                    >
                                        {{ $category->category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_category')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </section>
            </div>

            <div class="form-stack">
                <section class="form-card">
                    <div class="form-card-header">
                        <h3>Current image</h3>
                        <p>This image is currently shown across the storefront.</p>
                    </div>

                    @if($product->product_image)
                        <div class="current-image">
                            <img src="/products/{{ $product->product_image }}" alt="{{ $product->product_title }}">
                            <div class="current-image-caption">
                                <span>Current product image</span>
                                <span>{{ $product->product_image }}</span>
                            </div>
                        </div>
                    @else
                        <div class="empty-state" style="min-height: 180px;">
                            <div class="empty-state-inner">
                                <div class="empty-state-icon">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><path d="m4 17 5-4 4 3 3-2 4 3"/></svg>
                                </div>
                                <p>No product image is currently available.</p>
                            </div>
                        </div>
                    @endif
                </section>

                <section class="form-card">
                    <div class="form-card-header">
                        <h3>Replace image</h3>
                        <p>Leave this empty to keep the current image.</p>
                    </div>

                    <label class="file-upload" for="product_image">
                        <input
                            id="product_image"
                            type="file"
                            name="product_image"
                            accept="image/jpeg,image/png,image/webp"
                            data-image-input="updatedProductPreview"
                        >
                        <span class="file-upload-copy">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 16V4M7 9l5-5 5 5"/><path d="M5 20h14"/></svg>
                            <strong>Choose a replacement</strong>
                            <span>JPG, PNG, or WebP. Maximum 2 MB.</span>
                        </span>
                        <img id="updatedProductPreview" class="image-preview" alt="Replacement product preview">
                    </label>
                    @error('product_image')<span class="field-error">{{ $message }}</span>@enderror
                </section>

                <section class="form-card">
                    <div class="form-actions" style="margin-top: 0;">
                        <a href="{{ route('admin.viewproduct') }}" class="admin-button admin-button-secondary">Cancel</a>
                        <button type="submit" class="admin-button admin-button-primary">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12 9 16 19 6"/></svg>
                            Save changes
                        </button>
                    </div>
                </section>
            </div>
        </div>
    </form>
@endsection
