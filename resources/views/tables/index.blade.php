<x-layout title="Our Tables — TableBook">
    <section class="max-w-6xl mx-auto px-6 py-16">
        <div class="text-center mb-12">
            <p class="font-sans text-xs tracking-[0.3em] uppercase text-gold/70 mb-2">Select Your Experience</p>
            <h1 class="font-display text-4xl">Our Tables</h1>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tables as $table)
            <a href="{{ route('tables.show', $table) }}" class="card group">
                <div class="h-52 flex items-center justify-center" style="background: linear-gradient(135deg, rgba(201,168,76,0.08), rgba(201,168,76,0.02));">
                    <div class="text-center">
                        <div class="text-5xl mb-3 opacity-30">
                            @if($table->location === 'outdoor') &#9788; @elseif($table->location === 'terrace') &#9789; @elseif($table->location === 'private') &#10022; @else &#9670; @endif
                        </div>
                        <span class="font-sans text-xs uppercase tracking-widest text-white/30">{{ $table->location }}</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-display text-xl group-hover:text-gold transition-colors">{{ $table->name }}</h3>
                        <span class="font-sans text-xs px-2 py-1 rounded border border-gold/20 text-gold/60">{{ $table->capacity }} seats</span>
                    </div>
                    @if($table->description)
                    <p class="font-sans text-sm text-white/40 mb-4">{{ $table->description }}</p>
                    @endif
                    <div class="flex items-center justify-between font-sans text-sm">
                        <span class="text-white/30">{{ $table->restaurant->name ?? '' }}</span>
                        <span class="text-gold group-hover:translate-x-1 transition-transform inline-block">Reserve &rarr;</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>
</x-layout>
