<?php

namespace App\Http\Controllers;

use App\Models\TextWidget;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function aboutUs() {
        $about = TextWidget::where('key', 'about-page')->where('active', 1)->first();
        if(!$about) {
            abort(404);
        }
        return view('about-us', compact('about'));
    }
}
