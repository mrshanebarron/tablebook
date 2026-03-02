<x-layout title="Booking Confirmed — TableBook">
    <section class="max-w-2xl mx-auto px-6 py-20 text-center">
        <div class="text-5xl mb-6 text-gold/40">&#10003;</div>
        <p class="font-sans text-xs tracking-[0.3em] uppercase text-gold/70 mb-3">Reservation {{ ucfirst($booking->status) }}</p>
        <h1 class="font-display text-4xl mb-2">Thank You, {{ $booking->guest_name }}</h1>
        <p class="font-sans text-white/40 mb-8">Your booking reference is:</p>

        <div class="card inline-block px-8 py-4 mb-10">
            <span class="font-display text-2xl text-gold tracking-wider">{{ $booking->reference }}</span>
        </div>

        <div class="card p-6 text-left mb-8">
            <div class="grid grid-cols-2 gap-4 font-sans text-sm">
                <div>
                    <span class="text-white/30 text-xs uppercase tracking-wider">Table</span>
                    <p class="text-white/80 mt-1">{{ $booking->table->name }} ({{ ucfirst($booking->table->location) }})</p>
                </div>
                <div>
                    <span class="text-white/30 text-xs uppercase tracking-wider">Date &amp; Time</span>
                    <p class="text-white/80 mt-1">{{ $booking->timeSlot->date->format('l, M j, Y') }} at {{ substr($booking->timeSlot->start_time, 0, 5) }}</p>
                </div>
                <div>
                    <span class="text-white/30 text-xs uppercase tracking-wider">Party Size</span>
                    <p class="text-white/80 mt-1">{{ $booking->party_size }} {{ $booking->party_size === 1 ? 'guest' : 'guests' }}</p>
                </div>
                <div>
                    <span class="text-white/30 text-xs uppercase tracking-wider">Status</span>
                    <p class="mt-1">
                        <span class="inline-block px-2 py-0.5 rounded text-xs font-medium
                            {{ $booking->status === 'confirmed' ? 'bg-green-500/20 text-green-300' : '' }}
                            {{ $booking->status === 'pending' ? 'bg-yellow-500/20 text-yellow-300' : '' }}
                            {{ $booking->status === 'cancelled' ? 'bg-red-500/20 text-red-300' : '' }}
                            {{ $booking->status === 'completed' ? 'bg-blue-500/20 text-blue-300' : '' }}
                        ">{{ ucfirst($booking->status) }}</span>
                    </p>
                </div>
                @if($booking->coupon)
                <div>
                    <span class="text-white/30 text-xs uppercase tracking-wider">Coupon Applied</span>
                    <p class="text-gold mt-1">{{ $booking->coupon->code }} &mdash; ${{ number_format($booking->discount_amount, 2) }} off</p>
                </div>
                @endif
                @if($booking->special_requests)
                <div class="col-span-2">
                    <span class="text-white/30 text-xs uppercase tracking-wider">Special Requests</span>
                    <p class="text-white/60 mt-1">{{ $booking->special_requests }}</p>
                </div>
                @endif
            </div>
        </div>

        <p class="font-sans text-sm text-white/30 mb-6">A confirmation will be sent to {{ $booking->guest_email }}</p>
        <a href="{{ route('home') }}" class="btn-gold inline-block">Back to Home</a>
    </section>
</x-layout>
