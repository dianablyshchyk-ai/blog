<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Resources\Api\Blog\Admin\CategoryResource;
use App\Models\BlogCategory;
use App\Repositories\BlogCategoryRepository;
use Illuminate\Support\Str;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    public function __construct(private BlogCategoryRepository $blogCategoryRepository)
    {
        //parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $search = $request->input('search', null);
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'desc');
        $paginator = $this->blogCategoryRepository->getAllWithPaginate($perPage, $search, $sortBy, $sortOrder);

        return CategoryResource::collection($paginator);
    }

    /**
     * Store a newly created resource.
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();
        $item = (new BlogCategory())->create($data);

        if ($item) {
            return [
                'success' => true,
                'message' => 'Успішно збережено',
            ];
        } else {
            return [
                'message' => 'Помилка збереження',
            ];
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource.
     */
    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);
        if (empty($item)) {
            return back()
                ->withErrors(['msg' => "Запис id=[{$id}] не знайдено"])
                ->withInput();
        }

        $data = $request->all();
        $result = $item->update($data);
        if ($result) {
            return [
                'success' => 'Успішно збережено'
            ];
        } else {
            return [
                'msg' => 'Помилка збереження'
            ];
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(string $id)
    {
        $item = BlogCategory::find($id);

        if (empty($item)) {
            return response()->json([
                'message' => "Запис id=[{$id}] не знайдено"
            ], 404);
        }

        $hasChildren = BlogCategory::where('parent_id', $id)->exists();
        if ($hasChildren) {
            return response()->json([
                'message' => 'Не можна видалити категорію, у якої є підкатегорії!'
            ], 400);
        }

        $result = $item->delete();

        if ($result) {
            return [
                'success' => true,
                'message' => 'Успішно видалено'
            ];
        } else {
            return response()->json([
                'message' => 'Помилка при видаленні'
            ], 500);
        }
    }
}
