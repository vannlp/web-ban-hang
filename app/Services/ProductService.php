<?php
namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService {
    protected $productRepository;
    
    public function __construct()
    {
        $this->productRepository = new ProductRepository(app());
    }
    
    public function create($data = []) {
        $dataCreate = [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'] ?? 0,
            'original_price' => $data['original_price'] ?? null,
            'image_avatar' => $data['image_avatar'] ?? null,
            'short_description' => $data['short_description'] ?? null,
            'list_image' => isset($data['list_image']) ? json_encode($data['list_image']) : null,
            'status' => $data['status'] ?? 0 ,
            'bard_id' => $data['bard_id'] ?? null,
        ];
        
        
        return $this->productRepository->create($dataCreate);
    }
}