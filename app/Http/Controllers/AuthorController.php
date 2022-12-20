<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 *
 */
class AuthorController extends Controller
{
    use ApiResponser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $authors = Author::all();
        return $this->successResponse($authors);
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'gender' => 'required|max:255|in:male,female',
            'country' => 'required|max:255',
        ];
        $this->validate($request, $rules);
        $author = Author::insert($request->json()->all());
        return $this->successResponse($author, Response::HTTP_CREATED);
    }

    /**
     * @param $author
     */
    public function show($author)
    {
        $author = Author::findOrFail($author);
        return $this->successResponse($author);
    }

    /**
     * @param Request $request
     * @param $author
     */
    public function update(Request $request, $author)
    {
        $rules = [
            'name' => 'max:255',
            'gender' => 'in:male,female',
            'country' => 'max:255',
        ];
        $this->validate($request, $rules);
        $author = Author::findOrFail($author);
        $author->fill($request->all());
        if ($author->isClean()) {
            return $this->errorResponse('Al menos un valor debe cambiar', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $author->save();
        return $this->successResponse($author);
    }

    /**
     * @param $author
     */
    public function destroy($author)
    {
        $author = Author::findOrFail($author);
        $author->delete();
        return $this->successResponse($author);
    }
}
