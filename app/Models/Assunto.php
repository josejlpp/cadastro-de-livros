<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Assunto extends Model
{
    use HasFactory;

    protected $table = 'Assunto';

    protected $primaryKey = 'CodAs';

    protected $fillable = ['Descricao'];

    public $timestamps = false;

    public function livros(): BelongsToMany
    {
        return $this->belongsToMany(Livro::class, 'Livro_Assunto', 'Assunto_CodAs', 'Livro_Codl');
    }
}
