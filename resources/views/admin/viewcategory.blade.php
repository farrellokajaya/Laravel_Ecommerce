@extends('admin.maindesign')

@section('title', 'Categories')
@section('page_title', 'Categories')

@section('page_actions')
    <a href="{{ route('admin.addcategory') }}" class="admin-button admin-button-primary">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
        Add category
    </a>
@endsection

@section('content')
    <div class="page-intro">
        <div class="page-intro-copy">
            <h2>Organize your catalog</h2>
            <p>Manage the categories customers use to discover products across your fashion store.</p>
        </div>
    </div>

    <div class="category-summary-card" style="margin-bottom: 20px; max-width: 330px;">
        <span class="category-summary-icon">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm10 0h6v6h-6v-6Z"/></svg>
        </span>
        <div>
            <strong>{{ number_format($categories->count()) }}</strong>
            <span>Total product categories</span>
        </div>
    </div>

    <div class="admin-table-card">
        <div class="table-toolbar">
            <div class="table-toolbar-copy">
                <h3>Category list</h3>
                <p>Delete is disabled by the backend whenever a category is still assigned to a product.</p>
            </div>
        </div>

        @if($categories->isEmpty())
            <div class="empty-state">
                <div class="empty-state-inner">
                    <div class="empty-state-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm10 0h6v6h-6v-6Z"/></svg>
                    </div>
                    <h3>No categories yet</h3>
                    <p>Create the first category before adding products to your catalog.</p>
                    <a href="{{ route('admin.addcategory') }}" class="admin-button admin-button-primary">Add category</a>
                </div>
            </div>
        @else
            <div class="table-scroll">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category name</th>
                            <th>Created</th>
                            <th class="actions-cell">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td class="text-muted">#{{ str_pad($category->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td><strong>{{ $category->category }}</strong></td>
                                <td class="text-muted">{{ $category->created_at?->format('M d, Y') ?? '—' }}</td>
                                <td class="actions-cell">
                                    <div class="table-actions">
                                        <a href="{{ route('admin.categoryupdate', $category->id) }}" class="admin-icon-button" title="Edit category" aria-label="Edit {{ $category->category }}">
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 20 4.2-1 10.4-10.4a2 2 0 0 0-2.8-2.8L5.4 16.2 4 20Z"/><path d="m14.5 7.1 2.8 2.8"/></svg>
                                        </a>

                                        <form
                                            action="{{ route('admin.categorydelete', $category->id) }}"
                                            method="POST"
                                            data-confirm
                                            data-confirm-title="Delete this category?"
                                            data-confirm-text="The category can only be deleted if no products are currently using it."
                                            data-confirm-button="Delete category"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-icon-button danger" title="Delete category" aria-label="Delete {{ $category->category }}">
                                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M9 7V4h6v3M7 7l1 13h8l1-13M10 11v5M14 11v5"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection