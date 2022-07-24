<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Borrow;
use Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker\Factory::create();
        // \App\Models\User::factory(10)->create();
        $this->call(UsersTableSeeder::class);
        $this->call(BooksTableSeeder::class);
        $this->call(GenresTableSeeder::class);

        $users = User::all();
        $books = Book::all();
        $genres = Genre::all();

        for($i = 0; $i<20; $i++){
            $r = rand(1,5);
            $gens = [];
            for($j = 0; $j<$r; $j++){
                $r2 = rand(1,8);
                while(in_array($r2,$gens)){
                    $r2 = rand(1,8);
                }
                $gens[] = $r2;
            }
            $books->get($i)->genres()->attach($gens);
        }

        $readers = User::all()->where('is_librarian', '0')->pluck('id');
        $librarians = User::all()->where('is_librarian', '1')->pluck('id');
        $types = ['PENDING', 'ACCEPTED', 'REJECTED', 'RETURNED'];
        $booksIds = Book::all()->pluck('id');
        for($i = 0; $i<30; $i++){
            Borrow::create([
                'reader_id' => $faker->randomElement($readers),
                'book_id' => $faker->randomElement($booksIds),
                'status' => 'PENDING',
                'request_processed_at' => null,
                'request_managed_by' => null,
                'request_processed_message' => null,
                'deadline' => null,
                'returned_at' => null,
                'return_managed_by' => null
            ]);
        }

        for($i = 0; $i<30; $i++){
            Borrow::create([
                'reader_id' => $faker->randomElement($readers),
                'book_id' => $faker->randomElement($books)->id,
                'status' => 'ACCEPTED',
                'request_processed_at' => $faker->dateTimeThisYear('now',null),
                'request_managed_by' => $faker->randomElement($librarians),
                'request_processed_message' => null,
                'deadline' => $faker->dateTimeThisYear(date_add(date_create('now'), date_interval_create_from_date_string('30 days')),null),
                'returned_at' => null,
                'return_managed_by' => null
            ]);
        }

        for($i = 0; $i<30; $i++){
            Borrow::create([
                'reader_id' => $faker->randomElement($readers),
                'book_id' => $faker->randomElement($books)->id,
                'status' => 'REJECTED',
                'request_processed_at' => $faker->dateTimeThisYear('now',null),
                'request_managed_by' => $faker->randomElement($librarians),
                'request_processed_message' => null,
                'deadline' => null,
                'returned_at' => null,
                'return_managed_by' => null
            ]);
        }

        for($i = 0; $i<30; $i++){
            Borrow::create([
                'reader_id' => $faker->randomElement($readers),
                'book_id' => $faker->randomElement($books)->id,
                'status' => 'RETURNED',
                'request_processed_at' => $faker->dateTimeThisYear('now',null),
                'request_managed_by' => $faker->randomElement($librarians),
                'request_processed_message' => null,
                'deadline' => $faker->dateTimeThisYear(date_add(date_create('now'), date_interval_create_from_date_string('30 days')),null),
                'returned_at' => $faker->dateTimeThisYear('now',null),
                'return_managed_by' => $faker->randomElement($librarians),
            ]);
        }

        /*Borrow::create([
            'reader_id' => $users->get(6)->id,
            'book_id' => $books->get(10)->id,
            'status' => 'PENDING',
            'request_processed_at' => null,
            'request_managed_by' => null,
            'request_processed_message' => null,
            'deadline' => null,
            'returned_at' => null,
            'return_managed_by' => null
        ]);

        Borrow::create([
            'reader_id' => $users->get(6)->id,
            'book_id' => $books->get(10)->id,
            'status' => 'PENDING',
            'request_processed_at' => null,
            'request_managed_by' => null,
            'request_processed_message' => null,
            'deadline' => null,
            'returned_at' => null,
            'return_managed_by' => null
        ]);

        Borrow::create([
            'reader_id' => $users->get(7)->id,
            'book_id' => $books->get(4)->id,
            'status' => 'ACCEPTED',
            'request_processed_at' => date('Y-m-d H:i:s'),
            'request_managed_by' => $users->get(1)->id,
            'request_processed_message' => 'ok',
            'deadline' => '2021-04-23 23:59:59',
            'returned_at' => null,
            'return_managed_by' => null
        ]);

        Borrow::create([
            'reader_id' => $users->get(8)->id,
            'book_id' => $books->get(17)->id,
            'status' => 'REJECTED',
            'request_processed_at' => date('Y-m-d H:i:s'),
            'request_managed_by' => $users->get(2)->id,
            'request_processed_message' => null,
            'deadline' => null,
            'returned_at' => null,
            'return_managed_by' => null
        ]);

        Borrow::create([
            'reader_id' => $users->get(9)->id,
            'book_id' => $books->get(8)->id,
            'status' => 'RETURNED',
            'request_processed_at' => date('Y-m-d H:i:s'),
            'request_managed_by' => $users->get(2)->id,
            'request_processed_message' => null,
            'deadline' => null,
            'returned_at' => date('Y-m-d H:i:s'),
            'return_managed_by' => $users->get(1)->id
        ]);

        Borrow::create([
            'reader_id' => $users->get(7)->id,
            'book_id' => $books->get(14)->id,
            'status' => 'PENDING',
            'request_processed_at' => null,
            'request_managed_by' => null,
            'request_processed_message' => null,
            'deadline' => null,
            'returned_at' => null,
            'return_managed_by' => null
        ]);

        Borrow::create([
            'reader_id' => $users->get(7)->id,
            'book_id' => $books->get(1)->id,
            'status' => 'PENDING',
            'request_processed_at' => null,
            'request_managed_by' => null,
            'request_processed_message' => null,
            'deadline' => null,
            'returned_at' => null,
            'return_managed_by' => null
        ]);

        Borrow::create([
            'reader_id' => $users->get(7)->id,
            'book_id' => $books->get(1)->id,
            'status' => 'ACCEPTED',
            'request_processed_at' => date('Y-m-d H:i:s'),
            'request_managed_by' => $users->get(1)->id,
            'request_processed_message' => null,
            'deadline' => '2021-04-23 23:59:59',
            'returned_at' => null,
            'return_managed_by' => null
        ]);

        Borrow::create([
            'reader_id' => $users->get(7)->id,
            'book_id' => $books->get(1)->id,
            'status' => 'RETURNED',
            'request_processed_at' => date('Y-m-d H:i:s'),
            'request_managed_by' => null,
            'request_processed_message' => null,
            'deadline' => null,
            'returned_at' => date('Y-m-d H:i:s'),
            'return_managed_by' => $users->get(7)->id
        ]);*/

        Borrow::all()->each(function ($borrow) use (&$books, &$users){
            $borrow->book()->associate($books->get($borrow->book_id - 1));
            $borrow->save();
            $borrow->reader()->associate($users->get($borrow->reader_id - 1));
            $borrow->save();
            if($borrow->request_managed_by !== null){
                $borrow->request_managed_by()->associate($users->get($borrow->request_managed_by - 1));
                $borrow->save();
            }
            if($borrow->return_managed_by !== null){
                $borrow->return_managed_by()->associate($users->get($borrow->return_managed_by - 1));
                $borrow->save();
            }
        });
    }
}
