<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Carbon\Carbon;

class DiggingDeeperController extends Controller
{
    /**
     * Базова інформація
     * @url https://laravel.com/docs/13.x/collections#introduction
     *
     * Довідкова інформація
     * @url https://laravel.com/api/13.x/Illuminate/Support/Collection.html
     *
     * Варіант колекції для моделі eloquent
     * @url https://laravel.com/api/13.x/Illuminate/Database/Eloquent/Collection.html
     *
     */

    public function collections()
    {
        $result = [];

        /**
         * @var \Illuminate\Database\Eloquent\Collection $eloquentCollection
         */
        $eloquentCollection = BlogPost::withTrashed()->get();

        // dd(__METHOD__, $eloquentCollection, $eloquentCollection->toArray());

        /**
         * @var \Illuminate\Support\Collection $collection
         */
        $collection = collect($eloquentCollection->toArray());

        /*
        dd(
            get_class($eloquentCollection),
            get_class($collection),
            $collection
        );
        */

        $result['first'] = $collection->first();

        $result['last'] = $collection->last();

        $result['where']['data'] = $collection
            ->where('category_id', 10)
            ->values()
            ->keyBy('id');

        $result['where']['count'] = $result['where']['data']->count();

        $result['where']['isEmpty'] = $result['where']['data']->isEmpty();

        $result['where']['isNotEmpty'] = $result['where']['data']->isNotEmpty();

        if ($result['where']['data']->isNotEmpty()) {
            //
        }

        $result['where_first'] = $collection
            ->firstWhere('created_at', '>', '2020-02-24 03:46:16');

        // map()
        $result['map']['all'] = $collection->map(function ($item) {

            $newItem = new \stdClass();

            $newItem->item_id = $item['id'];

            $newItem->item_name = $item['title'];

            $newItem->exists = is_null($item['deleted_at']);

            return $newItem;
        });

        $result['map']['not_exists'] = $result['map']['all']
            ->where('exists', '=', false)
            ->values()
            ->keyBy('item_id');

        // dd($result);

        // transform()
        $collection->transform(function ($item) {

            $newItem = new \stdClass();

            $newItem->item_id = $item['id'];

            $newItem->item_name = $item['title'];

            $newItem->exists = is_null($item['deleted_at']);

            $newItem->created_at = Carbon::parse($item['created_at']);

            return $newItem;
        });

        // dd($collection);

        // Додаємо нові елементи
        $newItem = new \stdClass();
        $newItem->id = 9999;

        $newItem2 = new \stdClass();
        $newItem2->id = 8888;

        // dd($newItem, $newItem2);

        // prepend / push / pull
        $newItemFirst = $collection->prepend($newItem)->first();

        $newItemLast = $collection->push($newItem2)->last();

        $pulledItem = $collection->pull(1);

        /*
        dd(compact(
            'collection',
            'newItemFirst',
            'newItemLast',
            'pulledItem'
        ));
        */

        // Фільтрація
        $filtered = $collection->filter(function ($item) {

            // перевірка щоб не було помилки
            if (!isset($item->created_at)) {
                return false;
            }

            $byDay = $item->created_at->isFriday();

            $byDate = $item->created_at->day == 11;

            return $byDay && $byDate;
        });

        // dd(compact('filtered'));

        // Сортування
        $sortedSimpleCollection = collect([5, 3, 1, 2, 4])
            ->sort()
            ->values();

        $sortedAscCollection = $collection->sortBy('created_at');

        $sortedDescCollection = $collection->sortByDesc('item_id');

        dd(compact(
            'filtered',
            'sortedSimpleCollection',
            'sortedAscCollection',
            'sortedDescCollection'
        ));
    }
}
