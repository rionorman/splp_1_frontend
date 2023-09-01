<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\RabbitMQController;
use Illuminate\Database\Eloquent\Collection;

class PostController extends Controller
{

	public function index()
	{
		$response = Http::get('http://127.0.0.1:8001/api/indexPostAPI');
		// $response = Http::get('http://localhost:8290/postAPI/indexPostAPI');
		$response = (json_decode($response, true));
		if ($response != NULL) {
			return view('post/postlist', ['rows' => $response['data']]);
		} else {
			dd('Error');
		}
	}

	public function create()
	{
		$response = Http::get('http://127.0.0.1:8001/api/getCategoriesAPI');
		// $response = Http::get('http://localhost:8290/postAPI/getCategoriesAPI');
		$response = (json_decode($response, true));
		$categories = $response['data'];
		return view('post/postform', ['action' => 'insert', 'categories' => $categories]);
	}

	public function store(Request $request)
	{
		$url_api = 'http://127.0.0.1:8001/api/storePostAPI';
		// $url_api = 'http://localhost:8290/postAPI/storePostAPI';
		$this->validate($request, [
			'image' => 'required|mimes:jpg,png,jpeg',
		]);

		$image_ext = $request->image->extension();
		$image_name = time() . '.' . $image_ext;
		$request->image->move(public_path('images'), $image_name);
		$image = 'data:@image/' . $image_ext . ';base64,' . base64_encode(file_get_contents(public_path('images/') . $image_name));

		$response = Http::post($url_api, [
			'id' => $request->id,
			'user_id' => $request->user_id,
			'cat_id' => $request->cat_id,
			'title' => $request->title,
			'content' => $request->content,
			'image' => $image
		]);

		$response = (json_decode($response, true));
		if ($response['success']) {
			$image_path = public_path('images/' . $image_name);
			if (file_exists($image_path)) {
				unlink($image_path);
			}
			return redirect('/post');
		}
	}

	public function show($id)
	{
		$response = Http::get('http://127.0.0.1:8001/api/showPostAPI/' . $id);
		// $response = Http::get('http://localhost:8290/postAPI/showPostAPI/' . $id);
		$response = (json_decode($response, false));
		$post = $response->data;
		return view('post/postform', ['row' => $post, 'action' => 'detail']);
	}

	public function edit($id)
	{
		$response = Http::get('http://127.0.0.1:8001/api/showPostAPI/' . $id);
		// $response = Http::get('http://localhost:8290/postAPI/showPostAPI/' . $id);
		$response = (json_decode($response, false));
		$post = $response->data;

		$response = Http::get('http://127.0.0.1:8001/api/getCategoriesAPI');
		// $response = Http::get('http://localhost:8290/postAPI/getCategoriesAPI');

		$response = (json_decode($response, true));
		$rows = $response['data'];

		return view('post/postform', ['row' => $post, 'action' => 'update', 'categories' => $rows]);
	}

	public function update(Request $request)
	{
		$url_api = 'http://127.0.0.1:8001/api/updatePostAPI';
		// $url_api = 'http://localhost:8290/postAPI/updatePostAPI';

		if ($request->image != NULL) {

			$image_ext = $request->image->extension();
			$image_name = time() . '.' . $image_ext;
			$request->image->move(public_path('images'), $image_name);
			$image = 'data:@image/' . $image_ext . ';base64,' . base64_encode(file_get_contents(public_path('images/') . $image_name));


			// $file = fopen(public_path('images/') . $imageName, 'r');
			// $response = Http::attach(
			// 	'image',
			// 	$file,
			// 	$imageName
			// )->post($url_api, [
			// 	'id' => $request->id,
			// 	'user_id' => $request->user_id,
			// 	'cat_id' => $request->cat_id,
			// 	'title' => $request->title,
			// 	'content' => $request->content,
			// 	'image' => $imageName
			// ]);

			$response = Http::post($url_api, [
				'id' => $request->id,
				'user_id' => $request->user_id,
				'cat_id' => $request->cat_id,
				'title' => $request->title,
				'content' => $request->content,
				'image' => $image
			]);


			$image_path = public_path('images/' . $image_name);
			if (file_exists($image_path)) {
				unlink($image_path);
			}
		} else {
			$response = Http::post($url_api, [
				'id' => $request->id,
				'user_id' => $request->user_id,
				'cat_id' => $request->cat_id,
				'title' => $request->title,
				'content' => $request->content
			]);
		}
		return redirect('/post');
	}

	public function delete($id)
	{
		$response = Http::get('http://127.0.0.1:8001/api/showPostAPI/' . $id);
		// $response = Http::get('http://localhost:8290/postAPI/showPostAPI/' . $id);
		$response = (json_decode($response, false));
		$post = $response->data;
		return view('post/postform', ['row' => $post, 'action' => 'delete']);
	}

	public function destroy($id)
	{
		$response = Http::post('http://127.0.0.1:8001/api/destroyPostAPI/' . $id);
		// $response = Http::post('http://localhost:8290/postAPI/destroyPostAPI/' . $id);
		$response = (json_decode($response, false));
		return redirect('/post');
	}
}
