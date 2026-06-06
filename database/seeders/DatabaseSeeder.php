<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Cabinet;
use App\Models\DoctorProfile;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\PatientProfile;
use App\Models\Supplier;
use App\Models\SupplyCategory;
use App\Models\SupplyItem;
use App\Models\Treatment;
use App\Models\TreatmentCategory;
use App\Models\TreatmentRecord;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Super Admin ────────────────────────────────────────────────────────
        User::firstOrCreate(['email' => 'superadmin@smilecare.tn'], [
            'name'      => 'Super Administrator',
            'password'  => Hash::make('password'),
            'role'      => 'super_admin',
            'phone'     => '+216 70 000 001',
            'is_active' => true,
        ]);

        // ── Admin ──────────────────────────────────────────────────────────────
        User::firstOrCreate(['email' => 'admin@smilecare.tn'], [
            'name'      => 'Admin User',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'phone'     => '+216 70 000 002',
            'is_active' => true,
        ]);

        // ── Doctors ────────────────────────────────────────────────────────────
        $doctorsData = [
            ['name' => 'Dr. Amel Karoui',  'email' => 'amel.karoui@smilecare.tn',  'spec' => 'General Dentistry', 'license' => 'TN-DEN-001'],
            ['name' => 'Dr. Mohamed Slim',  'email' => 'mohamed.slim@smilecare.tn', 'spec' => 'Orthodontics',      'license' => 'TN-DEN-002'],
            ['name' => 'Dr. Sonia Gharbi',  'email' => 'sonia.gharbi@smilecare.tn', 'spec' => 'Oral Surgery',      'license' => 'TN-DEN-003'],
        ];

        $doctorUsers = [];
        foreach ($doctorsData as $d) {
            $user = User::firstOrCreate(['email' => $d['email']], [
                'name' => $d['name'], 'password' => Hash::make('password'),
                'role' => 'doctor', 'is_active' => true,
            ]);
            DoctorProfile::firstOrCreate(['user_id' => $user->id], [
                'specialization' => $d['spec'],
                'license_number' => $d['license'],
                'schedule_start' => '08:00:00', 'schedule_end' => '17:00:00',
                'working_days' => ['monday','tuesday','wednesday','thursday','friday'],
            ]);
            $doctorUsers[] = $user;
        }

        // ── Secretary ──────────────────────────────────────────────────────────
        $secretary = User::firstOrCreate(['email' => 'fatma@smilecare.tn'], [
            'name' => 'Fatma Ben Ali', 'password' => Hash::make('password'),
            'role' => 'secretary', 'phone' => '+216 71 100 200', 'is_active' => true,
        ]);

        // ── Patients ────────────────────────────────────────────────────────────
        $patientsData = [
            ['name' => 'Ahmed Trabelsi', 'email' => 'ahmed.trabelsi@gmail.com', 'dob' => '1985-03-15', 'gender' => 'male',   'blood' => 'A+'],
            ['name' => 'Leila Mansouri', 'email' => 'leila.mansouri@gmail.com', 'dob' => '1992-07-22', 'gender' => 'female', 'blood' => 'O+'],
            ['name' => 'Youssef Haddad', 'email' => 'youssef.haddad@gmail.com', 'dob' => '1978-11-08', 'gender' => 'male',   'blood' => 'B+'],
            ['name' => 'Nadia Bejaoui',  'email' => 'nadia.bejaoui@gmail.com',  'dob' => '2000-05-30', 'gender' => 'female', 'blood' => 'AB+'],
            ['name' => 'Karim Zouari',   'email' => 'karim.zouari@gmail.com',   'dob' => '1965-09-12', 'gender' => 'male',   'blood' => 'O-'],
        ];

        $patientUsers = [];
        foreach ($patientsData as $p) {
            $user = User::firstOrCreate(['email' => $p['email']], [
                'name' => $p['name'], 'password' => Hash::make('password'),
                'role' => 'patient', 'is_active' => true,
            ]);
            PatientProfile::firstOrCreate(['user_id' => $user->id], [
                'date_of_birth' => $p['dob'], 'gender' => $p['gender'], 'blood_type' => $p['blood'],
            ]);
            $patientUsers[] = $user;
        }

        // ── Treatment Categories & Treatments ─────────────────────────────────
        $treatmentCatData = [
            ['Preventive',   '#10B981', [['Dental Cleaning',45,50],['Fluoride Treatment',20,30],['Dental X-Ray',15,40]]],
            ['Restorative',  '#3B82F6', [['Composite Filling',45,120],['Dental Crown',60,350],['Root Canal Treatment',90,400],['Dental Implant',120,1500]]],
            ['Orthodontics', '#8B5CF6', [['Braces Consultation',30,80],['Metal Braces',60,2500],['Clear Aligners',45,3500]]],
            ['Oral Surgery', '#EF4444', [['Tooth Extraction',30,150],['Wisdom Tooth Removal',60,300]]],
            ['Cosmetic',     '#F59E0B', [['Teeth Whitening',60,200],['Dental Veneer',45,400]]],
            ['Periodontics', '#06B6D4', [['Deep Cleaning',60,180],['Gum Treatment',45,150]]],
        ];

        $allTreatments = [];
        foreach ($treatmentCatData as [$catName, $color, $treatments]) {
            $cat = TreatmentCategory::firstOrCreate(['name' => $catName], ['color' => $color]);
            foreach ($treatments as [$name, $duration, $price]) {
                $allTreatments[] = Treatment::firstOrCreate(
                    ['name' => $name, 'category_id' => $cat->id],
                    ['duration_minutes' => $duration, 'price' => $price, 'is_active' => true]
                );
            }
        }

        // ── Supplier ──────────────────────────────────────────────────────────
        $supplierUser = User::firstOrCreate(['email' => 'contact@dentasup.tn'], [
            'name' => 'DentaSup Tunisia', 'password' => Hash::make('password'), 'role' => 'supplier',
        ]);

        $supplier = Supplier::firstOrCreate(['email' => 'contact@dentasup.tn'], [
            'user_id' => $supplierUser->id, 'company_name' => 'DentaSup Tunisia',
            'contact_name' => 'Slim Meddeb', 'phone' => '+216 71 500 600',
            'city' => 'Tunis', 'is_active' => true,
        ]);

        $catConsumable  = SupplyCategory::firstOrCreate(['name' => 'Consumables']);
        $catEquipment   = SupplyCategory::firstOrCreate(['name' => 'Equipment']);
        $catAnesthetics = SupplyCategory::firstOrCreate(['name' => 'Anesthetics']);
        $catSterilize   = SupplyCategory::firstOrCreate(['name' => 'Sterilization']);

        $supplyItems = [
            ['Dental Gloves (Box)',    $catConsumable->id,  0.15, 50,  10, 'box'],
            ['Dental Masks (Box)',     $catConsumable->id,  0.08, 30,   5, 'box'],
            ['Cotton Rolls',           $catConsumable->id,  0.05, 200, 50, 'piece'],
            ['Dental Burs Set',        $catEquipment->id,  45.00,  5,  2, 'set'],
            ['Lidocaine 2% Cartridge', $catAnesthetics->id, 2.50, 100, 20, 'cartridge'],
            ['Autoclave Pouches',      $catSterilize->id,   0.30, 500,100, 'piece'],
        ];

        foreach ($supplyItems as [$name, $catId, $price, $qty, $min, $unit]) {
            SupplyItem::firstOrCreate(
                ['name' => $name, 'supplier_id' => $supplier->id],
                [
                    'category_id' => $catId, 'unit' => $unit, 'unit_price' => $price,
                    'stock_quantity' => $qty, 'min_stock_level' => $min, 'is_active' => true,
                ]
            );
        }

        // ── Cabinets (medical offices) ─────────────────────────────────────────
        $cabinetsData = [
            ['Cabinet 1 — General Dentistry', 'Checkups & fillings',  0],
            ['Cabinet 2 — Orthodontics',      'Braces & aligners',    1],
            ['Cabinet 3 — Oral Surgery',      'Extractions & surgery', 2],
        ];

        $cabinets = collect($cabinetsData)->map(fn($c) => Cabinet::firstOrCreate(
            ['name' => $c[0]],
            [
                'description'  => $c[1],
                'doctor_id'    => $doctorUsers[$c[2]]->id,
                'secretary_id' => $secretary->id,
                'is_active'    => true,
            ]
        ));

        // ── Appointments + Records + Invoices ──────────────────────────────────
        $statuses = ['completed', 'completed', 'confirmed', 'pending', 'completed'];

        foreach ($patientUsers as $idx => $patient) {
            if ($patient->appointmentsAsPatient()->count() > 0) {
                continue;
            }

            $doctor = $doctorUsers[$idx % count($doctorUsers)];
            $status = $statuses[$idx % count($statuses)];
            $date   = $status === 'completed'
                ? now()->subDays(rand(5, 45))->setHour(rand(9, 15))->setMinute(0)->setSecond(0)
                : now()->addDays(rand(1, 14))->setHour(rand(9, 15))->setMinute(0)->setSecond(0);

            $appt = Appointment::create([
                'patient_id'       => $patient->id,
                'doctor_id'        => $doctor->id,
                'secretary_id'     => $secretary->id,
                'cabinet_id'       => $cabinets->random()->id,
                'appointment_date' => $date,
                'duration_minutes' => 45,
                'status'           => $status,
                'type'             => 'consultation',
                'reason'           => 'Routine checkup',
            ]);

            if ($status === 'completed') {
                $treatment = $allTreatments[array_rand($allTreatments)];

                TreatmentRecord::create([
                    'patient_id'     => $patient->id,
                    'doctor_id'      => $doctor->id,
                    'appointment_id' => $appt->id,
                    'treatment_id'   => $treatment->id,
                    'status'         => 'completed',
                    'cost'           => $treatment->price,
                    'completed_date' => $date->toDateString(),
                ]);

                $invoiceNumber = 'INV-' . str_pad($idx + 1, 5, '0', STR_PAD_LEFT);
                if (!Invoice::where('invoice_number', $invoiceNumber)->exists()) {
                    $inv = Invoice::create([
                        'patient_id'     => $patient->id,
                        'appointment_id' => $appt->id,
                        'issued_by'      => $secretary->id,
                        'invoice_number' => $invoiceNumber,
                        'status'         => 'paid',
                        'subtotal'       => $treatment->price,
                        'discount'       => 0,
                        'tax'            => 0,
                        'total'          => $treatment->price,
                        'due_date'       => $date->copy()->addDays(30)->toDateString(),
                        'paid_at'        => $date->copy()->addDays(rand(0, 7)),
                    ]);

                    InvoiceItem::create([
                        'invoice_id'   => $inv->id,
                        'treatment_id' => $treatment->id,
                        'description'  => $treatment->name,
                        'quantity'     => 1,
                        'unit_price'   => $treatment->price,
                        'subtotal'     => $treatment->price,
                    ]);
                }
            }
        }

        $this->command->info('');
        $this->command->info('SmileCare demo data seeded! Login with password: "password"');
        $this->command->info('  Super Admin : superadmin@smilecare.tn');
        $this->command->info('  Admin       : admin@smilecare.tn');
        $this->command->info('  Doctor      : amel.karoui@smilecare.tn');
        $this->command->info('  Secretary   : fatma@smilecare.tn');
        $this->command->info('  Patient     : ahmed.trabelsi@gmail.com');
        $this->command->info('  Supplier    : contact@dentasup.tn');
    }
}
