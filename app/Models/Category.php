<?php

namespace App\Models;

use App\Traits\Multicompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory, SoftDeletes, Multicompany;

    protected $fillable = ['company_id', 'branch_id', 'category_name', 'category_code', 'category_image', 'in_order', 'category_id'];

    protected const CACHE_PRODUCT_LIST = "categories";

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function prods(){
        return $this->hasMany(Product::class, 'category_id')->select(['id','total_cost','primary_unit','has_serial_number','product_name','product_code']);
    }

    public function childrenCategories()
    {
        return $this->hasMany(Category::class,'category_id')->with('categories:id,category_name,category_code,category_image,category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    protected static $relations_to_cascade = ['products'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($resource) {
            foreach (static::$relations_to_cascade as $relation) {
                foreach ($resource->{$relation}()->get() as $item) {
                    $item->delete();
                }
            }
        });
    }

    public function forgetCategoryCacheList(): bool
    {
        return Cache::forget(self::CACHE_PRODUCT_LIST);
    }

    public function updateCategoryCacheListById(Category $category): bool
    {
        $categories = collect($this->getAll());

        $filtered = $categories->reject(function ($value, $key) use ($category) {
            return $value['id'] == $category->id;
        });

        $filtered->combine($category);

        return Cache::set(self::CACHE_PRODUCT_LIST, $filtered->all());
    }

    public function deleteCategoryCacheListById(Category $category): bool
    {
        $categories = collect($this->getAll());

        $filtered = $categories->reject(function ($value, $key) use ($category) {
            return $value['id'] == $category->id;
        });

        return Cache::set(self::CACHE_PRODUCT_LIST, $filtered->all());
    }

    public function getAll()
    {
        // return Cache::rememberForever(self::CACHE_PRODUCT_LIST, function () {
        return $this->selectRaw('
        id,
        category_name,
        category_code,
        category_image,
        category_id
        ')->whereNull('category_id')->with('childrenCategories:id,category_name,category_code,category_image,category_id')->get();
        // });
    }
}
