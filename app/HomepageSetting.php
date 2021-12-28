<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'bannerImage',

        'productIds',
        'box1',
        'box2',
        'box3',
        'box4',
        'box5',

        'url1',
        'url2',
        'url3',
        'url4',
        'url5',

        'file1',
        'file2',
        'file3',
        'file4',
        'file5',
        'linkcallout',
        'headerTitle',
        'subTitle',


    ];

    protected $casts = [
        'bannerImage'   =>  'array',
        'productIds'   =>  'array',
        'file1'   =>  'array',
        'file2'   =>  'array',
        'file3'   =>  'array',
        'file4'   =>  'array',
        'file5'   =>  'array',
    ];
}
