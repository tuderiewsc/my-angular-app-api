<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Foreach_;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with('getParent')->get();
        return $categories;
        //  return response()->json(['result' => $categories]);

    }
	
    public function headCats()
    {
        $cats = Category::where('parent_id', 0)->get();
        return $cats;
    }

    public function articleList($catid)
    {
        $cat = Category::find($catid);
        if ($cat->parent_id != 0) {
            $articles = Article::with('user')->where('category_id', $catid)->paginate(3);
        } else {
            $catChilds = Category::where('parent_id', $cat->id)->get();
            $array = array();
            $array[0] = $cat->id;
            foreach ($catChilds as $key => $value) {
                $array[$key + 1] = $value->id;
            }
            $articles = Article::with('user')->whereIn('category_id', $array)->paginate(3);
        }

        return $articles;
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
        $this->validate($request, [
            'name' => 'required|unique:categories',
            'parent_id' => 'required'
        ]);

        Category::create($request->all());
        return response()->json(['result' => 'دسته بندی مورد نظر با موفقیت ایجاد شد']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        if ($category) {
            return $category;
        } else {
            return abort('404');
        }
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
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
			'parent_id' => 'required'
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());
        return response()->json(['result' => 'دسته بندی مورد نظر با موفقیت ویرایش شد']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['result' => 'دسته بندی مورد نظر با موفقیت حذف شد']);
    }
}