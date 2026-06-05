<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen_url'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    // --- QUERY SCOPES (Práctica 07) ---

    public function scopeBuscar($query, $termino) {
        return $query->when($termino, function($q) use ($termino) {
            $q->where(function($sub) use ($termino) {
                $sub->where('nombre', 'LIKE', "%{$termino}%")
                    ->orWhere('descripcion', 'LIKE', "%{$termino}%");
            });
        });
    }

    public function scopeDeCategoria($query, $categoriaId) {
        return $query->when($categoriaId, function($q) use ($categoriaId) {
            $q->where('categoria_id', $categoriaId);
        });
    }

    public function scopeRangoPrecio($query, $min, $max) {
        return $query
            ->when($min, fn($q) => $q->where('precio', '>=', $min))
            ->when($max, fn($q) => $q->where('precio', '<=', $max));
    }
}