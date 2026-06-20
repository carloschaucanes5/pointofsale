<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class HomeController extends Controller
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
    public function index(Request $request)
    {
        $category = $request->category ? $request->category : 12;
        //dd($category);
        // Fecha actual
        $fechaFin = Carbon::now();
        // Tres meses atrás
        $fechaInicio = Carbon::now()->subMonths(3);
        $perPage = $request->get('per_page', 100);
        $productos = DB::table('income_detail_historical as inch')

            ->leftJoin('income_detail as incd', function ($join) {

                $join->on('incd.id', '=', 'inch.income_detail_id');

            })

            ->join('product as p', 'inch.product_id', '=', 'p.id')

            ->join('category as ca', 'p.category_id', '=', 'ca.id')

            ->select(
                'inch.product_id',
                'p.code',
                'p.name',
                'p.laboratory',

                DB::raw('SUM(COALESCE(incd.quantity,0)) as existencias'),

                DB::raw('MAX(inch.created_at) as ultima_fecha')
            )

            ->whereBetween('inch.created_at', [
                $fechaInicio,
                $fechaFin
            ])

            ->when($category, function ($query) use ($category) {

                $query->where('ca.id', '=', $category);

            })

            ->groupBy(
                'inch.product_id',
                'p.code',
                'p.name',
                'p.laboratory'
            )

            ->having('existencias', '<=', 10)

            ->orderByDesc('ultima_fecha')

            ->paginate($perPage);
        $categories = DB::table('category')->get();
        return view('home', ["products" => $productos, "categories" => $categories, "category" => $category]);
    }
}
