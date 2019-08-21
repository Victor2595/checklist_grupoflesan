<?php
    
namespace App\Models;
    
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class Preguntas extends Model{
	
    protected $connection = 'pgsql';
    protected $table = 'abastecimiento.clbod_preguntas';
    protected $primaryKey = 'clbod_preguntas_item_id';
    public $timestamps = false;
    protected $fillable = ['clbod_preguntas_item_id'];
}