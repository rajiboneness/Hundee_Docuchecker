<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    public static function insertData($data) {
        $value = DB::table('products')->where('name', $data['name'])->get();
        if($value->count() == 0) {
           DB::table('products')->insert($data);
        }
    }
}
