<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ItemImage extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $appends = ['url']; // نضيف اسم الحقل الجديد الذي نريده يظهر في JSON

    public function getUrlAttribute()
    {
        return Storage::url('item_images/' . $this->id . '.png');
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
