<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Resources\Api\Blog\Admin\PostResource;
use App\Models\BlogPost;
use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;
use App\Http\Requests\BlogPostCreateRequest;
use App\Repositories\BlogPostRepository;
use App\Repositories\BlogCategoryRepository;
use App\Http\Requests\BlogPostUpdateRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;

class PostController extends BaseController
{
    use DispatchesJobs;

    public function __construct(
        private BlogPostRepository $blogPostRepository,
        private BlogCategoryRepository $blogCategoryRepository
    )
    {
        //parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $search = $request->input('search', null);
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'desc');

        $paginator = $this->blogPostRepository->getAllWithPaginate($perPage, $search, $sortBy, $sortOrder);

        return PostResource::collection($paginator);
    }

    /**
     * Store a newly created resource.
     */
    public function store(BlogPostCreateRequest $request)
    {
        $data = $request->all();

        if (empty($data['user_id'])) {
            $data['user_id'] = 1;
        }

        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['title']);
        }

        $item = (new BlogPost())->create($data);

        if ($item) {
            $job = new BlogPostAfterCreateJob($item);
            $this->dispatch($job);

            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        }

        return response()->json([
            'message' => 'Помилка збереження'
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) {
            return response()->json(['message' => "Запис id=[{$id}] не знайдено"], 404);
        }

        $item->load(['user', 'category']);
        return new PostResource($item);
    }

    /**
     * Update the specified resource.
     */
    public function update(BlogPostUpdateRequest $request, string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) {
            return ['message' => "Запис id=[{$id}] не знайдено"];
        }

        $data = $request->all();
        $result = $item->update($data);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return [
                'message' => 'Помилка збереження'
            ];
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(string $id)
    {
        $result = BlogPost::destroy($id);

        if ($result) {
            BlogPostAfterDeleteJob::dispatch($id)->delay(20);
            return [];
        } else {
            return [];
        }
    }
}
