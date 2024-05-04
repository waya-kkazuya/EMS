<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;


class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $items = Item::with('category')
        ->searchItems($request->search)
        ->select(
            'id',
            'name',
            'category_id',
            'image_path1',
            'image_path2',
            'image_path3',
            'stocks',
            'usage_status',
            'end_user',
            'location_of_use',
            'storage_location',
            'acquisition_category',
            'price',
            'date_of_acquisition',
            'inspection_schedule',
            'disposal_schedule',
            'manufacturer',
            'product_number',
            'vendor',
            'vendor_website_url',
            'remarks',
            'qrcode_path'
        )->paginate(20);
        
        // dd($items);

        return Inertia::render('Items/Index', [
            'items' => $items
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $categories = Category::all();

        return Inertia::render('Items/Create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        Item::create([
            'id' => $request->id,
            'name' => $request->name,
            'category_id' => $request->category_id ,
            'image_path1' => $request->image_path1,
            'image_path2' => $request->image_path2,
            'image_path3' => $request->image_path3,
            'stocks' => $request->stocks,
            'usage_status' => $request->usage_status,
            'end_user' => $request->end_user,
            'location_of_use' => $request->location_of_use,
            'storage_location' => $request->storage_location,
            'acquisition_category' => $request->acquisition_category,
            'price' => $request->price,
            'date_of_acquisition' => $request->date_of_acquisition,
            'inspection_schedule' => $request->inspection_schedule,
            'disposal_schedule' => $request->disposal_schedule,
            'manufacturer' => $request->manufacturer,
            'product_number' => $request->product_number,
            'vendor' => $request->vendor,
            'vendor_website_url' => $request->vendor_website_url,
            'remarks' => $request->remarks,
            'qrcode_path' => $request->qrcode_path
        ]);

        return to_route('items.index')
        ->with([
            'message' => '登録しました。',
            'status' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        // dd($item);
        // categoryとのリレーションをロード
        $item_category = Item::with('category')->find($item->id);

        return Inertia::render('Items/Show', [
            'item' => $item_category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        // categoryとのリレーションをロード
        $item_category = Item::with('category')->find($item->id);
        $categories = Category::all();

        return Inertia::render('Items/Edit', [
            'item' => $item_category,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {      
        // dd($item->name, $request->name);
        $item->name = $request->name;
        $item->category_id = $request->category_id;
        $item->image_path1 = $request->image_path1;
        $item->image_path2 = $request->image_path2;
        $item->image_path3 = $request->image_path3;
        $item->usage_status = $request->usage_status;
        $item->end_user = $request->end_user;
        $item->location_of_use = $request->location_of_use;
        $item->acquisition_category = $request->acquisition_category;
        $item->price = $request->price;
        $item->date_of_acquisition = $request->date_of_acquisition;
        $item->inspection_schedule = $request->inspection_schedule;
        $item->disposal_schedule = $request->disposal_schedule;
        $item->manufacturer = $request->manufacturer;
        $item->product_number = $request->product_number;
        $item->vendor = $request->vendor;
        $item->vendor_website_url = $request->vendor_website_url;
        $item->remarks = $request->remarks;
        $item->qrcode_path = $request->qrcode_path;
        $item->save();

        return to_route('items.index')
        ->with([
            'message' => '更新しました。',
            'status' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return to_route('items.index')
        ->with([
            'message' => '削除しました。',
            'status' => 'danger'
        ]);
    }
}
