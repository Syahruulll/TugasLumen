<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stuff extends Model
{
    // jika di migrationnya menggunaakan $table->softdelete 
  use SoftDeletes;


  //fillable/ guarded
  // menentukan column wajib diisi (column yg bisa diisi diluar)
  protected $fillable =["name","category"];
  //protected $guarded = ['id']


  //property opsional :
  // kalau prymary key bukan id :public $prymarykey = "no"
  // kalau misal 

public function stuffStock()
{
    return $this->hasOne(StuffStock::class);
}

public function lendings()
{
 return $this->hasMany(Lending::class);   
}

public function inboundStuffs()
{
    return $this->hasMany(InboundStuff::class);
}
}
//relasi
//nama function :samain kaya model,kata pertma hhuruf kecil
//model pk :hasOne/ hasMany
//panggil namamodelFk::class

