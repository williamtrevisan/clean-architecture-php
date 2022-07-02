<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'id', 'library_id', 'title', 'number_of_pages', 'year_launched',
    ];

    protected $casts = ['id' => 'string', 'id_library' => 'string'];

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'books_authors');
    }
}
