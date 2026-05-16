<x-app-layout>
    <div class="fade-up" style="margin-bottom:40px;">
        <a href="/superadmin/dashboard" style="font-size:13px;color:var(--muted);text-decoration:none;">← Back</a>
        <h1 class="page-title" style="margin-top:12px;">Upload <em>Raw Exam</em></h1>
        <p style="font-size:14px;color:var(--muted);margin-top:8px;">AI will convert questions into reusable templates automatically.</p>
    </div>

    <div class="card upload-card fade-up-1">
        <form method="POST" action="/superadmin/upload" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Exam Type</label>
                <div class="type-grid">
                    @foreach(['dost', 'csc', 'general'] as $type)
                    <label class="type-card">
                        <input type="radio" name="exam_type" value="{{ $type }}" {{ $type === 'dost' ? 'checked' : '' }}>
                        <div class="type-inner">
                            <span class="type-name">{{ strtoupper($type) }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Raw Exam File</label>
                <div class="file-drop" onclick="document.getElementById('file').click()">
                    <p class="file-drop-icon">↑</p>
                    <p class="file-drop-title">Click to upload</p>
                    <p class="file-drop-sub">PDF or TXT — AI will extract templates</p>
                    <input type="file" id="file" name="file" accept=".pdf,.txt" style="display:none"
                        onchange="document.querySelector('.file-drop-title').textContent = this.files[0].name">
                </div>
            </div>

            <button type="submit" class="btn-primary">Process with AI</button>
        </form>
    </div>
</x-app-layout>