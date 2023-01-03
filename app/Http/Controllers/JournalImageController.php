<?php

namespace App\Http\Controllers;

use App\Models\JournalImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JournalImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function destroy($id)
    {
        $jvimage = JournalImage::findorfail($id);
        Storage::disk('uploads')->delete($jvimage->location);
        $jvimage->delete();
        return redirect()->back()->with('success', 'Image Removed Successfully');
    }
}
