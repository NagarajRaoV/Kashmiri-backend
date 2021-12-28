<?php


namespace App\Traits;


use App\HomepageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Webkul\API\Http\Resources\Catalog\Product as ProductResource;
use Webkul\Category\Models\Category;
use Webkul\Customer\Mail\VerificationEmail;
use Webkul\Product\Models\Product;

trait HasBlog
{

    public function getPages( Request $request){


    }

    public function GetCategory(Request $request){

        return $cat;
    }

    public function getSinglePage($slug, Request $request){
           $data = DB::table('cms_page_translations')->where('url_key','=', $slug)
           ->first();

           if ($data) {
               return $this->successResponse([
                   'page' => $data
               ]);
           } else {
               return $this->errorResponse('no data');
           }
    }

    public function getConfig(){
        $sliders = DB::table('sliders')->where('channel_id',1)->get();
        $inventory_sources = DB::table('inventory_sources')->get();
        $channel_translations = DB::table('channel_translations')->get();
        $attribute_options = DB::table('attribute_options')->where('attribute_id', 25)->get();

        $cat = Category::with('childs')->where('parent_id',1)->get();

        $home_settings = HomepageSetting::find(1);

        $pids = explode(",",$home_settings->productIds);
        try {
            foreach ($pids as $pid) {
                $products_link[] = new ProductResource(
                    $this->productRepository->where('sku', trim($pid))->first()
                );
            }
        } catch (\Exception $exception){
            $products_link[] = [];
        }

        return $this->successResponse([
            'images'    =>  $sliders,
            'inventory_sources'    =>  $inventory_sources,
            'channel_translations'    =>  $channel_translations,
            'brands'    =>  $attribute_options,
            'categories'    =>  $cat,
            'homepage'    =>  $home_settings,
            'products_link'    =>  $products_link,
        ]);
    }

    public function getAllDepCategories(Request $request){
        $cat = Category::with('childs')->where('parent_id',1)->get();
        return $this->successResponse([
            'categories'    => $cat
            ]);

    }


}