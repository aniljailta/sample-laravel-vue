<?php

namespace App\Repositories\Employees;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

use App\Notifications\Employees\EmployeeCreated;


/**
 * Class UserRepository.
 */
class EmployeesRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = User::class;

    /**
     * @param array $input
     */
    public function create($input)
    {
        $data = $input['data'];
        $role = $input['roles'];
        $user = $this->createUserStub($data);
        $raw_pass = $user->password;
        $user->password = bcrypt($raw_pass);

        DB::transaction(function () use ($user, $data, $role, $raw_pass) {
            if ($user->save()) {
                
                //Attach new roles
                $user->attachRole($role);

                //need to send notification here 
                $user->notify(new EmployeeCreated($user->first_name.' '.$user->last_name, $raw_pass));
                return true;
            }

            throw new GeneralException(trans('exceptions.employees.create_error'));
        });
    }

    /**
     * @param array $input
     */
    public function update($input)
    {
        $data = $input['data'];
        $user = $this->updateUserStub($data);
        
        DB::transaction(function () use ($user) {
            if ($user->save()) {
                return true;
            }

            throw new GeneralException(trans('exceptions.employees.update_error'));
        });
    }


    /**
     * @param  $input
     *
     * @return mixed
     */
    protected function createUserStub($input)
    {
        $user = self::MODEL;
        $user = new $user;
        $user->first_name = $input['first_name'];
        $user->last_name = $input['last_name'];
        $user->email = $input['email'];
        $user->password = str_random(8);
        return $user;
    }


    /**
     * @param  $input
     *
     * @return mixed
     */
    protected function updateUserStub($input)
    {
        $user = self::MODEL;
        $user = $user::find($input['id']);
        $user->first_name = $input['first_name'];
        $user->last_name = $input['last_name'];
        $user->email = $input['email'];
        return $user;
    }

    

}