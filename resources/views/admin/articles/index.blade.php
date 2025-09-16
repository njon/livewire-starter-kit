@extends('admin.app')

@section('toolbar')
    @include('admin.partials.buttons', ['title' => __('Articles'), 'asset' => __('Articles'), 'buttons' => [
        ['new' => true, 'link' => route('admin.articles.create')]
    ]])
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="card-header bg-transparent border-bottom py-3">
            <h3 class="h5 mb-0">{{ __('Articles') }}</h3>
        </div>
        <div class="">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Slug') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Published At') }}</th>
                        <th>{{ __('Created At') }}</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                    <tr>
                        <td>
                            <h6 class="mb-0">
                                <a href="{{ route('admin.articles.edit', $article) }}" class="text-decoration-none">
                                    {{ $article->getTitle() }}
                                </a>
                            </h6>
                        </td>
                        <td>
                            <code class="text-muted">{{ $article->slug }}</code>
                        </td>
                        <td>
                            @php
                                $badgeClass = match($article->status) {
                                    'published' => 'bg-success',
                                    'draft' => 'bg-warning',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst($article->status) }}
                            </span>
                        </td>
                        <td>
                            @if($article->published_at)
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    {{ $article->published_at->format('M j, Y H:i') }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted">{{ $article->created_at->format('M j, Y H:i') }}</span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-gear"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.articles.show', $article) }}">
                                            <i class="bi bi-eye me-2"></i> View
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.articles.edit', $article) }}">
                                            <i class="bi bi-pencil me-2"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure?')">
                                                <i class="bi bi-trash me-2"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-journal-text me-2"></i> {{ __('No articles found') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($articles->hasPages())
        <div class="card-footer bg-transparent border-top">
            {{ $articles->links() }}
        </div>
        @endif
    </div>
</div>
@endsection