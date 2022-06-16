<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;
    protected $table = 'actions';
    protected $guarded = ['id'];

    public function type() {
        return $this->belongsTo(Type::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function client() {
        return $this->belongsTo(Client::class);
    }
}
