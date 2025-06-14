<?php
namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;

class CategoryService {
    protected $categoryRepository;
    
    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository(app());
    }
    
    public function updateStatus($id, $newStatus) {
        $category = $this->categoryRepository->where('id', $id)->first();
        $category->status = $newStatus;
        $category->save();
        return $category;
    }
    
    public function update($id, $data = []) {
        $category = $this->categoryRepository->where('id', $id)->first();
        $category->update($data);
        return $category;
    }
    
    public function create($data = []) {
        if(count($data) <= 0) {
            return false;
        }
        
        $dataCreate = [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'image_avatar' => $data['image_avatar'] ?? null,
            'slug' => $data['slug'],
            'status' => $data['status'] ?? 0 ,
            'parent_id' => $data['parent_id'] ?? null,
        ];
        
        return $this->categoryRepository->create($dataCreate);
    }
    
    public function delete($id) {
        return $this->categoryRepository->delete($id);
    }
}