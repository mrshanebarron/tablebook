<x-layout title="Guest Reviews — TableBook">
    <section class="max-w-6xl mx-auto px-6 py-16">
        <div class="text-center mb-12">
            <p class="font-sans text-xs tracking-[0.3em] uppercase text-gold/70 mb-2">Verified Guests</p>
            <h1 class="font-display text-4xl">Reviews &amp; Feedback</h1>
        </div>

        @if($reviews->count())
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($reviews as $review)
            <div class="card p-6">
                <div class="flex items-center gap-1 mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star text-sm">{{ $i <= $review->rating ? '&#9733;' : '&#9734;' }}</span>
                    @endfor
                </div>
                <p class="font-sans text-sm text-white/60 leading-relaxed mb-4">{{ $review->comment }}</p>

                @if($review->youtube_embed_url)
                <div class="mb-4 rounded overflow-hidden" style="aspect-ratio: 16/9;">
                    <iframe src="{{ $review->youtube_embed_url }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                </div>
                @endif

                <div class="divider mb-3"></div>
                <div class="flex items-center justify-between font-sans text-xs text-white/30">
                    <span class="text-gold/60">{{ $review->guest_name }}</span>
                    <span>{{ $review->booking->table->name ?? '' }} &middot; {{ $review->created_at->format('M j, Y') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $reviews->links() }}
        </div>
        @else
        <p class="text-center font-sans text-white/30">No reviews yet. Be the first to share your experience!</p>
        @endif
    </section>
</x-layout>
