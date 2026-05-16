<x-app-layout>
    <div class="fade-up" style="margin-bottom:40px;">
        <a href="/superadmin/dashboard" style="font-size:13px;color:var(--muted);text-decoration:none;">← Back</a>
        <h1 class="page-title" style="margin-top:12px;">Question <em>Templates</em></h1>
    </div>

    @if(session('success'))
    <div class="alert-success fade-up">{{ session('success') }}</div>
    @endif

    <div class="fade-up-1">
        @foreach($templates as $template)
        <div class="card" style="padding:24px 28px;margin-bottom:12px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div style="flex:1;margin-right:20px;">
                    <p style="font-size:15px;font-weight:500;color:var(--text);margin-bottom:8px;">{{ $template->template }}</p>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <span class="badge badge-purple">{{ $template->topic->name }}</span>
                        <span class="badge badge-gray">{{ $template->difficulty }}</span>
                        <span class="badge badge-gray">{{ $template->exam_type }}</span>
                        <span class="badge badge-pill">{{ $template->questions_count }} questions</span>
                    </div>
                </div>
                <form method="POST" action="/superadmin/templates/{{ $template->id }}">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Delete this template and all its questions?')"
                        style="padding:6px 16px;background:none;border:1px solid #fc8181;color:#c53030;border-radius:100px;font-size:12px;cursor:pointer;">
                        Delete
                    </button>
                </form>
            </div>
        </div>
        @endforeach

        <div style="margin-top:20px;">{{ $templates->links() }}</div>
    </div>
</x-app-layout>