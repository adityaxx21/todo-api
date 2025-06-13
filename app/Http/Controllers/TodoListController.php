<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use App\Http\Requests\StoreTodoListRequest;
use App\Http\Requests\UpdateTodoListRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Helpers\helper;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource with filters.
     * Filters: status, priority, assignee, due_date
     * GET /api/todos?status=pending&priority=high&assignee=John&due_date=2025-06-12
     */
    public function index(Request $request)
    {
        error_log(json_encode(commaSplit($request->status)));
        $query = TodoList::query();
        if ($request->has('title')) {
            $query->whereLike('title', '%'.$request->title.'%');
        }
        if ($request->has('status')) {
            $query->whereIn('status', commaSplit($request->status));
        }
        if ($request->has('priority')) {
            $query->whereIn('priority', commaSplit($request->priority));
        }
        if ($request->has('assignee')) {
            $assignees = commaSplit($request->assignee);
            $query->where(function($q) use ($assignees) {
                foreach ($assignees as $assignee) {
                    $q->orWhere('assignee', 'LIKE', "%$assignee%");
                }
            });
        }
        if ($request->has('start') || $request->has('end')) {
            $query->whereBetween('due_date', dateRange($request->start, $request->end));
        }
        if ($request->has('min') && $request->has('max')) {
            $query->whereBetween('time_tracked', [$request->min, $request->max]);
        }
        $todos = $query->orderByDesc('id')->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $todos
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/todos
     */
    public function store(StoreTodoListRequest $request)
    {
        $todo = TodoList::create($request->validated());
        $result = TodoList::find($todo->id);
        return response()->json([
            'status' => true,
            'message' => 'Todo created successfully.',
            'data' => $result
        ], 201);
    }

    /**        
     * Display the specified resource.
     * GET /api/todos/{id}
     */
    public function show(TodoList $todoList)
    {
        return response()->json([
            'status' => true,
            'data' => $todoList
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * PUT /api/todos/{id}
     */
    public function update(UpdateTodoListRequest $request)
    {
        try {
            // Get validated data from request
            $newData = $request->validated();
            $todoId = $request->route()->todo;
            $currentData = TodoList::find($todoId);

            // Return 404 if todo not found
            if (!$currentData) {
                return response()->json([
                    'status' => false,
                    'message' => 'Todo not found',
                ], 404);
            }

            // Update fields only if they exist in the request
            if (isset($newData['title'])) {
                $currentData->title = $newData['title'];
            }
            if (isset($newData['assignee'])) {
                $currentData->assignee = $newData['assignee'];
            }
            if (isset($newData['due_date'])) {
                $currentData->due_date = $newData['due_date'];
            }
            if (isset($newData['status'])) {
                $currentData->status = $newData['status'];
            }
            if (isset($newData['priority'])) {
                $currentData->priority = $newData['priority'];
            }
            if (isset($newData['time_tracked'])) {
                $currentData->time_tracked = $newData['time_tracked'];
            }

            // Save changes
            $currentData->save();

            // Return success response with updated data
            return response()->json([
                'status' => true,
                'message' => 'Todo updated successfully.',
                'data' => $currentData->fresh()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update todo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/todos/{id}
     */
    public function destroy(Request $request)
    {
        $todoId = $request->route()->todo;
        $todoList = TodoList::find($todoId);
        if (!$todoList) {
            return response()->json([
                'status' => false,
                'message' => 'Todo not found',
            ], 404);
        }
        $todoList->delete();
        return response()->json([
            'status' => true,
            'message' => 'Todo deleted successfully.'
        ], 200);
    }

    /**
     * Export todos to Excel (filtered).
     * GET /api/todos/export?status=pending&priority=high
     */
    public function export(Request $request)
    {
        $query = TodoList::query();
        if ($request->has('title')) {
            $query->whereLike('title', "%$request->title%");
        }
        if ($request->has('status')) {
            $query->whereIn('status', commaSplit($request->status));
        }
        if ($request->has('priority')) {
            $query->whereIn('priority', commaSplit($request->priority));
        }
        if ($request->has('assignee')) {
            $assignees = commaSplit($request->assignee);
            $query->where(function($q) use ($assignees) {
                foreach ($assignees as $assignee) {
                    $q->orWhereLike('assignee', "%$assignee%");
                }
            });
        }
        if ($request->has('due_date')) {
            $query->whereDateBetween('due_date', dateRange($request->start, $request->end));
        }
        if ($request->has('time_tracked')) {
            $query->whereBetween('time_tracked', [$request->min, $request->max]);
        }
        $todos = $query->get();
        $exportData = $todos->map(function($todo) {
            return [
                'Title' => $todo->title,
                'Assignee' => $todo->assignee,
                'Due Date' => $todo->due_date,
                'Status' => $todo->status,
                'Priority' => $todo->priority,
            ];
        });
        $filename = 'todos_export_'.now()->format('Ymd_His').'.xlsx';
        return Excel::download(new class($exportData) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $data;
            public function __construct($data) { $this->data = $data; }
            public function collection() { return collect($this->data); }
            public function headings(): array { return ['Title', 'Assignee', 'Due Date', 'Status', 'Priority']; }
        }, $filename);
    }
}
