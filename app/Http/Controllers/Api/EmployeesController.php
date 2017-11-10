<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employees\AddEmployeeRequest;
use App\Http\Requests\Employees\DeleteEmployeeRequest;
use App\Http\Requests\Employees\UpdateEmployeeRequest;

use App\Models\User;

use App\Repositories\Employees\EmployeesRepository;
use App\Services\ArrayBuilder\ArrayBuilderAssistant;

class EmployeesController extends Controller
{
   /**
     * @var EmployeesRepository
     */
   protected $employees;


    /**
     * @param EmployeesRepository $employees
     */
    public function __construct(EmployeesRepository $employees)
    {
        $this->employees = $employees;
    }
   
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new User());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }
        $query = $abAssistant->apply()->getQuery();

        $query->join('role_user', function ($join) {
            $join->on('users.id', '=', 'role_user.user_id')
                 ->where('role_user.role_id', '=', 2);
        });

        $result = $abAssistant->setQuery($query)->paginate(request('page'), request('per_page'));
        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddEmployeeRequest $request)
    {
        try{
            $this->employees->create(
                [
                'data' => $request->only(
                    'first_name',
                    'last_name',
                    'email'
                    ),
                'roles' => '2',
                ]);
            return response()->json([
                'msg' => 'Employee created successfully.'
                ]);
        }
        catch (Exception $e) {
            \Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }

    }

    /**
     * Display the specified resource.
     * @param $id
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function show($id, Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new User());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }

        $query = $abAssistant->apply()->getQuery();
        try {
            $item = $query->findOrFail($id);
            return response()->json($item);
        } catch (Exception $e) {
            \Log::error($e);
            return response()->json(['Employee is not found.'], 422);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateEmployeeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeRequest $request, $id)
    {
        try{
            $this->employees->update(
                [
                'data' => $request->all()
                ]);
            return response()->json([
                'msg' => 'Employee updated successfully.'
                ]);
        }
        catch (Exception $e) {
            \Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteEmployeeRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteEmployeeRequest $request, $id)
    {
        try
        {
            // get data which has got through validator
            $employee = \Store::get('employee');
            $employee->delete();
            return response()->json(['Employee successfully deleted.']);
        } catch (Exception $e)
        {
            return response()->json(['Something went wrong.'], 422);
        }
    }
}
