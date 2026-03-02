<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Restaurant;
use App\Models\Review;
use App\Models\Table;
use App\Models\TimeSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@tablebook.test',
            'password' => bcrypt('TableBook!Demo2026'),
        ]);

        $restaurant = Restaurant::create([
            'name' => 'Saffron & Sage',
            'slug' => 'saffron-sage',
            'description' => 'A modern Mediterranean restaurant in the heart of Dubai Marina. We blend traditional recipes with contemporary techniques, using the freshest local ingredients to create dishes that tell a story.',
            'cuisine_type' => 'mediterranean',
            'address' => 'Marina Walk, Dubai Marina, Dubai, UAE',
            'phone' => '+971 4 555 0123',
            'email' => 'reservations@saffronandsage.ae',
            'opening_time' => '11:00',
            'closing_time' => '23:00',
            'is_active' => true,
        ]);

        $tables = [
            ['name' => 'The Olive Branch', 'capacity' => 2, 'location' => 'indoor', 'description' => 'An intimate table for two, nestled by the window with soft ambient lighting.'],
            ['name' => 'The Fig Garden', 'capacity' => 4, 'location' => 'indoor', 'description' => 'A cozy booth with cushioned seating, perfect for family dinners.'],
            ['name' => 'Marina Breeze', 'capacity' => 4, 'location' => 'outdoor', 'description' => 'Al fresco dining overlooking the marina promenade. Best at sunset.'],
            ['name' => 'The Terrace', 'capacity' => 6, 'location' => 'outdoor', 'description' => 'Spacious open-air table with panoramic views of the marina skyline.'],
            ['name' => 'The Saffron Room', 'capacity' => 8, 'location' => 'private', 'description' => 'An exclusive private dining room with dedicated service. Ideal for celebrations and corporate events.'],
        ];

        foreach ($tables as $t) {
            $table = Table::create(array_merge($t, ['restaurant_id' => $restaurant->id]));

            for ($day = 0; $day < 7; $day++) {
                $date = Carbon::today()->addDays($day);
                $hour = 11;
                while ($hour < 22) {
                    $booked = ($day === 0 && $hour === 19 && $table->id <= 2);
                    TimeSlot::create([
                        'table_id' => $table->id,
                        'date' => $date->format('Y-m-d'),
                        'start_time' => sprintf('%02d:00', $hour),
                        'end_time' => sprintf('%02d:30', $hour + 1),
                        'is_available' => !$booked,
                    ]);
                    $hour += 2;
                }
            }
        }

        $welcome = Coupon::create([
            'code' => 'WELCOME10',
            'description' => '10% off your first reservation',
            'type' => 'percentage',
            'value' => 10,
            'min_party_size' => 1,
            'max_uses' => 100,
            'times_used' => 12,
            'valid_from' => Carbon::today()->subMonth(),
            'valid_until' => Carbon::today()->addMonths(3),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'PARTY6',
            'description' => '15% off for groups of 6 or more',
            'type' => 'percentage',
            'value' => 15,
            'min_party_size' => 6,
            'max_uses' => 50,
            'times_used' => 3,
            'valid_from' => Carbon::today()->subWeek(),
            'valid_until' => Carbon::today()->addMonths(2),
            'is_active' => true,
        ]);

        $table1 = Table::find(1);
        $table2 = Table::find(2);
        $table3 = Table::find(3);

        $slot1 = TimeSlot::where('table_id', 1)->where('date', Carbon::today())->where('start_time', '19:00')->first();
        $slot2 = TimeSlot::where('table_id', 2)->where('date', Carbon::today())->where('start_time', '19:00')->first();
        $slot3 = TimeSlot::where('table_id', 3)->where('date', Carbon::today()->subDays(2))->where('start_time', '19:00')->first();

        if ($slot1) {
            Booking::create([
                'table_id' => 1,
                'time_slot_id' => $slot1->id,
                'guest_name' => 'Sarah Al-Rashid',
                'guest_email' => 'sarah@example.com',
                'guest_phone' => '+971 50 123 4567',
                'party_size' => 2,
                'status' => 'confirmed',
                'coupon_id' => $welcome->id,
                'discount_amount' => 10.00,
            ]);
        }

        if ($slot2) {
            Booking::create([
                'table_id' => 2,
                'time_slot_id' => $slot2->id,
                'guest_name' => 'James Morrison',
                'guest_email' => 'james@example.com',
                'party_size' => 3,
                'special_requests' => 'Celebrating an anniversary. Would love a quiet corner if possible.',
                'status' => 'pending',
            ]);
        }

        if ($slot3) {
            $completedBooking = Booking::create([
                'table_id' => 3,
                'time_slot_id' => $slot3->id,
                'guest_name' => 'Aisha Khan',
                'guest_email' => 'aisha@example.com',
                'party_size' => 4,
                'status' => 'completed',
            ]);

            Review::create([
                'booking_id' => $completedBooking->id,
                'guest_name' => 'Aisha Khan',
                'rating' => 5,
                'comment' => 'Absolutely stunning evening at the Marina Breeze table. The lamb chops were perfectly cooked, and the sunset view was breathtaking. Our waiter Mohammed was attentive without being intrusive. Will definitely return!',
                'is_approved' => true,
            ]);
        }

        $pastSlot = TimeSlot::where('table_id', 5)->where('date', Carbon::today()->subDays(3))->where('start_time', '19:00')->first();
        if ($pastSlot) {
            $booking2 = Booking::create([
                'table_id' => 5,
                'time_slot_id' => $pastSlot->id,
                'guest_name' => 'David Chen',
                'guest_email' => 'david@example.com',
                'party_size' => 6,
                'status' => 'completed',
            ]);

            Review::create([
                'booking_id' => $booking2->id,
                'guest_name' => 'David Chen',
                'rating' => 4,
                'comment' => 'The Saffron Room was perfect for our corporate dinner. Great ambiance and excellent service. The mezze platter was the highlight. Only minor note — the dessert menu could use more variety.',
                'is_approved' => true,
            ]);
        }
    }
}
