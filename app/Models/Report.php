<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'file_name'];

    public function getStatusAttribute($value)
    {
        return match ($value) {
            'generating' => 'Processando...',
            'completed' => 'Completo',
            'failed' => 'Falhou!',
            default => 'Desconhecido',
        };
    }
}
