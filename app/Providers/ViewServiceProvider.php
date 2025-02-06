<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Chia sẻ danh mục với tất cả các view
        View::composer('*', function ($view) {
            $view->with('categories', Category::all());  // Chỉnh sửa lại theo tên model bạn đang sử dụng
        });
    }
}