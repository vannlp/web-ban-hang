<?php
namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends Repository {
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return Category::class;
    }
    
    public function getCategory($input = []) {
        $query = $this->model->query()
            ->select([
                'id',
                'name',
                'description',
                'image_avatar',
                'created_at',
                'updated_at',
                'slug',
                'status',
                'parent_id'
            ])
            ->where('status', 1);
        
        if(!empty($input['name'])) {
            $query->where('name', 'like', '%' . $input['name'] . '%');
        }  
    
        if(!empty($input['id'])) {
            $query->where('id', $input['id']);
        } 
        
        if(!empty($input['orListId'])) {
            $query->orWhereIn('id', $input['orListId']);
        } 
        
        if(!empty($input['slug'])) {
            $query->where('slug', $input['slug']);
        } 
            
        return $query;
    }
    
    public function getParentByClient($input = []) {
        return $this->getCategory($input)->where('parent_id', null);
    }
    
    public function getAllCol($input = []) {
        $query = $this->model->query()
            ->select(['categories.*']);
        
        if(!empty($input['id'])) {
            $query->where('id', $input['id']);
        }    
            
        if(!empty($input['name'])) {
            $query->where('name', 'like', '%' . $input['name'] . '%');
        }    
        
        if(!empty($input['slug'])) {
            $query->where('slug', $input['slug']);
        } 
            
        return $query;
    }

}