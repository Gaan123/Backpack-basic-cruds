<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\MediaResource;
use App\Models\Article;
use App\Models\ArticleViewLog;
use App\Models\Category;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Plank\Mediable\Exceptions\MediaUpload\ConfigurationException;
use Plank\Mediable\Exceptions\MediaUpload\FileExistsException;
use Plank\Mediable\Exceptions\MediaUpload\FileNotFoundException;
use Plank\Mediable\Exceptions\MediaUpload\FileNotSupportedException;
use Plank\Mediable\Exceptions\MediaUpload\FileSizeException;
use Plank\Mediable\Exceptions\MediaUpload\ForbiddenException;
use Plank\Mediable\Facades\ImageManipulator;
use Plank\Mediable\Facades\MediaUploader;
use Plank\Mediable\Media;

class AdminController extends \Backpack\CRUD\app\Http\Controllers\AdminController
{
    protected $data = [];

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(backpack_middleware());
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $this->data['title'] = trans('backpack::base.dashboard'); // set the page title
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin') => backpack_url('dashboard'),
            trans('backpack::base.dashboard') => false,
        ];

        $this->data['article_viewed_today'] = ArticleViewLog::whereDate('created_at', today())->count();
        $this->data['subscriber'] = Subscription::count();
        $this->data['category'] = Category::count();
        $this->data['total_article'] = Article::count();
        $this->data['weeklyArticle'] = Article::whereDate('created_at', '>=', today()->subDay(6))->count();
        return view(backpack_view('dashboard'), $this->data);
    }

    /**
     * Redirect to the dashboard.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        return redirect(backpack_url('dashboard'));
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect('/');
    }

    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadFile(Request $request)
    {

        try {
            $media = MediaUploader::fromSource($request->file('file'))
                ->toDestination('public', 'uploads')
                ->makePublic()
                ->upload();
//            $variantMedia = ImageManipulator::createImageVariant($media, 'thumbnail');
//            $variantMedia->makePublic();
            return \response()->json(MediaResource::collection([$media]), 201);
        } catch (ConfigurationException | FileExistsException | FileNotFoundException | FileNotSupportedException | FileSizeException | ForbiddenException $e) {
            dd($e);
            return \response()->json([$e], 500);
        }
    }

    public function getMedia()
    {

        if (request()->q) {
            $medias = MediaResource::collection(Media::where('filename', 'LIKE', '%' . request()->q . '%')->get());
            return response()->json($medias, 200);
        }
        $medias = MediaResource::collection(Media::latest()->paginate(24));
        return response()->json($medias, 200);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function postCaption(Request $request , $id)
    {
        $media=\App\Models\Media::find($id);
        $media->update([
            'caption'=>$request->caption
        ]);
        return response()->json(['message' =>"caption updated"],201);
    }

    public function deleteMedia(Request $request, Media $media)
    {
        Media::destroy($request->id);
        return response()->json(['message' => 'Image deleted successfully'], 200);
    }
}
