<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Restaurant;
use App\Models\Table;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $restaurant = Restaurant::where('is_active', true)->first();
        $tables = Table::where('is_active', true)
            ->withCount(['bookings' => fn ($q) => $q->whereIn('status', ['confirmed', 'completed'])])
            ->get();
        $reviews = \App\Models\Review::where('is_approved', true)
            ->with('booking.table')
            ->latest()
            ->take(6)
            ->get();

        return view('welcome', compact('restaurant', 'tables', 'reviews'));
    }

    public function tables()
    {
        $tables = Table::where('is_active', true)
            ->with('restaurant')
            ->get();

        return view('tables.index', compact('tables'));
    }

    public function showTable(Table $table, Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $slots = $table->timeSlots()
            ->where('date', $date)
            ->where('is_available', true)
            ->orderBy('start_time')
            ->get();

        $dates = collect(range(0, 6))->map(fn ($i) => now()->addDays($i)->format('Y-m-d'));

        return view('tables.show', compact('table', 'slots', 'date', 'dates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'time_slot_id' => 'required|exists:time_slots,id',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'nullable|string|max:20',
            'party_size' => 'required|integer|min:1|max:20',
            'special_requests' => 'nullable|string|max:500',
            'coupon_code' => 'nullable|string',
        ]);

        $slot = TimeSlot::findOrFail($validated['time_slot_id']);
        if (!$slot->is_available) {
            return back()->with('error', 'This time slot is no longer available.');
        }

        $coupon = null;
        $discount = 0;
        if (!empty($validated['coupon_code'])) {
            $coupon = Coupon::where('code', strtoupper($validated['coupon_code']))->first();
            if ($coupon && $coupon->isValid($validated['party_size'])) {
                $discount = $coupon->calculateDiscount(50.00 * $validated['party_size']);
                $coupon->increment('times_used');
            }
        }

        $booking = Booking::create([
            'table_id' => $validated['table_id'],
            'time_slot_id' => $validated['time_slot_id'],
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'],
            'guest_phone' => $validated['guest_phone'] ?? null,
            'party_size' => $validated['party_size'],
            'special_requests' => $validated['special_requests'] ?? null,
            'coupon_id' => $coupon?->id,
            'discount_amount' => $discount,
            'status' => 'pending',
        ]);

        $slot->update(['is_available' => false]);

        return redirect()->route('bookings.confirmation', $booking->reference);
    }

    public function confirmation(string $reference)
    {
        $booking = Booking::where('reference', $reference)
            ->with(['table.restaurant', 'timeSlot', 'coupon'])
            ->firstOrFail();

        return view('bookings.confirmation', compact('booking'));
    }

    public function reviews()
    {
        $reviews = \App\Models\Review::where('is_approved', true)
            ->with('booking.table')
            ->latest()
            ->paginate(12);

        return view('reviews.index', compact('reviews'));
    }

    public function storeReview(Request $request, string $reference)
    {
        $booking = Booking::where('reference', $reference)
            ->where('status', 'completed')
            ->whereDoesntHave('review')
            ->firstOrFail();

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'youtube_url' => 'nullable|url|max:255',
        ]);

        $booking->review()->create([
            'guest_name' => $booking->guest_name,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'youtube_url' => $validated['youtube_url'] ?? null,
        ]);

        return back()->with('success', 'Thank you for your review!');
    }
}
