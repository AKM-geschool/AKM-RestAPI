<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Imagick;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

require '/Magang/AKM/lumen-rest-api-AKM/vendor/autoload.php';

class Pdf2imgController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index()
    {
        $imagick = new Imagick();

        $imagick->readImage(public_path('/assets/2022.pdf'));

        $saveImagePath = public_path('converted-test.jpg');
        $imagick->writeImages($saveImagePath, true);

        $type = 'image/jpg';
        $headers = ['Content-Type' => $type];
        $response = new BinaryFileResponse($saveImagePath, 200, $headers);

        return $response;

        // return response()->download($saveImagePath);
    }

    //
}
