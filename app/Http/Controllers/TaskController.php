<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Models\Task;

/**
 * Class Task.
 *
 * @author  Luara Mineiro <luaramineiro@gmail.com>
 */

 class TaskController extends Controller 
{
    /**
     * @OA\Get(
     *     path="/tasks",
     *     tags={"To-do List"},
     *     description="Returns the list of all tasks.",
     *     @OA\Response(
     *          response="200", 
     *          description="OK",
     *          @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/Task"),
     *             },
     *             @OA\Examples(
     *                  example="result", 
     *                  value={
     *                     "current_page": 1,
     *                     "data": {
     *                           {
     *                              "id": "99f89841-8f88-4ae0-af13-11e934ccf40a",
     *                              "title": "Title example",
     *                              "description": "A description example",
     *                              "attachment": null,
     *                              "is_completed": true,
     *                              "completed_at": "2023-08-25 02:17:34",
     *                              "created_at": "2023-08-25T02:17:33.000000Z",
     *                              "updated_at": "2023-08-25T02:17:33.000000Z",
     *                              "deleted_at": null,
     *                              "user_id": "224ee47b-11cc-4965-b9cd-8464916ce5ad"
     *                           }
     *                     },
     *                     "links": {
     *                           {
     *                              "url": null,
     *                              "label": "&laquo; Previous",
     *                              "active": false
     *                           },
     *                           {
     *                              "url": "http://localhost:8000/api/tasks?page=1",
     *                              "label": "1",
     *                              "active": true
     *                           },
     *                           {
     *                              "url": null,
     *                              "label": "Next &raquo;",
     *                              "active": false
     *                           }
     *                     },
     *                     "next_page_url": null,
     *                     "path": "http://localhost:8000/api/tasks",
     *                     "per_page": 15,
     *                     "prev_page_url": null,
     *                     "to": 2,
     *                     "total": 2
     *                  }, 
     *                  summary="An result object."
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result", 
     *                  summary="An result object.",
     *                  value={
     *                      "status" : "400",
     *                      "message" : "error message",
     *                  }
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result", 
     *                  summary="An result object.",
     *                  value={
     *                      "status" : "500",
     *                      "message" : "error message",
     *                  }
     *              )
     *         )
     *     )
     * )
     */
    public function index() {
        $tasks = Task::paginate();
        return response()->json($tasks, 200);
    }

    /**
     * @OA\Post(
     *     path="/tasks",
     *     tags={"To-do List"},
     *     description="Creates a new task.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/Task"),
     *             },
     *             @OA\Examples(
     *                  example="example", 
     *                  value={ 
     *                      "title": "Title example", 
     *                      "description": "A description example", 
     *                      "is_completed": true,
     *                      "attachment": {
     *                          "0": "file:website.com/pathtofile/intro.pdf",
     *                          "1": "file:website.com/pathtofile/photo.png"
     *                      },
     *                      "user_id": "224ee47b-11cc-4965-b9cd-8464916ce5ad" 
     *                  }, 
     *                  summary="An example object."
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/Task"),
     *             },
     *             @OA\Examples(
     *                  example="result", 
     *                  value={
     *                      "status": "201",
     *                      "message": "Task created",
     *                      "data": {
     *                          "id": "99f857d3-3119-4be9-b078-c2136ace0314",
     *                          "title": "Title example",
     *                          "description": "A description example",
     *                          "attachment": {
     *                              "0": "file:website.com/pathtofile/intro.pdf",
     *                              "1": "file:website.com/pathtofile/photo.png"
     *                          },
     *                          "is_completed": false,
     *                          "is_deleted": false,
     *                          "deleted_at": null,
     *                          "completed_at": null,
     *                          "created_at": "2023-08-24T23:17:24.000000Z",
     *                          "updated_at": "2023-08-24T23:17:24.000000Z",
     *                          "user_id": "224ee47b-11cc-4965-b9cd-8464916ce5ad"
     *                      }
     *                  }, 
     *                  summary="An result object."
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result", 
     *                  summary="An result object.",
     *                  value={
     *                      "status" : "400",
     *                      "message" : "error message",
     *                  }
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result", 
     *                  summary="An result object.",
     *                  value={
     *                      "status" : "500",
     *                      "message" : "error message",
     *                  }
     *              )
     *         )
     *     )
     * )
     */
    public function store(TaskRequest $request) {
        $validatedTask = $request->validated();

        if (array_key_exists("attachment", $validatedTask)) {
            $validatedTask["attachment"] = json_encode($validatedTask["attachment"]);
        }
        
        $task = Task::create($validatedTask);
    
        return response()->json([
            'status'    => '201',
            'message'   => "Task created",
            "data"      => $task
        ], 201);
    }
    
