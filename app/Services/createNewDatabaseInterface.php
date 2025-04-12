<?php
namespace App\Services;

interface createNewDatabaseInterface
{
    public function createNewDatabase($request, $company_id , $user_id,$company_initial = null);
}

