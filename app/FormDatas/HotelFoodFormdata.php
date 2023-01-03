<?php
namespace App\FormDatas;

class HotelFoodFormdata {

    public int $category_id;
    public int $kitchen_id;
    public string $food_name;
    public ?string $food_image;
    public ?string $component;
    public ?string $description;
    public ?string $cooking_time;
    public float $food_price;
    public bool $status;

    public function __construct(
        int $category_id,
        int $kitchen_id,
        string $food_name,
        ?string $food_image,
        ?string $component,
        ?string $description,
        ?string $cooking_time,
        float $food_price,
        bool $status
    )
    {
        $this->category_id = $category_id;
        $this->kitchen_id = $kitchen_id;
        $this->food_name = $food_name;
        $this->food_image = $food_image;
        $this->component = $component;
        $this->description = $description;
        $this->cooking_time = $cooking_time;
        $this->food_price = $food_price;
        $this->status = $status;
    }
}
