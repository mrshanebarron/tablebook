# RESEARCH — Job #3016: Table Booking System

## Job Analysis
- **Client**: UAE, 4.9 rating, $2,500 spent, 73 hires, 120 jobs posted
- **Budget**: $450 fixed
- **Requirement**: Comprehensive table booking website with booking confirmations, verified-user coupons, public feedback with photo/video uploads, full admin control

## Domain Research
- Restaurant booking systems need: table management, time slot availability, party size matching, coupon/promo system, review/feedback with media
- Key competitors: OpenTable, Resy, Yelp Reservations — all have table browsing, real-time availability, confirmation flows
- Client wants admin control over bookings, users, and content — Filament admin panel is ideal

## Stack Decision
- Laravel 12 + Filament v3 (admin) + Livewire (frontend interactions) + Alpine.js
- MySQL for relational booking data with time slot constraints
- No external CDNs — self-hosted fonts and assets

## Nous Sign-off
- Intelligence report completed and signed off during P4 batch analysis (relay: 2026-03-02)
- Demo spec: "Restaurant booking site. Seed: 5 tables, 2 active coupons. Workflow: Browse tables -> Select time -> Apply 'WELCOME10' coupon -> Confirm booking -> View admin dashboard."
