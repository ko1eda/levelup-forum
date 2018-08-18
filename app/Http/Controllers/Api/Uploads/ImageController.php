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
     * $allowedDirectoryKeys
     *
     * @var array
     */
    protected $allowedDirectoryKeys = [
        'profile-photos',
        'avatars',
        'banners',
        'test-avatars'
    ];


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
    public function store(Request $req, String $key, User $user)
    {
        $validated = $req->validate([
            'file' => 'required|image|max:1024'
        ]);
        
        // If the user tries to store a file in a directory
        // that is not in our whitelist return 404 not found
        if (!in_array($key, $this->allowedDirectoryKeys, true)) {
            return response([], 404);
        }

        // Relative path to the resource we are storing
        $filePath = $key .'/'. $user->id ;
       
        // dd(public_path() . '/' . $filePath);
        // If no directory exists for our path create one
        if (!\File::isDirectory(public_path() . '/storage/' . $filePath)) {
            Storage::makeDirectory('public/' . $filePath);
        }

        // dd(is_writable(public_path('storage/' . $filePath)));
        $filePath = $this->processImage($validated['file'], $filePath, $req->query('size') ?? 450);

        return response(['path' => $filePath], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
    protected function processImage($file, String $filePath, int $size)
    {
        $this->imageManager
            ->make($file->getPathName())
            ->encode('png')
            ->resize($size, $size)
            ->save(public_path('storage/' . $filePath) . $fileName = '/' .Uuid::uuid4()  . '.png');

        return $filePath . $fileName;
    }
}
