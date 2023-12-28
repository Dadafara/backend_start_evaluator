<?php

namespace App\Http\Controllers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Display a listing of company.
     * @return Response
     */
    public function index()
    {
        $company = Company::select(
                'company.id',
                'url',
                'companyName',
                'lastName',
                'firstName',
                'email',
                'country',
                'contact',
                'category',
                'password' 
            )
            ->get();

        return response()->json($company);
    }

    /**
     * Store a newly created company.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $company = new Company([
            'url' => $request->input('url'),
            'companyName' => $request->input('companyName'),
            'lastName' => $request->input('lastName'),
            'firstName' => $request->input('firstName'),
            'email' => $request->input('email'),
            'country' => $request->input('country'),
            'contact' => $request->input('contact'),
            'category' => $request->input('category'),
            'password' => $request->input('password'),
        ]);
        $company->save();
        return response()->json('Company created!');
    }

}
