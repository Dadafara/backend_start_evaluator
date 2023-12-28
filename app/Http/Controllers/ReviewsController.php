<?php

namespace App\Http\Controllers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Reviews;
use App\Models\UserSimple;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


use Illuminate\View\View;

class ReviewsController extends Controller
{
      /**
      * Display a listing of the resource.
      */
     public function index(): View
     {
        $reviews = Reviews::with(['user', 'company'])

        ->join('company', 'reviews.company_id', '=', 'company.id')
        ->select('reviews.*', 'company.companyName as companyName')
        ->join('user', 'reviews.user_id', '=', 'user.id')
        ->select('reviews.*', 'user.name as name')
        ->latest()
        ->paginate(10);

    return view('reviews.index', compact('reviews'))
        ->with('i', (request()->input('page', 1) - 1) * 10); 

     }
 
     /**
      * Show the form for creating a new resource.
      */
     public function create(): View
     {
        $user = UserSimple::all();
        $company = Company::all();

        return view('reviews.create', compact('user', 'company'));
     }
 
     /**
      * Store a newly created resource in storage.
      */
 
     public function store(Request $request): RedirectResponse
     {
         $request->validate([
             'note' => 'required',
             'avis' => 'required',
             'user_id' => 'required',
             'dateTime' => 'required',
             'company_id' => 'required',
         ]);
 
         Reviews::create($request->all());
         return redirect()->route('reviews.index')
                         ->with('success','reviews created successfully.');
 
     }
 
     /**
      * Remove the specified resource from storage.
      */
 
      public function destroy($id): RedirectResponse
      {
          $review = Reviews::find($id);
      
          if (!$review) {
              return redirect()->back()->with('error', 'Review not found');
          }
      
          $review->delete();
          
          return redirect()->back()->with('success', 'Review deleted successfully');
      }      
      
    public function show($id)
        {
            $reviews = Reviews::find($id);
            return response()->json($reviews);
        }

    public function search(Request $request): View
    {
        try {
            $searchTerm = $request->input('search');

            $reviews = Reviews::where('note', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('avis', 'LIKE', '%' . $searchTerm . '%')
                ->orWhereHas('user', function ($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orWhere('dateTime', 'LIKE', '%' . $searchTerm . '%')
                ->orWhereHas('company', function ($query) use ($searchTerm) {
                    $query->where('companyName', 'LIKE', '%' . $searchTerm . '%');
                })
                ->get();
                
            return view('reviews.index', compact('reviews'));

        } 
        catch (error) {

            \Log::info('Search Results: ' . json_encode($reviews));
            console.error('Erreur lors de l\'analyse JSON :', error);
        }
    }


    public function getReviews()
    {
        try {
            // Récupérez la liste des noms d'entreprise depuis la base de données
            $companyNames = Company::pluck('companyName')->toArray();
           
            $name = 'esperance';
    
            // Enregistrez des avis statiques pour chaque entreprise
            foreach ($companyNames as $companyName) {
                Reviews::create([
                    'company_id' => Company::where('companyName', $companyName)->value('id'),
                    'avis' => 'Avis pour ' . $companyName . ': Excellent service.',
                    'note' => '5',
                    'dateTime' => '26/12/2023',
                    'user_id' => UserSimple::where('name', $name)->value('id'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
    
                Reviews::create([
                    'company_id' => Company::where('companyName', $companyName)->value('id'),
                    'avis' => 'Avis pour ' . $companyName . ': Produits de haute qualité.',
                    'note' => '4',
                    'dateTime' => '26/12/2023',
                    'user_id' => UserSimple::where('name', $name)->value('id'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
    
            return response()->json(['success' => 'Avis enregistrés avec succès'], 200);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'enregistrement des avis : ' . $e->getMessage());
            return response()->json(['error' => 'Erreur interne du serveur'], 500);
        }
    }
        
    public function fetchReviews()
    {
        try {
            $reviews = Reviews::all();
            $groupedReviews = [];

            foreach ($reviews as $reviews) {
                $companyName = $reviews->company->companyName;
                $createdAt = $reviews->created_at;

                // Vérifier si la critique a été créée il y a plus d'une semaine
                //if ($createdAt->diffInDays(now()) > 7)
                if ($createdAt->diffInDays(now()) > 4) {
                    if (!isset($groupedReviews[$companyName])) {
                        $groupedReviews[$companyName] = [];
                    }

                    $groupedReviews[$companyName][] = [
                        'note' => $reviews->note,
                        'avis' => $reviews->avis,
                        'created_at' => $createdAt,
                    ];
                }
            }

            return response()->json(['groupedReviews' => $groupedReviews], 200);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des avis : ' . $e->getMessage());
            return response()->json(['error' => 'Erreur interne du serveur'], 500);
        }
    }

}
