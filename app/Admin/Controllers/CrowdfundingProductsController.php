<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Models\CrowdfundingProduct;
use App\Models\Product;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CrowdfundingProductsController extends CommonProductsController
{
  public function getProductType()
  {
    return Product::TYPE_CROWDFUNDING;
  }

  protected $title = '众筹商品';

  protected function customGrid(Grid $grid)
  {
    $grid->id('ID')->sortable();
    $grid->title('商品名称');
    $grid->on_sale('已上架')->display(function ($value) {
      return $value ? '是' : '否';
    });
    $grid->price('价格');
    $grid->column('crowdfunding.target_amount', '目标金额');
    $grid->column('crowdfunding.end_at', '结束时间');
    $grid->column('crowdfunding.total_amount', '目前金额');
    $grid->column('crowdfunding.status', ' 状态')->display(function ($value) {
      return CrowdfundingProduct::$statusMap[$value];
    });
  }

  protected function customForm(Form $form)
  {
    // 众筹相关字段
    $form->text('crowdfunding.target_amount', '众筹目标金额')->rules('required|numeric|min:0.01');
    $form->datetime('crowdfunding.end_at', '众筹结束时间')->rules('required|date');
  }

  // protected function grid()
  // {
  //   $grid = new Grid(new Product());

  //   // 只展示type 为众筹类型的商品
  //   $grid->model()->where('type',Product::TYPE_CROWDFUNDING);

  //   $grid->id('ID');

  //   $grid->title('商品名称');
  //   $grid->on_sale('已上架')->display(function($value){
  //     return $value ? '是':'否';
  //   });

  //   $grid->price('价格');

  //   // 展示众筹相关字段
  //   $grid->column('crowdfunding.target_amount','目标金额');
  //   $grid->column('crowdfunding.end_at','结束时间');
  //   $grid->column('crowdfunding.total_amount', '目前金额');
  //   $grid->column('crowdfunding.status', ' 状态')->display(function ($value) {
  //     return CrowdfundingProduct::$statusMap[$value];
  //   });

  //   $grid->actions(function($actions){

  //     $actions->disableView();
  //     $actions->disableDelete();
  //   });

  //   $grid->tools(function($tools){
  //     $tools->batch(function($batch){
  //       $batch->disableDelete();
  //     });
  //   });

  //   return $grid;
  // }


  // protected function detail($id)
  // {
  //   $show = new Show(Product::findOrFail($id));

  //   $show->field('id', __('Id'));
  //   $show->field('type', __('Type'));
  //   $show->field('category_id', __('Category id'));
  //   $show->field('title', __('Title'));
  //   $show->field('description', __('Description'));
  //   $show->field('image', __('Image'));
  //   $show->field('on_sale', __('On sale'));
  //   $show->field('rating', __('Rating'));
  //   $show->field('sold_count', __('Sold count'));
  //   $show->field('review_count', __('Review count'));
  //   $show->field('price', __('Price'));
  //   $show->field('created_at', __('Created at'));
  //   $show->field('updated_at', __('Updated at'));

  //   return $show;
  // }


  // protected function form()
  // {
  //   $form = new Form(new Product());

  //   // 在表单中添加一个名为type，值为Product::TYPE_ 的隐藏字段
  //   $form->hidden('type')->value(Product::TYPE_CROWDFUNDING);

  //   $form->text('title', '商品名称')->rules('required');

  //   $form->select('category_id', '类目')->options(function ($id) {
  //     $category = Category::find($id);
  //     if ($category) {
  //       return [$category->id => $category->full_name];
  //     }
  //   })->ajax('/admin/api/categories?is_directory=0');

  //   $form->image('image', '封面图片')->rules('required|image');

  //   $form->quill('description', '商品描述')->rules('required');

  //   $form->radio('on_sale', '上架')->options(['1' => '是', '0' => '否'])->default('0');

  //   // 添加众筹相关字段
  //   $form->text('crowdfunding.target_amount', '众筹目标金额')->rules('required|numeric|min:0.01');
  //   $form->datetime('crowdfunding.end_at', '众筹结束时间')->rules('required|date');
  //   // 一个商品有多个skus (一对多 关系处理)
  //   $form->hasMany('skus', '商品skus', function (Form\NestedForm $form) {
  //     $form->text('title', 'SKU 名称')->rules('required');
  //     $form->text('description', 'SKU 描述')->rules('required');
  //     $form->text('price', '单价')->rules('required|numeric|min:0.01');
  //     $form->text('stock', '剩余库存')->rules('required|integer|min:0');
  //   });
  //   $form->saving(function (Form $form) {

  //     $form->model()->price = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->min('price');
  //   });

  //   return $form;
  // }
}
