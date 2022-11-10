<?php

namespace App\Repositories\Employee;

use App\Repositories\RepositoryInterface;

interface EmployeeRepositoryInterface extends RepositoryInterface
{
    public function checkEmployee($email);
    public function addEmployeeDetail($info);
}
