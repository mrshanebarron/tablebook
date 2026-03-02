# SPEC — TableBook (Job #3016)

## Features
1. **Restaurant Profile** — name, cuisine, hours, address, contact info
2. **Table Management** — multiple tables with capacity, location (indoor/outdoor/terrace), images
3. **Time Slot System** — date + time slots per table, availability tracking
4. **Booking Flow** — browse tables → select date/time → enter details → apply coupon → confirm
5. **Coupon System** — percentage/fixed discounts, usage limits, date validity, min party size
6. **Review System** — post-booking reviews with star ratings, comments, photo uploads, YouTube embeds
7. **Admin Dashboard** — Filament panel: manage tables, bookings, coupons, reviews, view stats

## Acceptance Criteria
- Guest can browse available tables without login
- Guest can complete a booking with name/email/phone
- Coupon 'WELCOME10' applies 10% discount at checkout
- Booking generates unique reference number
- Admin can confirm/cancel bookings
- Admin can approve/reject reviews
- Reviews display star ratings and embedded media

## Pages / Routes
- `GET /` — Homepage with restaurant info + featured tables
- `GET /tables` — Browse all tables with availability
- `GET /tables/{id}` — Table detail with calendar/time picker
- `POST /bookings` — Create booking
- `GET /bookings/{reference}` — Booking confirmation page
- `POST /bookings/{reference}/review` — Submit review
- `GET /reviews` — Public reviews page
- `GET /admin/*` — Filament admin panel

## Data Model
- **Restaurant** — id, name, slug, description, cuisine_type, address, phone, email, image, opening_time, closing_time, is_active
- **Table** — id, restaurant_id, name, capacity, location, description, image, is_active
- **TimeSlot** — id, table_id, date, start_time, end_time, is_available (unique: table+date+start)
- **Booking** — id, reference, user_id?, table_id, time_slot_id, guest_name, guest_email, guest_phone, party_size, special_requests, coupon_id?, discount_amount, status
- **Coupon** — id, code, description, type, value, min_party_size, max_uses, times_used, valid_from, valid_until, is_active
- **Review** — id, booking_id, user_id?, guest_name, rating, comment, photos (JSON), youtube_url, is_approved

## Seed Data
- 1 restaurant: "Saffron & Sage" (Mediterranean, Dubai Marina)
- 5 tables: 2 indoor (2-seat, 4-seat), 2 outdoor (4-seat, 6-seat), 1 terrace (8-seat private)
- Time slots: next 7 days, 90-minute intervals from 11:00-22:30
- 2 coupons: WELCOME10 (10% off), PARTY6 (15% off, min 6 guests)
- 3 sample bookings (1 confirmed, 1 pending, 1 completed)
- 2 approved reviews with ratings

## Packages
- filament/filament v3 (admin panel)
- livewire/livewire (frontend reactivity)
- No external CDN, no Google Fonts — self-hosted woff2
