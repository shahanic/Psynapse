<x-app-layout>
    <div class="fade-up" style="margin-bottom: 40px;">
        <p class="page-eyebrow">New Exam</p>
        <h1 class="page-title">Start a <em>review session.</em></h1>
    </div>

    <div class="card upload-card fade-up-1">
        <form method="POST" action="/exams">
            @csrf

            <div class="form-group">
                <label class="form-label">Session Title</label>
                <input type="text" name="title" class="form-input" placeholder="e.g. DOST Math Practice #1" required>
                @error('title') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Topic</label>
                <select name="topic_id" class="form-input" required>
                    <option value="">Select a topic...</option>
                    @foreach($topics as $topic)
                    <option value="{{ $topic->id }}">{{ $topic->name }} ({{ strtoupper($topic->exam_type) }})</option>
                    @endforeach
                </select>
                @error('topic_id') <p class="form-error">{{ $message }}</p> @enderror
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
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Difficulty</label>
                <div class="type-grid">
                    <label class="type-card">
                        <input type="radio" name="difficulty" value="easy" checked>
                        <div class="type-inner"><span class="type-name">Easy</span></div>
                    </label>
                    <label class="type-card">
                        <input type="radio" name="difficulty" value="medium">
                        <div class="type-inner"><span class="type-name">Medium</span></div>
                    </label>
                    <label class="type-card">
                        <input type="radio" name="difficulty" value="hard">
                        <div class="type-inner"><span class="type-name">Hard</span></div>
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

            <button type="submit" class="btn-primary">Start Review</button>
        </form>
    </div>
</x-app-layout>
