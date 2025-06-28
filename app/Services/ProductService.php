<?php
namespace App\Services;

use App\Models\CategoryProduct;
use App\Repositories\ProductRepository;

class ProductService {
    protected $productRepository;
    
    protected $product = null;
    
    public function __construct()
    {
        $this->productRepository = new ProductRepository(app());
    }
    
    public function getProduct($id = null) {
        if(!$this->product) {
            $this->product = $this->productRepository->findOrFail($id);
        }
        
        return $this->product;
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
            'slug' => $data['slug'],
        ];
        
        $product = $this->productRepository->create($dataCreate);
        
        if(isset($data['listCategory']) && count($data['listCategory']) > 0) {
            $product_id = $product->id;
            
            foreach($data['listCategory'] as $item) {
                $category_id = $item;
                $dataPC = [
                    'product_id' => $product_id,
                    'category_id' => $category_id,
                ];
                $this->createOrUpdateCategoryProduct(null, $dataPC);
            }
        }
        
        return $this->getProduct($product->id);
    }
    
    public function createOrUpdateCategoryProduct($productCategory = null, $data) {
        $category_id = $data['category_id'];
        $product_id = $data['product_id'];
        
        if(!$productCategory) {
            $result = CategoryProduct::create([
                'product_id' => $product_id,
                'category_id' => $category_id,
            ]);
            
            return $result;
        }
        
        
        $productCategory->product_id = $product_id;
        $productCategory->category_id = $category_id;
        $productCategory->save();
        
        return $productCategory;
    }
    
    public function getProductEdit($id, $input = []) {
        $query = $this->productRepository->query();
        
        if(!empty($input['getCategory'])) {
            $query->with('categories');
        }
        
        $product = $query->findOrFail($id);
        
        if(empty($product)) {
            return null;
        }
        
        $product->list_image = json_decode($product->list_image);
        if(!empty($product->categories)) {
            $product->listCategory = array_map(function ($item) {
                return $item['id'];
            }, $product->categories->toArray());
        }
        
        return $product;
    }
    
    public function update($id ,$data = []) {
        $this->getProduct($id);
        
        if(!$this->product) {
            return null;
        }
        
        $dataUpdate = [
            'name' => $data['name'] ?? $this->product->name,
            'description' => $data['description'] ?? $this->product->description,
            'price' => $data['price'] ?? $this->product->price,
            'original_price' => $data['original_price'] ?? $this->product->original_price,
            'image_avatar' => $data['image_avatar'] ?? $this->product->image_avatar,
            'short_description' => $data['short_description'] ?? $this->product->short_description,
            'list_image' => isset($data['list_image']) ? json_encode($data['list_image']) : $this->product->list_image,
            'status' => $data['status'] ?? $this->product->status ,
            'bard_id' => $data['bard_id'] ?? $this->product->bard_id,
            'slug' => $data['slug'] ?? $this->product->slug,
        ];
        
        $this->product->update($dataUpdate);
        
        if(isset($data['listCategory']) && count($data['listCategory']) > 0) {
            $product_id = $this->product->id;
            $listProductCategory = CategoryProduct::where('product_id', $product_id)->get();
            
            foreach($data['listCategory'] as $item) {
                $category_id = $item;
                $PT = $listProductCategory->where('category_id', $category_id)->first();
                $dataPC = [
                    'product_id' => $product_id,
                    'category_id' => $category_id,
                ];
                $this->createOrUpdateCategoryProduct($PT, $dataPC);
            }
        }
        
        return $this->product;
    }
    
    public function delete($id) {
        return $this->productRepository->delete($id);
    }
}