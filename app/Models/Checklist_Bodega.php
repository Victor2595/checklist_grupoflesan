<?php
    
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Eloquent;

class Checklist_Bodega extends Model{

	protected $connection = 'pgsql';
    protected $table = 'abastecimiento.clbod';
    protected $primaryKey = 'clbod_id';
    public $timestamps = false;
    protected $fillable = ['clbod_obra_id','clbod_tipo','clbod_semana','clbod_ano','clbod_create_date','clbod_create_user','clbod_validate_date','clbod_validate_user','clbod_cumplimiento'];

}