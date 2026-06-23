@extends('admin.maindesign')

@section('title', 'Add Product')
@section('page_title', 'Add Product')

@section('content')
    <div class="page-intro">
        <div class="page-intro-copy">
            <h2>Create a new product</h2>
            <p>Add clear product details, accurate stock, and a high-quality fashion image.</p>
        </div>
        <a href="{{ route('admin.viewproduct') }}" class="admin-button admin-button-secondary">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
            View products
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

    <form action="{{ route('admin.postaddproduct') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-layout">
            <div class="form-stack">
                <section class="form-card">
                    <div class="form-card-header">
                        <h3>Product information</h3>
                        <p>Use a descriptive title and explain the most important product details.</p>
                    </div>

                    <div class="form-grid">
                        <div class="form-group full">
                            <label class="form-label" for="product_title">Product title <span>*</span></label>
                            <input
                                class="admin-input"
                                id="product_title"
                                type="text"
                                name="product_title"
                                value="{{ old('product_title') }}"
                                placeholder="e.g. Tailored Linen Blazer"
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
                                placeholder="Describe the material, fit, color, and key features..."
                                required
                            >{{ old('product_description') }}</textarea>
                            @error('product_description')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </section>

                <section class="form-card">
                    <div class="form-card-header">
                        <h3>Pricing and inventory</h3>
                        <p>Enter the selling price in USD and the quantity currently available.</p>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label" for="product_prices">Price (USD) <span>*</span></label>
                            <input
                                class="admin-input"
                                id="product_prices"
                                type="number"
                                name="product_prices"
                                value="{{ old('product_prices') }}"
                                placeholder="0"
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
                                value="{{ old('product_quantity') }}"
                                placeholder="0"
                                min="0"
                                step="1"
                                required
                            >
                            @error('product_quantity')<span class="field-error">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group full">
                            <label class="form-label" for="product_category">Category <span>*</span></label>
                            <select class="admin-select" id="product_category" name="product_category" required>
                                <option value="" disabled {{ old('product_category') ? '' : 'selected' }}>Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->category }}" @selected(old('product_category') === $category->category)>
                                        {{ $category->category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_category')<span class="field-error">{{ $message }}</span>@enderror
                            @if($categories->isEmpty())
                                <span class="field-error">Create a category before adding a product.</span>
                            @endif
                        </div>
                    </div>
                </section>
            </div>

            <div class="form-stack">
                <section class="form-card">
                    <div class="form-card-header">
                        <h3>Product image</h3>
                        <p>Use a portrait or square fashion image with a clean background.</p>
                    </div>

                    <label class="file-upload" for="product_image">
                        <input
                            id="product_image"
                            type="file"
                            name="product_image"
                            accept="image/jpeg,image/png,image/webp"
                            data-image-input="newProductPreview"
                        >
                        <span class="file-upload-copy">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 16V4M7 9l5-5 5 5"/><path d="M5 20h14"/></svg>
                            <strong>Choose product image</strong>
                            <span>JPG, PNG, or WebP. Maximum 2 MB.</span>
                        </span>
                        <img id="newProductPreview" class="image-preview" alt="Selected product preview">
                    </label>
                    @error('product_image')<span class="field-error">{{ $message }}</span>@enderror
                </section>

                <section class="form-card">
                    <div class="form-card-header">
                        <h3>Publish product</h3>
                        <p>Review the details before adding this item to the storefront.</p>
                    </div>

                    <div class="form-actions" style="margin-top: 0;">
                        <a href="{{ route('admin.viewproduct') }}" class="admin-button admin-button-secondary">Cancel</a>
                        <button type="submit" class="admin-button admin-button-primary" @disabled($categories->isEmpty())>
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                            Add product
                        </button>
                    </div>
                </section>
            </div>
        </div>
    </form>
@endsection