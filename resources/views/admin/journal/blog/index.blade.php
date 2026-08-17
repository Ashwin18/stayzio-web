@extends('admin.layout')
@section('section','CMS / Pages')
@section('page','Blog & Posts')

@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Blog & Posts</h2><p>{{ $blogs->count() }} posts published</p></div>
  <div class="page-hdr-actions">
    <button class="btn btn-danger btn-sm bulk-delete d-none" data-href="{{ route('admin.pages.blog.bulk_delete_blog') }}"><i class="ti ti-trash"></i> Delete</button>
    <a href="{{ route('admin.pages.blog.create_blog') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Add Post</a>
  </div>
</div>

<div class="card">
  <div class="tbl-wrap">
    <table>
      <thead>
        <tr>
          <th><input type="checkbox" onchange="document.querySelectorAll('.rc').forEach(c=>c.checked=this.checked)"></th>
          <th>#</th><th>Title</th><th>Category</th><th>Author</th><th>Date</th><th>Status</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($blogs as $blog)
        @php  @endphp
        <tr>
          <td><input type="checkbox" class="rc bulk-check" data-val="{{ $blog->id }}"></td>
          <td class="td-muted">{{ $blog->id }}</td>
          <td>
            <div class="td-pair">
              <div class="td-avatar"><i class="ti ti-file-text"></i></div>
              <div class="td-main">{{ Str::limit($blog->title ?? '—', 50) }}</div>
            </div>
          </td>
          <td class="td-muted">{{ $blog->categoryName ?? '—' }}</td>
                    <td class="td-muted">{{ \Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}</td>
          <td>@if($blog->status == 1)<span class="badge green">Published</span>@else<span class="badge amber">Draft</span>@endif</td>
          <td>
            <div style="display:flex;gap:6px">
              <a href="{{ route('admin.pages.blog.edit_blog', ['id' => $blog->id, 'language' => $defaultLang->code]) }}" class="btn btn-secondary btn-xs"><i class="ti ti-edit"></i></a>
              <form action="{{ route('admin.pages.blog.delete_blog', $blog->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this post?')">@csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button></form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--muted)"><i class="ti ti-file-text" style="font-size:32px;display:block;margin-bottom:10px"></i>No posts found</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>
@endsection
