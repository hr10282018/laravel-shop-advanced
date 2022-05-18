<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $fillable=['name','is_directory','level','path'];
  protected $casts=[
    'is_directory' =>'boolean'
  ];

  protected static function boot(){
    parent::boot();

    // 监听 Category的创建事件。用于初始化 path 和 level 字段值
    static::creating(function(Category $category){
      // 如果创建时一个根类目
      if(is_null($category->parent_id)){
        $category->level=0;     // 层级为 0

        $category->path='-';      // path 设为 -
      }else{
        $category->level=$category->parent->level+1;    // 层级为父类目的层级+1

        // 将 path 值设为父类目的 path 追加父类目 ID 及最后 - 字符
        $category->path=$category->parent->path.$category->parent_id.'-';
      }
    });

  }

  // 一个类目 只属于一个父类 (有许多祖先类)
  public function parent(){
    return $this->belongsTo(Category::class);
  }

  // 一个类目 有许多子类
  public function children(){
    return $this->hasMany(Category::class,'parent_id');
  }

  // 定义访问器，获取所有祖先类目的 ID
  public function getPathIdsAttribute(){

    // trim() - 去除字符两端的 -
    // explode() - 字符串转数组
    // array_filter() - 数组中空值移除

    return array_filter(explode('-',trim($this->path,'-')));
  }

  // 定义访问器，获取所有祖先类目并按层级排序
  public function getAncestorsAttribute(){
    return Category::query()
    ->whereIn('id',$this->path_ids)
    ->orderBy('level')
    ->get();
  }

  // 定义访问器，获取以 - 为分隔的所有祖先类目名称以及当前类目的名称
  public function getFullNameAttribute(){
    return $this->ancestors
    ->pluck('name')    // 取祖先类目的 name 字符作为一个数组
    ->push($this->name)   // 将当前类目的 name 字段加到数组末尾
    ->implode('-');     // 用 - 符号将数组值转成一个字符串
  }

}
