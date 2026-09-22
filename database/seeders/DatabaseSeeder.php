<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Core Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator Sekolah',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $panitia1 = User::firstOrCreate(
            ['email' => 'panitia@example.com'],
            [
                'name' => 'Panitia OSIS',
                'password' => Hash::make('password'),
                'role' => 'panitia',
            ]
        );

        $panitia2 = User::firstOrCreate(
            ['email' => 'panitia2@example.com'],
            [
                'name' => 'Panitia Ekstrakurikuler',
                'password' => Hash::make('password'),
                'role' => 'panitia',
            ]
        );

        $peserta1 = User::firstOrCreate(
            ['email' => 'peserta@example.com'],
            [
                'name' => 'Zentristan Vitadi',
                'password' => Hash::make('password'),
                'role' => 'peserta',
            ]
        );

        $peserta2 = User::firstOrCreate(
            ['email' => 'budi@example.com'],
            [
                'name' => 'Budi Pratama',
                'password' => Hash::make('password'),
                'role' => 'peserta',
            ]
        );

        $peserta3 = User::firstOrCreate(
            ['email' => 'siti@example.com'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('password'),
                'role' => 'peserta',
            ]
        );

        // 2. Categories
        $categoriesData = [
            'Seminar',
            'Workshop',
            'Lomba',
            'Pelatihan',
            'Pentas Seni',
        ];

        $categories = [];
        foreach ($categoriesData as $catName) {
            $categories[$catName] = Category::firstOrCreate(['name' => $catName]);
        }

        // 3. Events
        $eventsData = [
            [
                'category_id' => $categories['Seminar']->id,
                'organizer_id' => $panitia1->id,
                'title' => 'Seminar Nasional: Inovasi AI dalam Pendidikan',
                'description' => 'Seminar interaktif membahas pemanfaatan kecerdasan buatan dalam pembelajaran modern di sekolah menengah. Menghadirkan narasumber pakar AI nasional dan praktisi edutech.',
                'location' => 'Auditorium Utama Lantai 3',
                'start_date' => now()->addDays(7)->setTime(9, 0),
                'end_date' => now()->addDays(7)->setTime(12, 30),
                'capacity' => 100,
                'status' => 'upcoming',
            ],
            [
                'category_id' => $categories['Workshop']->id,
                'organizer_id' => $panitia1->id,
                'title' => 'Workshop Fullstack Web Development dengan Laravel & Vue',
                'description' => 'Pelatihan intensif pembuatan aplikasi web modern dari dasar hingga deployment, khusus siswa jurusan RPL dan peminat web dev.',
                'location' => 'Lab Komputer RPL 1',
                'start_date' => now()->addDays(12)->setTime(8, 0),
                'end_date' => now()->addDays(12)->setTime(15, 0),
                'capacity' => 30,
                'status' => 'upcoming',
            ],
            [
                'category_id' => $categories['Pelatihan']->id,
                'organizer_id' => $panitia2->id,
                'title' => 'Bootcamp Public Speaking & Leadership',
                'description' => 'Pelatihan kepemimpinan dan teknik berbicara di depan umum untuk membekali calon pengurus organisasi sekolah.',
                'location' => 'Aula Serbaguna',
                'start_date' => now()->subDay()->setTime(9, 0),
                'end_date' => now()->addDay()->setTime(16, 0),
                'capacity' => 50,
                'status' => 'ongoing',
            ],
            [
                'category_id' => $categories['Pentas Seni']->id,
                'organizer_id' => $panitia1->id,
                'title' => 'Pentas Seni Tahunan: Harmoni Gemilang',
                'description' => 'Pagelaran musik, tari tradisional, teater, dan pameran karya seni siswa.',
                'location' => 'Lapangan Olahraga Terbuka',
                'start_date' => now()->subDays(10)->setTime(13, 0),
                'end_date' => now()->subDays(10)->setTime(21, 0),
                'capacity' => 300,
                'status' => 'completed',
            ],
        ];

        $createdEvents = [];
        foreach ($eventsData as $data) {
            $createdEvents[] = Event::create($data);
        }

        // 4. Registrations
        // Peserta 1 registered for event 0 (upcoming seminar) -> approved
        Registration::firstOrCreate([
            'user_id' => $peserta1->id,
            'event_id' => $createdEvents[0]->id,
        ], [
            'status' => 'approved',
            'registered_at' => now()->subDays(2),
        ]);

        // Peserta 1 registered for event 1 (workshop) -> pending
        Registration::firstOrCreate([
            'user_id' => $peserta1->id,
            'event_id' => $createdEvents[1]->id,
        ], [
            'status' => 'pending',
            'registered_at' => now()->subDay(),
        ]);

        // Peserta 2 registered for event 0 -> pending
        Registration::firstOrCreate([
            'user_id' => $peserta2->id,
            'event_id' => $createdEvents[0]->id,
        ], [
            'status' => 'pending',
            'registered_at' => now()->subHours(5),
        ]);

        // Peserta 3 registered for event 0 -> approved
        Registration::firstOrCreate([
            'user_id' => $peserta3->id,
            'event_id' => $createdEvents[0]->id,
        ], [
            'status' => 'approved',
            'registered_at' => now()->subDays(3),
        ]);

        // Peserta 2 registered for event 3 (ongoing) -> approved
        Registration::firstOrCreate([
            'user_id' => $peserta2->id,
            'event_id' => $createdEvents[3]->id,
        ], [
            'status' => 'approved',
            'registered_at' => now()->subDays(4),
        ]);
    }
}
