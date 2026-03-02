<x-layout title="Saffron & Sage — Reserve Your Table">
    {{-- Hero --}}
    <section class="relative py-24 md:py-32 overflow-hidden">
        <div class="absolute inset-0">
            <img src="/images/hero.jpg" alt="" class="w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-gradient-to-t from-[var(--bg)] via-[var(--bg)]/80 to-[var(--bg)]/60"></div>
        </div>
        <div class="absolute inset-0 opacity-15" style="background: radial-gradient(ellipse at 30% 50%, var(--gold), transparent 70%);"></div>
        <div class="relative max-w-6xl mx-auto px-6 text-center">
            <p class="font-sans text-xs tracking-[0.3em] uppercase text-gold/70 mb-4">Mediterranean Dining Experience</p>
            <h1 class="font-display text-5xl md:text-7xl tracking-tight mb-6" style="line-height: 1.1;">
                Saffron <span class="text-gold">&</span> Sage
            </h1>
            <div class="divider max-w-[200px] mx-auto mb-6"></div>
            <p class="font-sans text-lg text-white/50 max-w-xl mx-auto mb-10">
                Reserve your perfect table for an unforgettable evening. Fresh ingredients, bold flavors, timeless hospitality.
            </p>
            <a href="{{ route('tables.index') }}" class="btn-gold inline-block">Browse Tables</a>
        </div>
    </section>

    {{-- Tables Preview --}}
    @if($tables->count())
    <section class="max-w-6xl mx-auto px-6 py-16">
        <div class="text-center mb-12">
            <p class="font-sans text-xs tracking-[0.3em] uppercase text-gold/70 mb-2">Our Space</p>
            <h2 class="font-display text-3xl">Choose Your Setting</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach($tables->take(6) as $table)
            <a href="{{ route('tables.show', $table) }}" class="card group">
                <div class="h-48 flex items-center justify-center" style="background: linear-gradient(135deg, rgba(201,168,76,0.08), rgba(201,168,76,0.02));">
                    <div class="text-center">
                        <div class="text-4xl mb-2 opacity-40">
                            @if($table->location === 'outdoor') &#9788; @elseif($table->location === 'terrace') &#9789; @elseif($table->location === 'private') &#10022; @else &#9670; @endif
                        </div>
                        <span class="font-sans text-xs uppercase tracking-widest text-white/30">{{ $table->location }}</span>
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="font-display text-lg mb-1 group-hover:text-gold transition-colors">{{ $table->name }}</h3>
                    <div class="flex items-center justify-between font-sans text-sm text-white/40">
                        <span>Up to {{ $table->capacity }} guests</span>
                        <span class="text-gold/60">Reserve &rarr;</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- How It Works --}}
    <section class="py-16" style="background: var(--warm);">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-12">
                <p class="font-sans text-xs tracking-[0.3em] uppercase text-gold/70 mb-2">Simple &amp; Elegant</p>
                <h2 class="font-display text-3xl">How It Works</h2>
            </div>
            <div class="grid md:grid-cols-4 gap-8 text-center font-sans">
                <div>
                    <div class="text-3xl font-display text-gold/30 mb-3">01</div>
                    <h3 class="text-sm uppercase tracking-wider mb-2 text-gold">Browse Tables</h3>
                    <p class="text-sm text-white/40 leading-relaxed">Explore our indoor, outdoor, and private dining options.</p>
                </div>
                <div>
                    <div class="text-3xl font-display text-gold/30 mb-3">02</div>
                    <h3 class="text-sm uppercase tracking-wider mb-2 text-gold">Pick Your Time</h3>
                    <p class="text-sm text-white/40 leading-relaxed">Select your preferred date and available time slot.</p>
                </div>
                <div>
                    <div class="text-3xl font-display text-gold/30 mb-3">03</div>
                    <h3 class="text-sm uppercase tracking-wider mb-2 text-gold">Apply a Coupon</h3>
                    <p class="text-sm text-white/40 leading-relaxed">Have a promo code? Apply it for an instant discount.</p>
                </div>
                <div>
                    <div class="text-3xl font-display text-gold/30 mb-3">04</div>
                    <h3 class="text-sm uppercase tracking-wider mb-2 text-gold">Confirm &amp; Enjoy</h3>
                    <p class="text-sm text-white/40 leading-relaxed">Receive your booking reference and join us for dinner.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Reviews --}}
    @if($reviews->count())
    <section class="max-w-6xl mx-auto px-6 py-16">
        <div class="flex items-center justify-between mb-10">
            <div>
                <p class="font-sans text-xs tracking-[0.3em] uppercase text-gold/70 mb-2">Guest Experiences</p>
                <h2 class="font-display text-3xl">What Diners Say</h2>
            </div>
            <a href="{{ route('reviews.index') }}" class="font-sans text-sm text-gold/60 hover:text-gold transition-colors">View All &rarr;</a>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach($reviews as $review)
            <div class="card p-6">
                <div class="flex items-center gap-1 mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star text-sm">{{ $i <= $review->rating ? '&#9733;' : '&#9734;' }}</span>
                    @endfor
                </div>
                <p class="font-sans text-sm text-white/60 leading-relaxed mb-4">{{ Str::limit($review->comment, 150) }}</p>
                <div class="flex items-center justify-between font-sans text-xs text-white/30">
                    <span class="text-gold/60">{{ $review->guest_name }}</span>
                    <span>{{ $review->booking->table->name ?? '' }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- CTA --}}
    <section class="py-20 text-center" style="background: linear-gradient(180deg, transparent, rgba(201,168,76,0.05));">
        <p class="font-sans text-xs tracking-[0.3em] uppercase text-gold/70 mb-4">Ready to Dine?</p>
        <h2 class="font-display text-4xl mb-6">Reserve Your Table Tonight</h2>
        <a href="{{ route('tables.index') }}" class="btn-gold inline-block">Make a Reservation</a>
    </section>
</x-layout>
