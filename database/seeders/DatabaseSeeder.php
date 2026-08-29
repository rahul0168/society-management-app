<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\AmenityBooking;
use App\Models\Complaint;
use App\Models\Flat;
use App\Models\MaintenanceBill;
use App\Models\Notice;
use App\Models\User;
use App\Models\Visitor;
use App\Models\Wing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Wings
        $wingA = Wing::create(['name' => 'Wing A - Sapphire', 'total_floors' => 6]);
        $wingB = Wing::create(['name' => 'Wing B - Emerald', 'total_floors' => 6]);

        // 2. Create Flats
        $flatA101 = Flat::create(['wing_id' => $wingA->id, 'flat_number' => 'A-101', 'floor_number' => 1, 'flat_type' => '2 BHK', 'occupancy_status' => 'owner_occupied', 'area_sqft' => 1250]);
        $flatA102 = Flat::create(['wing_id' => $wingA->id, 'flat_number' => 'A-102', 'floor_number' => 1, 'flat_type' => '3 BHK', 'occupancy_status' => 'rented', 'area_sqft' => 1600]);
        $flatA201 = Flat::create(['wing_id' => $wingA->id, 'flat_number' => 'A-201', 'floor_number' => 2, 'flat_type' => '2 BHK', 'occupancy_status' => 'owner_occupied', 'area_sqft' => 1250]);
        $flatA301 = Flat::create(['wing_id' => $wingA->id, 'flat_number' => 'A-301', 'floor_number' => 3, 'flat_type' => '3 BHK', 'occupancy_status' => 'owner_occupied', 'area_sqft' => 1650]);

        $flatB101 = Flat::create(['wing_id' => $wingB->id, 'flat_number' => 'B-101', 'floor_number' => 1, 'flat_type' => '2 BHK', 'occupancy_status' => 'owner_occupied', 'area_sqft' => 1200]);
        $flatB201 = Flat::create(['wing_id' => $wingB->id, 'flat_number' => 'B-201', 'floor_number' => 2, 'flat_type' => '3 BHK', 'occupancy_status' => 'rented', 'area_sqft' => 1550]);
        $flatB301 = Flat::create(['wing_id' => $wingB->id, 'flat_number' => 'B-301', 'floor_number' => 3, 'flat_type' => 'Penthouse', 'occupancy_status' => 'owner_occupied', 'area_sqft' => 2400]);

        // 3. Create Users
        $admin = User::create([
            'name' => 'Rajesh Sharma (Secretary)',
            'email' => 'admin@society.com',
            'phone' => '+91 98765 43210',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'flat_id' => $flatB301->id,
        ]);

        $resident1 = User::create([
            'name' => 'Amit Verma',
            'email' => 'resident@society.com',
            'phone' => '+91 98123 45678',
            'password' => Hash::make('password'),
            'role' => 'resident',
            'flat_id' => $flatA101->id,
        ]);

        $resident2 = User::create([
            'name' => 'Priya Patel',
            'email' => 'resident2@society.com',
            'phone' => '+91 97654 32109',
            'password' => Hash::make('password'),
            'role' => 'resident',
            'flat_id' => $flatB201->id,
        ]);

        $resident3 = User::create([
            'name' => 'Dr. Sunita Rao',
            'email' => 'resident3@society.com',
            'phone' => '+91 99887 76655',
            'password' => Hash::make('password'),
            'role' => 'resident',
            'flat_id' => $flatA301->id,
        ]);

        $guard = User::create([
            'name' => 'Bahadur Singh (Gate Security)',
            'email' => 'guard@society.com',
            'phone' => '+91 91122 33445',
            'password' => Hash::make('password'),
            'role' => 'guard',
            'flat_id' => null,
        ]);

        // 4. Create Maintenance Bills
        MaintenanceBill::create([
            'flat_id' => $flatA101->id,
            'bill_number' => 'INV-2026-08-A101',
            'month_year' => 'August 2026',
            'maintenance_amount' => 2500.00,
            'utility_amount' => 450.00,
            'penalty_amount' => 0.00,
            'total_amount' => 2950.00,
            'due_date' => now()->addDays(5)->format('Y-m-d'),
            'status' => 'pending',
        ]);

        MaintenanceBill::create([
            'flat_id' => $flatA101->id,
            'bill_number' => 'INV-2026-07-A101',
            'month_year' => 'July 2026',
            'maintenance_amount' => 2500.00,
            'utility_amount' => 380.00,
            'penalty_amount' => 0.00,
            'total_amount' => 2880.00,
            'due_date' => now()->subDays(25)->format('Y-m-d'),
            'status' => 'paid',
            'payment_date' => now()->subDays(27),
            'payment_method' => 'UPI',
            'transaction_id' => 'UPI-982341209384',
            'notes' => 'Paid via GPay',
        ]);

        MaintenanceBill::create([
            'flat_id' => $flatB201->id,
            'bill_number' => 'INV-2026-08-B201',
            'month_year' => 'August 2026',
            'maintenance_amount' => 3100.00,
            'utility_amount' => 520.00,
            'penalty_amount' => 0.00,
            'total_amount' => 3620.00,
            'due_date' => now()->addDays(5)->format('Y-m-d'),
            'status' => 'paid',
            'payment_date' => now()->subDays(2),
            'payment_method' => 'NetBanking',
            'transaction_id' => 'HDFC-88491209',
        ]);

        MaintenanceBill::create([
            'flat_id' => $flatA301->id,
            'bill_number' => 'INV-2026-07-A301',
            'month_year' => 'July 2026',
            'maintenance_amount' => 3300.00,
            'utility_amount' => 410.00,
            'penalty_amount' => 200.00,
            'total_amount' => 3910.00,
            'due_date' => now()->subDays(10)->format('Y-m-d'),
            'status' => 'overdue',
        ]);

        // 5. Create Notices
        Notice::create([
            'title' => 'Emergency Water Supply Maintenance Work',
            'content' => 'Please note that main water tanks will undergo cleaning this Thursday from 10:00 AM to 2:00 PM. Water supply will be temporarily suspended during these hours. Kindly store required water.',
            'category' => 'urgent',
            'target_audience' => 'all',
            'is_pinned' => true,
            'posted_by' => $admin->id,
        ]);

        Notice::create([
            'title' => 'Annual General Meeting (AGM) 2026 Announcement',
            'content' => 'The Annual General Meeting for all housing society members is scheduled for Sunday, Sept 15, 2026 at 11:00 AM in the Clubhouse Hall. Agenda includes audit report and management committee elections.',
            'category' => 'general',
            'target_audience' => 'owners',
            'is_pinned' => true,
            'posted_by' => $admin->id,
        ]);

        Notice::create([
            'title' => 'Independence Day Cultural Event & Flag Hoisting',
            'content' => 'All residents are cordially invited for Flag Hoisting at 8:30 AM in the Central Garden, followed by children cultural performances and snacks.',
            'category' => 'event',
            'target_audience' => 'all',
            'is_pinned' => false,
            'posted_by' => $admin->id,
        ]);

        // 6. Create Complaints
        Complaint::create([
            'user_id' => $resident1->id,
            'flat_id' => $flatA101->id,
            'title' => 'Low Water Pressure in Master Bathroom',
            'description' => 'Water pressure in the master bathroom shower has significantly dropped over the last 2 days.',
            'category' => 'plumbing',
            'priority' => 'high',
            'status' => 'in_progress',
            'assigned_to' => 'Ramesh (Society Plumber)',
            'resolution_notes' => 'Inspected valve on floor 1. Replacement part ordered for tomorrow morning.',
        ]);

        Complaint::create([
            'user_id' => $resident2->id,
            'flat_id' => $flatB201->id,
            'title' => 'Main Gate Sensor Light Flickering',
            'description' => 'The security sensor light on Gate 2 is flickering constantly during night hours.',
            'category' => 'electrical',
            'priority' => 'medium',
            'status' => 'pending',
        ]);

        Complaint::create([
            'user_id' => $resident3->id,
            'flat_id' => $flatA301->id,
            'title' => 'Clubhouse AC Remote Control Missing',
            'description' => 'Unable to adjust temperature during evening yoga sessions.',
            'category' => 'general',
            'priority' => 'low',
            'status' => 'resolved',
            'assigned_to' => 'Suresh (Maintenance Staff)',
            'resolution_notes' => 'Replaced remote and mounted holder on wall near entrance.',
        ]);

        // 7. Create Visitors
        Visitor::create([
            'flat_id' => $flatA101->id,
            'created_by' => $resident1->id,
            'visitor_name' => 'Rohan Sharma (Guest)',
            'visitor_phone' => '+91 99112 23344',
            'purpose' => 'Guest Visit',
            'vehicle_number' => 'MH 02 CZ 4920',
            'entry_code' => '849201',
            'expected_at' => now()->addHours(2),
            'status' => 'approved',
        ]);

        Visitor::create([
            'flat_id' => $flatA101->id,
            'created_by' => $resident1->id,
            'visitor_name' => 'Amazon Logistics (Delivery)',
            'visitor_phone' => '+91 98989 89898',
            'purpose' => 'Delivery',
            'entry_code' => '529143',
            'checked_in_at' => now()->subMinutes(15),
            'status' => 'checked_in',
        ]);

        Visitor::create([
            'flat_id' => $flatB201->id,
            'created_by' => $resident2->id,
            'visitor_name' => 'Uber Cab',
            'visitor_phone' => '+91 97777 66666',
            'purpose' => 'Cab Pickup',
            'vehicle_number' => 'KA 01 AB 1234',
            'entry_code' => '193820',
            'checked_in_at' => now()->subHours(1),
            'checked_out_at' => now()->subMinutes(45),
            'status' => 'checked_out',
        ]);

        // 8. Create Amenities
        $clubhouse = Amenity::create([
            'name' => 'Clubhouse Grand Banquet Hall',
            'description' => 'Air-conditioned hall suitable for birthday parties, family gatherings, and community events.',
            'location' => 'Clubhouse 1st Floor',
            'max_capacity' => 150,
            'fee_per_slot' => 1000.00,
            'status' => 'available',
            'icon' => 'building-library',
        ]);

        $tennis = Amenity::create([
            'name' => 'Outdoor Lawn Tennis Court',
            'description' => 'Synthetically surfaced floodlit tennis court available for morning and evening play.',
            'location' => 'Sports Complex',
            'max_capacity' => 4,
            'fee_per_slot' => 200.00,
            'status' => 'available',
            'icon' => 'trophy',
        ]);

        $pool = Amenity::create([
            'name' => 'Semi-Olympic Swimming Pool',
            'description' => 'Clean filtered pool with dedicated kids lane and lifeguard on duty.',
            'location' => 'Garden Podium Level',
            'max_capacity' => 30,
            'fee_per_slot' => 0.00,
            'status' => 'available',
            'icon' => 'water',
        ]);

        Amenity::create([
            'name' => 'Fitness Gym & Cardio Studio',
            'description' => 'Fully equipped gym with treadmills, weights, and yoga mats.',
            'location' => 'Clubhouse Ground Floor',
            'max_capacity' => 25,
            'fee_per_slot' => 0.00,
            'status' => 'available',
            'icon' => 'heart',
        ]);

        // 9. Amenity Bookings
        AmenityBooking::create([
            'amenity_id' => $clubhouse->id,
            'user_id' => $resident1->id,
            'flat_id' => $flatA101->id,
            'booking_date' => now()->addDays(7)->format('Y-m-d'),
            'start_time' => '6:00 PM',
            'end_time' => '10:00 PM',
            'total_fee' => 1000.00,
            'status' => 'confirmed',
            'purpose' => 'Son 10th Birthday Celebration',
        ]);

        AmenityBooking::create([
            'amenity_id' => $tennis->id,
            'user_id' => $resident2->id,
            'flat_id' => $flatB201->id,
            'booking_date' => now()->addDays(1)->format('Y-m-d'),
            'start_time' => '7:00 AM',
            'end_time' => '8:30 AM',
            'total_fee' => 200.00,
            'status' => 'confirmed',
            'purpose' => 'Morning Match with Neighbor',
        ]);
    }
}
