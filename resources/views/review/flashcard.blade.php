<x-app-layout>
    <div class="fade-up" style="margin-bottom:40px;">
        <a href="/exams/{{ $exam->id }}" style="font-size:13px;color:var(--muted);text-decoration:none;">← Back</a>
        <h1 class="page-title" style="margin-top:12px;"><em>Flashcards</em></h1>
        <p class="page-eyebrow" style="margin-top:8px;">{{ $questions->count() }} cards — click to flip</p>
    </div>

    <div id="flashcard-container" class="fade-up-1">
        @foreach($questions as $i => $question)
        <div class="flashcard {{ $i === 0 ? 'active' : 'hidden' }}" data-index="{{ $i }}">
            <div class="flashcard-inner" onclick="this.classList.toggle('flipped')">
                <div class="flashcard-front card">
                    <p style="font-size:11px;font-weight:500;letter-spacing:1.5px;text-transform:uppercase;color:var(--muted);margin-bottom:16px;">Question {{ $i + 1 }} of {{ $questions->count() }}</p>
                    <p style="font-size:18px;font-weight:500;color:var(--text);text-align:center;">{{ $question->question }}</p>
                    <p style="font-size:12px;color:var(--muted);margin-top:24px;text-align:center;">Click to reveal answer</p>
                </div>
                <div class="flashcard-back card">
                    <p style="font-size:11px;font-weight:500;letter-spacing:1.5px;text-transform:uppercase;color:var(--purple-600);margin-bottom:16px;">Answer</p>
                    <p style="font-size:18px;font-weight:500;color:var(--text);text-align:center;">{{ $question->answer }}</p>
                    @if($question->explanation)
                    <p style="font-size:13px;color:var(--muted);margin-top:16px;text-align:center;">{{ $question->explanation }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div style="display:flex;gap:12px;margin-top:24px;" class="fade-up-2">
        <button onclick="prevCard()" style="padding:12px 28px;border:1px solid var(--border);border-radius:100px;font-size:14px;color:var(--muted);background:none;cursor:pointer;">← Prev</button>
        <span id="card-counter" style="display:flex;align-items:center;font-size:13px;color:var(--muted);">1 / {{ $questions->count() }}</span>
        <button onclick="nextCard()" class="btn-primary">Next →</button>
    </div>

    <script>
        let current = 0;
        const cards = document.querySelectorAll('.flashcard');
        const total = cards.length;

        function showCard(index) {
            cards.forEach(c => c.classList.add('hidden'));
            cards.forEach(c => c.querySelector('.flashcard-inner').classList.remove('flipped'));
            cards[index].classList.remove('hidden');
            document.getElementById('card-counter').textContent = (index + 1) + ' / ' + total;
        }

        function nextCard() {
            if (current < total - 1) { current++; showCard(current); }
        }

        function prevCard() {
            if (current > 0) { current--; showCard(current); }
        }
    </script>
</x-app-layout>