<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
	protected $table = 'zones'; // confirm table name is 'zones'
    protected $primaryKey = 'id'; // usually default, but just confirm
    public $timestamps = true;

}
