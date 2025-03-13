<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'stock',
    ];

    /**
     * Get the product's initials
     */
    public function initials(int $count = 2): string
    {
        $startLetter = Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name, int $index) => Str::of($name)->substr(0, 1));

        return $count ? $startLetter->take($count)->implode(''): $startLetter->implode('');
    }
}
