<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use Validator;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
	    public function welcome()
    {
		return view('welcome');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::with('user')->latest()->paginate(4);
        return $articles;
        //return response()->json(['data' => $articles]);
    }

    public function articlesList($userid)
    {
        $articles = Article::where('user_id', $userid)->latest()->get();
        return $articles;
    }

    public function searchArticle($phrase)
    {
        $str = $phrase;
        if (strpos($str, "'") === false) {
            $word = $str;
        } else {
            $first = strpos($str, "'");
            $second = strpos($str, "'", $first + 1);
            $len = $second - $first - 1;
            $word = substr($str, $first + 1, $len);
        }

        $articles = Article::where('title', 'like', '%' . $word . '%')->get();
        $counter = 0;
        foreach ($articles as $article) {
            $counter++;
        }
        if ($counter != 0) {
            return $articles;
        } else {
			return 0;
            //return response()->json(['result' => 'نتیجه ای یافت نشد']);
        }
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:articles|max:50',
            'desc' => 'required|max:1000',
            'submitted' => 'required',
            'isfavorite' => 'required',
            'category_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response([
                'status' => 'error',
                'message' => $validator->errors()
            ], 300);
        }


        Article::create($request->all());
        return response()->json(['result' => 'مقاله مورد نظر با موفقیت ایجاد شد']);
    }

    public function upload($file)
    {
        $year = Carbon::now()->year;
        $image_path = "\upload\images";
        $filename = microtime() . '-' . $year . '-' . $file->getClientOriginalName();
        $file = $file->move(public_path($image_path), $filename);
        return $filename;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::with('category')->findOrFail($id);
        //        $cat = $article->category;

        if ($article) {
            return $article;
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
        $article = Article::findOrFail($id);
        // $file = $request->file('image');
        // if ($request->hasFile('image')) {
        // $image_url = $this->uploadImage($file);
        // } else {
        // $image_url = '';
        // }
        //$article->update(array_merge($request->all(), ['image' => $image_url]));
        $article->update($request->all());
        return response()->json(['result' => 'مقاله مورد نظر با موفقیت ویرایش شد']);
    }
	
	
    public function update_status(Request $request, $id)
    {
        $article = Article::findOrFail($id);
		$array = [];
        $article->update(array_merge($array, ['submitted' => $request->submitted]));
        return response()->json(['result' => 'مقاله مورد نظر با موفقیت ویرایش شد']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();
        return response()->json(['result' => 'مقاله مورد نظر با موفقیت حذف شد']);
    }
	
    public function bulk_destroy(Request $request)
    {
		 $counter = 0;
        $articleLength = 0;
		 foreach (json_decode($request['ids']) as $data => $value)
        {
            $counter++;
            $articles = Article::where('id', $value);
            $res = $articles->delete();
            if ($res){
                $articleLength++;
            }
        }
		if ($counter === $articleLength){
			return response()->json(['result' => 'مقاله مورد نظر با موفقیت حذف شد']);
        }else{
			return response()->json(['result' => 'error in delete']);
        }
    }
}
