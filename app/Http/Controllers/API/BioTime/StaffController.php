<?php

namespace App\Http\Controllers\API\BioTime;

use App\Actions\CreateStaffAction;
use App\FormDatas\StaffFormData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Resources\StaffResource;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    /**
     * Get a list of Employees
     *
     * @queryParam per_page integer The perpage number. Example: 10
     * @queryParam page integer The page number. Example: 1
     *
     * @responseFile  responses/Employee/employees.get.json
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page;

        $employees = Staff::query();

        if($perPage) {
            $employees = $employees->paginate($perPage);
        } else {
            $employees = $employees->get();
        }

        return StaffResource::collection($employees);
    }

    /**
     * Create a New Employee
     *
     * @bodyParam department integer required The id of the department. Example: 1
     * @bodyParam position integer required The id of the position. Example: 1
     * @bodyParam employee_id string required The employee id of the employee. Example: emp-001
     * @bodyParam first_name string required The first name of the employee. Example: John
     * @bodyParam last_name string required The last name of the emmployee. Example: doe
     * @bodyParam gender string nullable The gender of the emmployee(Male, Female, Other). Example: Male
     * @bodyParam emp_type string nullable The emmployee type of the emmployee. Example: Permanent
     * @bodyParam phone string nullable The contact number of the emmployee. Example: 98243324234
     * @bodyParam date_of_birth string nullable The date of birth of the emmployee. Example: 1988-03-03
     * @bodyParam city string nullable The last name of the emmployee. Example: New York
     * @bodyParam address string nullable The last name of the emmployee. Example: New York
     * @bodyParam postcode string nullable The last name of the emmployee. Example: 44001
     * @bodyParam join_date string nullable The last name of the emmployee. Example: 2021-03-04
     * @bodyParam image image nullable The last name of the emmployee. Example: /image/uplodas/imag1.png
     *
     *
     * @responseFile  responses/Employee/created.get.json
     */
    public function store(StoreStaffRequest $request)
    {
        $imagename = '';
        $national_id_name = '';
        $documents_name = '';
        $contract_name = '';

        DB::beginTransaction();
        try {
            if($request->hasFile('image')) {
                $imagename = $request->file('image')->store( 'staff_docs', 'uploads' );
            }

            if($request->hasFile('national_id')) {
                $national_id_name = $request->file('national_id')->store( 'staff_docs', 'uploads' );
            }

            if($request->hasFile('documents'))  {
                $documents_name = $request->file('documents')->store( 'staff_docs', 'uploads' );
            }

            if($request->hasFile('contract'))  {
                $contract_name = $request->file('contract')->store( 'staff_docs', 'uploads' );
            }
            $staffFormData = new StaffFormData(
                (int) $request->department,
                (int) $request->position,
                (string) $request->employee_id,
                $request->first_name,
                $request->last_name,
                $request->email,
                $request->phone,
                $request->gender,
                $request->emp_type,
                $request->date_of_birth,
                $request->city,
                $request->address,
                $request->postcode,
                $request->join_date,
                $imagename,
                $national_id_name,
                $documents_name,
                $contract_name,
            );

            $staff = (new CreateStaffAction)->execute($staffFormData);

            DB::commit();

        } catch(\Exception $e) {
            DB::rollBack();
            return $this->responseError($e->getMessage(), 500);
        }

        return $this->responseOk(
            "Staff Created Successfully",
            StaffResource::make($staff),
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
