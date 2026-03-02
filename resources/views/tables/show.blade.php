<x-layout title="{{ $table->name }} — TableBook">
    <section class="max-w-4xl mx-auto px-6 py-16">
        <a href="{{ route('tables.index') }}" class="font-sans text-sm text-gold/60 hover:text-gold transition-colors mb-8 inline-block">&larr; All Tables</a>

        <div class="card p-8 mb-8">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="font-display text-3xl mb-1">{{ $table->name }}</h1>
                    <p class="font-sans text-sm text-white/40">{{ ucfirst($table->location) }} &middot; Up to {{ $table->capacity }} guests</p>
                </div>
                <span class="font-sans text-xs px-3 py-1 rounded bg-gold/10 text-gold border border-gold/20">{{ $table->restaurant->cuisine_type ?? 'Fine Dining' }}</span>
            </div>
            @if($table->description)
            <p class="font-sans text-sm text-white/50 leading-relaxed">{{ $table->description }}</p>
            @endif
        </div>

        {{-- Date Picker --}}
        <div class="mb-6">
            <p class="font-sans text-xs tracking-[0.2em] uppercase text-gold/70 mb-3">Select Date</p>
            <div class="flex flex-wrap gap-2">
                @foreach($dates as $d)
                <a href="{{ route('tables.show', ['table' => $table, 'date' => $d]) }}"
                   class="slot-btn {{ $d === $date ? 'active' : '' }}">
                    {{ \Carbon\Carbon::parse($d)->format('D, M j') }}
                </a>
                @endforeach
            </div>
        </div>

        {{-- Time Slots --}}
        <div class="mb-8">
            <p class="font-sans text-xs tracking-[0.2em] uppercase text-gold/70 mb-3">Available Times — {{ \Carbon\Carbon::parse($date)->format('l, F j') }}</p>
            @if($slots->count())
            <div class="flex flex-wrap gap-2" x-data="{ selected: null }">
                @foreach($slots as $slot)
                <button type="button"
                        class="slot-btn"
                        :class="{ 'active': selected === {{ $slot->id }} }"
                        @click="selected = {{ $slot->id }}; document.getElementById('time_slot_id').value = {{ $slot->id }}">
                    {{ substr($slot->start_time, 0, 5) }}
                </button>
                @endforeach
            </div>
            @else
            <p class="font-sans text-sm text-white/30">No available slots for this date.</p>
            @endif
        </div>

        {{-- Booking Form --}}
        @if($slots->count())
        <div class="divider mb-8"></div>

        <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="table_id" value="{{ $table->id }}">
            <input type="hidden" name="time_slot_id" id="time_slot_id" value="">

            <p class="font-sans text-xs tracking-[0.2em] uppercase text-gold/70 mb-2">Your Details</p>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label for="guest_name">Full Name *</label>
                    <input type="text" id="guest_name" name="guest_name" required value="{{ old('guest_name') }}">
                </div>
                <div>
                    <label for="guest_email">Email *</label>
                    <input type="email" id="guest_email" name="guest_email" required value="{{ old('guest_email') }}">
                </div>
                <div>
                    <label for="guest_phone">Phone</label>
                    <input type="tel" id="guest_phone" name="guest_phone" value="{{ old('guest_phone') }}">
                </div>
                <div>
                    <label for="party_size">Party Size *</label>
                    <select id="party_size" name="party_size" required>
                        @for($i = 1; $i <= $table->capacity; $i++)
                        <option value="{{ $i }}" {{ old('party_size') == $i ? 'selected' : '' }}>{{ $i }} {{ $i === 1 ? 'guest' : 'guests' }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div>
                <label for="special_requests">Special Requests</label>
                <textarea id="special_requests" name="special_requests" rows="2">{{ old('special_requests') }}</textarea>
            </div>

            <div>
                <label for="coupon_code">Promo Code</label>
                <input type="text" id="coupon_code" name="coupon_code" placeholder="e.g. WELCOME10" value="{{ old('coupon_code') }}">
            </div>

            @if($errors->any())
            <div class="p-4 rounded border border-red-500/30 bg-red-500/10 font-sans text-sm text-red-300">
                @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            @if(session('error'))
            <div class="p-4 rounded border border-red-500/30 bg-red-500/10 font-sans text-sm text-red-300">
                {{ session('error') }}
            </div>
            @endif

            <button type="submit" class="btn-gold">Confirm Reservation</button>
        </form>
        @endif
    </section>
</x-layout>
