<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Autor extends Model
{
    use HasFactory;

    protected $table = 'Autor';

    protected $primaryKey = 'CodAu';

    protected $fillable = ['Nome'];

    public $timestamps = false;

    public function livros(): BelongsToMany
    {
        return $this->belongsToMany(Livro::class, 'Livro_Autor', 'Autor_CodAu', 'Livro_Codl');
    }
}
