<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'id', 'id_library', 'title', 'page_number', 'year_launched'
    ];

    protected $casts = ['id' => 'string', 'id_library' => 'string'];

    public function authors(): HasMany
    {
        return $this->hasMany(Author::class);
    }
}