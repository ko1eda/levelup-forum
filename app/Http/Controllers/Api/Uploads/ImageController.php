<?php

namespace App\Http\Controllers\Api\Uploads;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Intervention\Image\ImageManager;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * $imageManager
     *
     * @var Intervention\Image\ImageManager
     */
    protected $imageManager;


    /**
     * __construct
     *
     * @param ImageManager $imageManager
     * @return void
     */
    public function __construct(ImageManager $imageManager)
    {
        $this->middleware(['auth', 'throttle:15,1']);

        $this->imageManager = $imageManager;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req, User $user)
    {
        $validated = $req->validate([
            'file' => 'required|image|max:1024'
        ]);
        
        // If no directory exists for our path create one
        if (!file_exists(public_path() . '/storage/' .  $user->id)) {
            \File::makeDirectory(public_path() . '/storage/' .  $user->id, 0770, true);
        }

        $filePath = $this->processImage($req, $req->query('size') ?? 450);

        return response(['path' => $filePath], 200);
    }

    /**
     * Processes the validated image,
     * stores it as a png for the passed in query string file size
     * and then saves it into our public directory with a unique uuid
     *
     * Returns the path to the stored file.
     * @param mixed $file the file read from the input
     * @param String $filePath relative path where you would like to store the file
     * @param int $size the cropped size of the file
     * @return String
     */
    protected function processImage(Request $req, int $size)
    {
        $this->imageManager
            ->make($req->file('file')->getPathName())
            ->encode('png')
            ->resize($size, $size)
            ->save(public_path('storage/' . $req->user()->id) . $fileName = '/' .Uuid::uuid4()  . '.png');

        return $req->user()->id . $fileName;
    }
}
