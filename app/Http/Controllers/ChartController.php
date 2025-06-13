<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TodoList;

class ChartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TodoList::query();
        $response = [];
        $type = null;

        if ($request->has('type')) {
            $type = $request->type;
        }

        switch ($type) {
            case 'status':
                $response = $this->statusSummaries($query);
                break;
            case 'priority':
                $response = $this->prioritySummaries($query);
                break;
            case 'assignee':
                $response = $this->assigneeSummaries($query);
                break;
            default:
                $response = [
                    'status_summaries' => $this->statusSummaries($query),
                    'priority_summaries' => $this->prioritySummaries($query),
                    'assignee_summaries' => $this->assigneeSummaries($query),
                ];
                break;
        }
        
        return response()->json([
            'status' => true,
            'data' => $response
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function statusSummaries($query)
    {
        $todos = $query->get();
        $counts = $todos->groupBy('status')->map(function($group) {
            return $group->count();
        });
        return ['status_summaries' => $counts];
    }
    
    private function prioritySummaries($query)
    {
        $todos = $query->get();
        $counts = $todos->groupBy('priority')->map(function($group) {
            return $group->count();
        });
        return ['priority_summaries' => $counts];
    }

    private function assigneeSummaries($query)
    {
        $todos = $query->get();
        $counts = $todos->groupBy('assignee')->map(function($group) {
            return [
                'total_todos' => $group->count(),
                'total_pending_todos' => $group->where('status', 'pending')->count(),
                'total_completed_todos' => $group->where('status', 'completed')->count(),
            ];
        });
        return [
            'assignee_summaries' => $counts,
        ];
    }

}
