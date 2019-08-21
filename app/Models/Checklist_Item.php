<?php
    
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Eloquent;

class Checklist_Item extends Model{

	protected $connection = 'pgsql';
    protected $table = 'abastecimiento.clbod_item';
    protected $primaryKey = 'clbod_item_id';
    public $timestamps = false;
    protected $fillable = ['clbod_item_obra_id','clbod_item_tipo','clbod_item_semana','clbod_item_ano','clbod_item_preguntas_id','clbod_item_cumple','clbod_item_por','clbod_create_date','clbod_create_user'];

}