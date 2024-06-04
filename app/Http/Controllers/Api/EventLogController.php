<?php

namespace App\Http\Controllers\Api;

use App\Models\EventLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventLogController extends Controller
{
    protected $model = Setting::class;

    /**
     * @OA\Get(
     *      path="/api/event-logs",
     *      tags={"Event-Logs"},
     *      security={{"bearer_token":{}}},
     *      summary="Get list of Event Logs",
     *      description="Returns list of Event Logs",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/EventLog")
     *       ),
     *     )
     */

    public function index()
    {
        return EventLog::all();
    }

    /**
     * @OA\Get(
     *      path="/api/event-logs/{id}",
     *      tags={"Event-Logs"},
     *      security={{"bearer_token":{}}},
     *      summary="Get Event Log by ID",
     *      description="Returns Event Log",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/EventLog")
     *       ),
     *     )
     */
    public function show(EventLog $event)
    {
        return $event;
    }
}