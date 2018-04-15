<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $rules = [
        'name' => 'required',
        'volume' => 'required',
        'price' => 'required',
    ];

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
     * Returns the categories page
     * @return view categories page blade
     */
    public function page()
    {
        return view('category.page');    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->wantsJson())
        {
            // Ryan 09022018 If this is an internal API request
            return Controller::VueTableListResult(Category::with('lots')->where('status', 'true'));
        }

        $categories = category::where('status', 'true')->get();

        return view('category.index')->with('categories', $categories);
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
    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $category = new category;
        $category->name = $request->name;
        $category->volume = $request->volume;
        $category->price = $request->price;
        $category->status = 'true';
        $category->save();

        if(request()->wantsJson())
        {   
            return ["message" => $category->name . " created successfully."];
        }
        return redirect()->back()->withSuccess($category->name . " created successfully.");
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, $this->rules);

        $category = category::find($request->id);
        $category->name = $request->name;
        $category->volume = $request->volume;
        $category->price = $request->price;
        $category->save();

        if(request()->wantsJson())
        {   
            return ["message" => "Category " . $category->name . " edited successfully."];
        }

        return redirect()->back()->withSuccess($category->name . ' updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = category::find($id);
        $category->status = "false";
        $category->save();

        return redirect()->back()->withSuccess($category->name . ' deleted successfully.');
    }
}
