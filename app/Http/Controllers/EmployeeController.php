<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use App\Facades\UserContext;
use App\Queries\EmployeeQueries;

/**
 * @OA\Info(
 *     title="BPMS API",
 *     version="1.0.0",
 *     description="API documentation for BPMS app",
 * )
 */
class EmployeeController extends Controller
{


    /**
     * @OA\Get(
     *     path="/api/role/{email}",
     *     summary="Get an BP role TOA by email",
     *     description="Get BP role TOA from Table map_employee_application, map_cost_center_application.",
     *     operationId="Get BP role TOA",
     *     tags={"Employee General Info"},
     *     @OA\Parameter(
     *         name="email",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", example="nrsari@tugu.com")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function getRole($email): JsonResponse
    {
        try {
            $query = EmployeeQueries::getRoleToaQuery();

            $employee = DB::select($query, [$email, $email]);

            if (empty($employee)) {
                $query = DB::table('m_employee_general_info as a')
                    ->select(
                        'a.BP as partner',
                        'a.email',
                        'a.name',
                        'a.address',
                        'd.cost_center',
                        'b.title_id',
                        'c.title_name',
                        'd.cost_center_name'
                    )
                    ->join('map_employee_title as b', 'a.BP', '=', 'b.BP')
                    ->join('m_title as c', 'b.title_id', '=', 'c.title_id')
                    ->join('map_cost_center_hierarchy as d', 'b.cost_center', '=', 'd.cost_center')
                    ->where('a.email', '=', $email);
                $employeeData = $query->get();
                $sql = $query->toSql();
                $bindings = $query->getBindings();
                Log::info('SQL Query: ' . Str::replaceArray('?', $bindings, $sql));
                if ($employeeData->isEmpty()) {
                    return response()->json(['message' => 'No employees found'], 404);
                }
                return response()->json(['toa' => $employeeData, 'dots' => null], 200);
            }

            $listApp = array_map(function ($item) {
                return $item->app_name;
            }, $employee);

            $hasDots = in_array('DOTS', $listApp);

            $dots = null;
            $userCcdots = null;
            $userDots = [];

            if ($hasDots) {
                $queryUserCCDots = EmployeeQueries::getRoleUserCcDotsQuery();
                $queryUserDots = EmployeeQueries::getRoleUserDotsQuery();

                $userCcDots = collect(DB::select($queryUserCCDots, [$email]))->first();

                if (!empty($userCcDots)) {
                    $userDots = DB::select($queryUserDots, [$userCcDots->BP]);

                    $userCcdots = $userCcDots;
                }
                $dots = [
                    'user' => $userCcdots,
                    'role' => $userDots,
                ];
            }

            return response()->json(['toa' => $employee, 'dots' => $dots], 200);
        } catch (\Exception $e) {
            Log::error('Error in getRoleToa: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
