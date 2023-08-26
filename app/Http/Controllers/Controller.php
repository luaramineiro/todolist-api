<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
/**
     * @OA\Info(
     *      version="1.0.0",
     *      title="OpenApi To-do List",
     *      description="RESTful API for a task management application (to-do list).",
     *      @OA\Contact(
     *          email="luaramineiro@gmail.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Demo API To-do List Server"
     * )

     *
     * @OA\Tag(
     *     name="To-do List",
     *     description="API Endpoints of to-do list"
     * )
     */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
