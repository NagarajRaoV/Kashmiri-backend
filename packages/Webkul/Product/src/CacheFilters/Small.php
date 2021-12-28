<?php

namespace Webkul\Product\CacheFilters;

use Illuminate\Support\Facades\Log;
use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Small implements FilterInterface
{
    /**
     * @param  \Intervention\Image\Image  $image
     * @return \Intervention\Image\Image
     */
    public function applyFilter(Image $image)
    {
        try {


        $width = (int)core()->getConfigData('catalog.products.cache-small-image.width') ?? 120;
        $height = (int)core()->getConfigData('catalog.products.cache-small-image.height') ?? 120;

        $image->resize(120, 120, function ($constraint) {
            $constraint->aspectRatio();
        });

        return $image->resizeCanvas(120, 120, 'center', false, '#fff');
        } catch (\Exception $exception){
            Log::alert($exception->getMessage());
            return $image->resizeCanvas(120, 120, 'center', false, '#fff');
        }
    }
}