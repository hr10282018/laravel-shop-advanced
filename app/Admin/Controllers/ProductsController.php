<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Models\Product;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductsController extends AdminController
{

  protected $title = '商品';


  protected function grid()
  {
    $grid = new Grid(new Product());

    // 使用 with 预加载商品类目数据，减少SQL查询
    //$grid->model()->with(['category']);
    $grid->model()->where('type',Product::TYPE_NORMAL)->with(['category']);   // 取普通类商品

    $grid->column('id', 'ID')->sortable();
    $grid->column('title', '商品名称');

    // Laravel-Admin 支持用符号 . 展示关联关系的字段
    $grid->column('category.name','类目');

    $grid->column('on_sale', __('已上架'))->display(function ($value) {
      return $value ? '是' : '否';
    });;
    $grid->column('price', __('价格'));

    $grid->column('rating', __('评分'));
    $grid->column('sold_count', __('销量'));
    $grid->column('review_count', __('评论数'));

    $grid->actions(function ($actions) {
      $actions->disableView();
      $actions->disableDelete();
    });
    $grid->tools(function ($tools) {
      // 禁用批量删除按钮
      $tools->batch(function ($batch) {
        $batch->disableDelete();
      });
    });

    return $grid;
  }


  // protected function detail($id)
  // {
  //   $show = new Show(Product::findOrFail($id));

  //   $show->field('id', __('Id'));
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


  // 编辑或添加商品
  protected function form()
  {
    $form = new Form(new Product());

    // 在表单中添加一个名为 type，值为 Product::TYPE_NORMAL 的隐藏字段
    $form->hidden('type')->value(Product::TYPE_NORMAL);

    // 创建一个输入框，第一个参数 title 是模型的字段名，第二个参数是该字段描述
    $form->text('title', '商品名称')->rules('required');  // rules()方法可以定义对应字段在提交时的校验规则，验证规则与 Laravel 的验证规则一致

    // 添加一个类目字段，与之前类目管理类似，使用Ajax 方式搜索添加
    $form->select('category_id','类目')->options(function($id){
      $category=Category::find($id);
      if($category){
        return [$category->id => $category->full_name];
      }
    })->ajax('/admin/api/categories?is_directory=0');

    
    // 创建一个选择图片的框
    $form->image('image', '封面图片')->rules('required|image');

    // 创建一个富文本编辑器
    $form->quill('description', '商品描述')->rules('required');

    // 创建一组单选框-options表示选项
    $form->radio('on_sale', '上架')->options(['1' => '是', '0' => '否'])->default('0');

    // 直接添加一对多的关联模型
    /*
      skus-第一个参数必须和主模型中定义此关联关系的方法同名(之前在 App\Models\Product 类中定义了skus()方法来关联SKU)
      第三个参数是一个匿名函数，用来定义关联模型的字段
    */
    $form->hasMany('skus', 'SKU 列表', function (Form\NestedForm $form) {
      $form->text('title', 'SKU 名称')->rules('required');
      $form->text('description', 'SKU 描述')->rules('required');
      $form->text('price', '单价')->rules('required|numeric|min:0.01');
      $form->text('stock', '剩余库存')->rules('required|integer|min:0');
    });

    // 定义事件回调，当模型即将保存时会触发这个回调
    $form->saving(function (Form $form) {
      // collect() 函数是 Laravel 提供的一个辅助函数，可以快速创建一个 Collection 对象
      // 这里将用户提交的SKU数据放到Collection中，用min方法求出所有SKU的最低price
      //where(Form::REMOVE_FLAG_NAME, 0)，需要判断_remove_是否为0，(否则编辑保存等于无效-SKU不变)
      $form->model()->price = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->min('price') ?: 0;
    });

    return $form;
  }
}