    /**
     * @OA\Get(
     *     path="/tasks/{id}",
     *     tags={"To-do List"}, 
     *     description="Returns the details of a specific task.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of task that needs to be fetched",
     *         @OA\Schema(type="uuid")
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="OK",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/Task"),
     *             },
     *             @OA\Examples(
     *                  example="result", 
     *                  value={
     *                      "status": "200",
     *                      "message": "Task retrieved",
     *                      "data": {
     *                          "id": "99f857d3-3119-4be9-b078-c2136ace0314",
     *                          "title": "Title example",
     *                          "description": "A description example",
     *                          "attachment": {
     *                              "0": "file:website.com/pathtofile/intro.pdf",
     *                              "1": "file:website.com/pathtofile/photo.png"
     *                          },
     *                          "is_completed": false,
     *                          "is_deleted": false,
     *                          "deleted_at": null,
     *                          "completed_at": null,
     *                          "created_at": "2023-08-24T23:17:24.000000Z",
     *                          "updated_at": "2023-08-24T23:17:24.000000Z",
     *                          "user_id": "224ee47b-11cc-4965-b9cd-8464916ce5ad"
     *                      }
     *                  }, 
     *                  summary="An result object."
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result", 
     *                  summary="An result object.",
     *                  value={
     *                      "status" : "400",
     *                      "message" : "error message",
     *                  }
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result", 
     *                  summary="An result object.",
     *                  value={
     *                      "status" : "404",
     *                      "message" : "Task with id {id} not found",
     *                  }
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result", 
     *                  summary="An result object.",
     *                  value={
     *                      "status" : "500",
     *                      "message" : "error message",
     *                  }
     *              )
     *         )
     *     )
     * )
     */
    public function show(Task $task) {
        return response()->json([
            'status'    => '200',
            'message'   => "Task retrieved.",
            "data"      => $task
        ], 200);
    }
    
    /**
     * 
     * @OA\Put(
     *     path="/tasks/{id}",
     *     tags={"To-do List"},
     *     description="Updates the details of a specific task.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of task that needs to be updated",
     *         @OA\Schema(
     *             type="uuid",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/Task"),
     *             },
     *             @OA\Examples(
     *                  example="example", 
     *                  value={
     *                      "title": "Title example updated",
     *                      "description": "A description example updated", 
     *                      "attachment": {
     *                          "0": "file:website.com/pathtofile/intro.pdf",
     *                          "1": "file:website.com/pathtofile/photo.png"
     *                      },
     *                      "is_completed": true,
     *                  }, 
     *                  summary="An example object."
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                  example="result", 
     *                  value={
     *                      "status": "200",
     *                      "message": "Task updated",
     *                      "data": {
     *                          "id": "99f857d3-3119-4be9-b078-c2136ace0314",
     *                          "title": "Title example updated",
     *                          "description": "A description example updated",
     *                          "attachment": {
     *                              "0": "file:website.com/pathtofile/intro.pdf",
     *                              "1": "file:website.com/pathtofile/photo.png"
     *                          },
     *                          "is_completed": true,
     *                          "is_deleted": false,
     *                          "deleted_at": null,
     *                          "completed_at": null,
     *                          "created_at": "2023-08-24T23:17:24.000000Z",
     *                          "updated_at": "2023-08-24T23:17:24.000000Z",
     *                          "user_id": "224ee47b-11cc-4965-b9cd-8464916ce5ad"
     *                      }
     *                  }, 
     *                  summary="An result object."
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result", 
     *                  summary="An result object.",
     *                  value={
     *                      "status" : "400",
     *                      "message" : "error message",
     *                  }
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result", 
     *                  summary="An result object.",
     *                  value={
     *                      "status" : "404",
     *                      "message" : "Task with id {id} not found",
     *                  }
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result", 
     *                  summary="An result object.",
     *                  value={
     *                      "status" : "500",
     *                      "message" : "error message",
     *                  }
     *              )
     *         )
     *     )
     * )
     */
    public function update(TaskRequest $request, Task $task) {
        $validatedTask = $request->validated();

        if (array_key_exists("attachment",$validatedTask)) {
            $validatedTask["attachment"] = json_encode($validatedTask["attachment"]);
        }
        
        if ($task->update($validatedTask)) {
            return response()->json([
                'status'    => '200',
                'message'   => "Task updated.",
                "data"      => Task::find($task->id)
            ], 200);
        }
    }

    
    /**
     * @OA\Delete(
     *     path="/tasks/{id}",
     *     tags={"To-do List"},
     *     description="Removes a specific task.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of task that needs to be deleted",
     *         @OA\Schema(
     *             type="uuid",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="OK",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/Task"),
     *             },
     *             @OA\Examples(
     *                  example="result", 
     *                  value={
     *                      "status": "200",
     *                      "message": "Task deleted.",
     *                      "data": {
     *                          "id": "99f857d3-3119-4be9-b078-c2136ace0314",
     *                          "title": "Title example",
     *                          "description": "A description example",
     *                          "attachment": {
     *                              "0": "file:website.com/pathtofile/intro.pdf",
     *                              "1": "file:website.com/pathtofile/photo.png"
     *                          },
     *                          "is_completed": false,
     *                          "is_deleted": false,
     *                          "deleted_at": null,
     *                          "completed_at": null,
     *                          "created_at": "2023-08-24T23:17:24.000000Z",
     *                          "updated_at": "2023-08-24T23:17:24.000000Z",
     *                          "user_id": "224ee47b-11cc-4965-b9cd-8464916ce5ad"
     *                      }
     *                  }, 
     *                  summary="An result object."
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result", 
     *                  summary="An result object.",
     *                  value={
     *                      "status" : "400",
     *                      "message" : "error message",
     *                  }
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result", 
     *                  summary="An result object.",
     *                  value={
     *                      "status" : "404",
     *                      "message" : "Task with id {id} not found",
     *                  }
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result", 
     *                  summary="An result object.",
     *                  value={
     *                      "status" : "500",
     *                      "message" : "error message",
     *                  }
     *              )
     *         )
     *     )
     * )
     */
    public function destroy(Task $task) {
        if ($task->delete()) {
            return response()->json([
                'status'    => '200',
                'message'   => "Task deleted.",
                "data"      => $task
            ], 200);
        }
    }
}