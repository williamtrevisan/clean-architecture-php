<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['id', 'name'];

    protected $casts = ['id' => 'string'];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
