<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds
     */
    public function run(): void
    {
        $authors = [
            ['last_name' => 'Shevchenko', 'first_name' => 'Taras', 'middle_name' => 'Hryhorovych'],
            ['last_name' => 'Franko', 'first_name' => 'Ivan', 'middle_name' => 'Yakovych'],
            ['last_name' => 'Lesia', 'first_name' => 'Ukrainka', 'middle_name' => null],
            ['last_name' => 'Kotlyarevsky', 'first_name' => 'Ivan', 'middle_name' => 'Petrovych'],
            ['last_name' => 'Stus', 'first_name' => 'Vasyl', 'middle_name' => 'Semenovych'],
            ['last_name' => 'Dovzhenko', 'first_name' => 'Oleksandr', 'middle_name' => 'Petrovych'],
            ['last_name' => 'Honchar', 'first_name' => 'Oles', 'middle_name' => 'Terentiyovych'],
            ['last_name' => 'Rylsky', 'first_name' => 'Maksym', 'middle_name' => 'Tadeyovych'],
            ['last_name' => 'Sosyura', 'first_name' => 'Volodymyr', 'middle_name' => 'Mykolayovych'],
            ['last_name' => 'Tychyna', 'first_name' => 'Pavlo', 'middle_name' => 'Hryhorovych'],
            ['last_name' => 'Bagryany', 'first_name' => 'Ivan', 'middle_name' => null],
            ['last_name' => 'Vynnychenko', 'first_name' => 'Volodymyr', 'middle_name' => 'Kyrylovych'],
            ['last_name' => 'Stefanyk', 'first_name' => 'Vasyl', 'middle_name' => 'Semenovych'],
            ['last_name' => 'Kotsyubynsky', 'first_name' => 'Mykhailo', 'middle_name' => 'Mykhaylovych'],
            ['last_name' => 'Malyshko', 'first_name' => 'Andriy', 'middle_name' => 'Samiylovych'],
            ['last_name' => 'Zahrebelnyi', 'first_name' => 'Pavlo', 'middle_name' => 'Arkhypovych'],
            ['last_name' => 'Pidmohylny', 'first_name' => 'Valerian', 'middle_name' => 'Petrovych'],
            ['last_name' => 'Khvylyovy', 'first_name' => 'Mykola', 'middle_name' => null],
            ['last_name' => 'Bazhan', 'first_name' => 'Mykola', 'middle_name' => 'Platonovych'],
            ['last_name' => 'Antonenko-Davydovych', 'first_name' => 'Borys', 'middle_name' => 'Dmytrovych'],
        ];

        foreach ($authors as $author) {
            Author::create($author);
        }
    }
}
