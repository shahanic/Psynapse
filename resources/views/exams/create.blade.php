<x-app-layout>
    <div class="fade-up" style="margin-bottom: 40px;">
        <p class="page-eyebrow">Upload</p>
        <h1 class="page-title">Add your <em>reviewer.</em></h1>
    </div>

    <div class="card upload-card fade-up-1">
        <form method="POST" action="/exams" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Exam Title</label>
                <input type="text" name="title" class="form-input" placeholder="e.g. DOST Math Reviewer 2024" required>
                @error('title') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Question Type</label>
                <div class="type-grid">
                    <label class="type-card">
                        <input type="radio" name="question_type" value="multiple_choice" checked>
                        <div class="type-inner">
                            <span class="type-icon">⊙</span>
                            <span class="type-name">Multiple Choice</span>
                        </div>
                    </label>
                    <label class="type-card">
                        <input type="radio" name="question_type" value="true_false">
                        <div class="type-inner">
                            <span class="type-icon">⊤</span>
                            <span class="type-name">True or False</span>
                        </div>
                    </label>
                    <label class="type-card">
                        <input type="radio" name="question_type" value="identification">
                        <div class="type-inner">
                            <span class="type-icon">✎</span>
                            <span class="type-name">Identification</span>
                        </div>
                    </label>
                    <label class="type-card">
                        <input type="radio" name="question_type" value="fill_blank">
                        <div class="type-inner">
                            <span class="type-icon">_</span>
                            <span class="type-name">Fill in the Blank</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Number of Questions</label>
                <div class="count-grid">
                    @foreach([5, 10, 20, 30] as $num)
                    <label class="count-card">
                        <input type="radio" name="question_count" value="{{ $num }}" {{ $num == 10 ? 'checked' : '' }}>
                        <div class="count-inner">{{ $num }}</div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Upload File</label>
                <div class="file-drop" onclick="document.getElementById('file').click()">
                    <p class="file-drop-icon">↑</p>
                    <p class="file-drop-title">Click to upload</p>
                    <p class="file-drop-sub">PDF or TXT — max 10MB</p>
                    <input type="file" id="file" name="file" accept=".pdf,.txt" style="display:none"
                        onchange="document.querySelector('.file-drop-title').textContent = this.files[0].name">
                </div>
                @error('file') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn-primary">Upload & Generate Questions</button>
        </form>
    </div>
</x-app-layout>