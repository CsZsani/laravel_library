<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $authorsNum = rand(1,3);
        $authors = "";
        if($authorsNum == 1){
            $authors = $this->faker->name();
        }else if($authorsNum == 2){
            $authors = $this->faker->name() . ", " . $this->faker->name();
        }else{
            $authors = $this->faker->name() . ", " . $this->faker->name() . ", " . $this->faker->name();
        }

        $isbn = "";
        for($i = 0; $i<13; $i++){
            $isbn = $isbn . (string)$this->faker->randomDigit();
        }

        return [
            'title' => $this->faker->word(),
            'authors' => $authors,
            'description' => $this->faker->optional($weight = 0.8)->text(),
            'released_at' => $this->faker->dateTime(),
            'cover_image' => null,
            'pages' => $this->faker->numberBetween(50,600),
            'language_code' => "hu",
            'isbn' => $isbn,
            'in_stock' => $this->faker->numberBetween(10,20),
        ];
    }
}
