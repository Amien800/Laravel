<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'categories';
    protected $fillable = ['name', 'img_link'];
    protected $primaryKey = 'id';

    public function listBook()
    {
        return $this->hasMany(Books::class, 'category_id');
    }
}
