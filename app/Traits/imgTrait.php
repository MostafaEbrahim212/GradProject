<?php
namespace App\Traits;

use Illuminate\Support\Str;

trait imgTrait
{
    public function uploadImage($request, $path)
    {
        $image = $request->file('picture');
        $image_name = Str::random(10) . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/images/' . $path, $image_name);
        return $image_name;
    }
}
