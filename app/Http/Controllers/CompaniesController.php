<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompaniesController extends Controller
{
    /**
     * Add new Company to the Companies table
     * @param Request $request
     * @return JsonResponse
     */
    public function addCompany(Request $request): JsonResponse
    {
        $insertResult = false;

        //Validate and filter inputs
        $validatedInputs = $this->validateNewCompanyRequest($request);

        //Try insert new Company to the Database
        try {
            $insertResult = Companies::insert($validatedInputs);
        } catch (Exception $e) {
            Log::error('Failed to write into the database: ' . $e->getMessage());
        }

        if ($insertResult) {
            return response()->json(["message" => "New Company added"]);
        } else {
            return response()->json(["error" => "New Company add failed"], 500);
        }
    }

    /**
     * Get data from Companies by array of companyIds.
     * @param Request $request
     * @return JsonResponse
     */
    public function getCompanies(Request $request): JsonResponse
    {
        //Validate and filter inputs
        $validatedInputs = $this->validateGetCompaniesRequest($request);

        $companyIds = $validatedInputs['ids'];

        try {
            //Get searched companies from Companies table
            $companyCollections = Companies::whereIn('companyId', $companyIds)->get();
        } catch (Exception $e) {
            $errorMsg = 'Failed to get Companies from database.';

            Log::error($errorMsg . ' Error: ' . $e->getMessage());

            return response()->json(['error' => $errorMsg], 500);
        }

        return response()->json($companyCollections);

    }

    /**
     * Update Company information
     * @param Request $request
     * @param $companyId
     * @return JsonResponse
     */
    public function updateCompany(Request $request, $companyId): JsonResponse
    {
        //Validate CompanyID
        try {
            $company = Companies::findOrFail($companyId);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Company ID not exist'], 404);
        }

        //Validate and filter inputs
        $validatedInputs = $this->validateUpdateCompaniesRequest($request);

        //Try to Update Company
        try{
            $company->update($validatedInputs);
        }catch(QueryException $e){
            Log::error('Failed to update the company with ID \''.$companyId.'\'. Error: '.$e->getMessage());
            return response()->json(['error' => 'Update failed'],500);
        }

        return response()->json(['message' => 'Update success']);
    }

    /**
     * Validate POST Request for addNewCompany function.
     * @param Request $request
     * @return array
     */
    private function validateNewCompanyRequest(Request $request): array
    {
        $rules = [
            'companyName' => 'string|required',
            'companyRegistrationNumber' => 'string|required|unique:companies',
            'companyFoundationDate' => 'date|required',
            'country' => 'string|required',
            'zipCode' => 'string|required',
            'city' => 'string|required',
            'streetAddress' => 'string|required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'companyOwner' => 'string|required',
            'employes' => 'required|numeric',
            'activity' => 'string|required',
            'active' => 'required|boolean',
            'email' => 'required|email',
            'password' => 'string|required'
        ];

        return $request->validate($rules);
    }

    /**
     * Validate GET Request for GetCompanies function.
     * @param Request $request
     * @return array
     */
    private function validateGetCompaniesRequest(Request $request): array
    {
        $rules = [
            'ids' => ['array', 'required']
        ];

        return $request->validate($rules);
    }

    /**
     * Validate PATCH Request for UpdateCompanies function.
     * @param Request $request
     * @return array
     */
    private function validateUpdateCompaniesRequest(Request $request): array
    {


        $rules = [
            'companyName' => 'nullable|string',
            'companyRegistrationNumber' => 'nullable|string|unique:companies',
            'country' => 'nullable|string',
            'zipCode' => 'nullable|string',
            'city' => 'nullable|string',
            'streetAddress' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'companyOwner' => 'nullable|string',
            'employes' => 'nullable|numeric',
            'activity' => 'nullable|string',
            'active' => 'nullable|boolean',
            'email' => 'nullable|email',
            'password' => 'nullable|string'
        ];

        return $request->validate($rules);
    }
}
