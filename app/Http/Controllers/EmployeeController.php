<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = [
            'employees' => \App\Models\Employee::orderBy('created_at', 'desc')->get(),
        ];
        return view('employee', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validation = Validator::make($input, [
            'name' => 'required|string|max:255',
            'nip' => 'required|numeric|unique:employees',
            'position' => 'required|string|max:255',
            // 'start_date' => 'required|date',
            // 'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()->all()]);
        }

        Employee::create($input);

        return response()->json(['status' => true, 'message' => 'Employee created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Employee::find($id);
        return response()->json(['status' => true, 'message' => '', 'data' => $data, 'url' => route('employee.update', $id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();

        $validation = Validator::make($input, [
            'name' => 'required|string|max:255',
            'nip' => 'required|numeric|unique:employees,nip,' . $id . ',id',
            'position' => 'required|string|max:255',
            // 'start_date' => 'required|date',
            // 'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()->all()]);
        }

        Employee::find($id)->update($input);

        return response()->json(['status' => true, 'message' => 'Employee updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Employee::find($id)->delete();
        return response()->json(['status' => true, 'message' => 'Employee deleted successfully.']);
    }
}
