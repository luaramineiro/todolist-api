<?php

/**
 * @license Apache 2.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Task.
 *
 * @author  Luara Mineiro <luaramineiro@gmail.com>
 *
 * @OA\Schema(
 *     description="Task model",
 *     title="Task model",
 *     required={"id","title"},
 *     @OA\Xml(
 *         name="Task"
 *     )
 * )
 */
class Task extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

    protected $fillable = ['title', 'description', 'attachment', 'is_completed', 'user_id'];
    
}
