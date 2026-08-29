<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Wings/Blocks
        Schema::create('wings', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Wing A, Wing B
            $table->integer('total_floors')->default(5);
            $table->timestamps();
        });

        // Flats/Units
        Schema::create('flats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wing_id')->constrained('wings')->onDelete('cascade');
            $table->string('flat_number'); // e.g., 101, 202
            $table->integer('floor_number');
            $table->string('flat_type')->default('2 BHK'); // 1 BHK, 2 BHK, 3 BHK, Penthouse
            $table->enum('occupancy_status', ['owner_occupied', 'rented', 'vacant'])->default('owner_occupied');
            $table->integer('area_sqft')->default(1200);
            $table->timestamps();
        });

        // Users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->enum('role', ['admin', 'resident', 'guard'])->default('resident');
            $table->foreignId('flat_id')->nullable()->constrained('flats')->onDelete('set null');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Maintenance Bills
        Schema::create('maintenance_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flat_id')->constrained('flats')->onDelete('cascade');
            $table->string('bill_number')->unique();
            $table->string('month_year'); // e.g., August 2026
            $table->decimal('maintenance_amount', 10, 2);
            $table->decimal('utility_amount', 10, 2)->default(0);
            $table->decimal('penalty_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->date('due_date');
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->timestamp('payment_date')->nullable();
            $table->string('payment_method')->nullable(); // UPI, Card, NetBanking
            $table->string('transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Digital Notice Board
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('category', ['general', 'event', 'urgent', 'maintenance'])->default('general');
            $table->enum('target_audience', ['all', 'owners', 'tenants'])->default('all');
            $table->boolean('is_pinned')->default(false);
            $table->foreignId('posted_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Complaints & Helpdesk
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('flat_id')->nullable()->constrained('flats')->onDelete('set null');
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['plumbing', 'electrical', 'security', 'cleanliness', 'noise', 'parking', 'general'])->default('general');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'closed'])->default('pending');
            $table->string('assigned_to')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
        });

        // Visitor Management / Gatekeeper
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flat_id')->constrained('flats')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('visitor_name');
            $table->string('visitor_phone');
            $table->string('purpose'); // Guest, Delivery, Cab, Service
            $table->string('vehicle_number')->nullable();
            $table->string('entry_code', 6);
            $table->timestamp('expected_at')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->enum('status', ['approved', 'checked_in', 'checked_out', 'denied'])->default('approved');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Facility & Amenity Management
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->integer('max_capacity')->default(20);
            $table->decimal('fee_per_slot', 10, 2)->default(0);
            $table->enum('status', ['available', 'under_maintenance'])->default('available');
            $table->string('icon')->default('star');
            $table->timestamps();
        });

        // Amenity Bookings
        Schema::create('amenity_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('amenity_id')->constrained('amenities')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('flat_id')->constrained('flats')->onDelete('cascade');
            $table->date('booking_date');
            $table->string('start_time');
            $table->string('end_time');
            $table->decimal('total_fee', 10, 2)->default(0);
            $table->enum('status', ['confirmed', 'cancelled', 'completed'])->default('confirmed');
            $table->text('purpose')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenity_bookings');
        Schema::dropIfExists('amenities');
        Schema::dropIfExists('visitors');
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('notices');
        Schema::dropIfExists('maintenance_bills');
        Schema::dropIfExists('users');
        Schema::dropIfExists('flats');
        Schema::dropIfExists('wings');
    }
};
