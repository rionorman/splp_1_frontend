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
		$response = (json_decode($response, true));
		if ($response != NULL) {
			return view('post/postlist', ['rows' => $response['data']]);
		} else {
			dd('Error');
		}
	}

	public function create()
	{
		$response = Http::get('http://127.0.0.1:8001/api/getCategories');
		$response = (json_decode($response, true));
		$categories = $response['data'];
		return view('post/postform', ['action' => 'insert', 'categories' => $categories]);
	}

	public function store(Request $request)
	{
		$this->validate($request, [
			'image' => 'required|mimes:jpg,png,jpeg',
		]);
		$imageName = time() . '.' . $request->image->extension();
		$request->image->move(public_path('images'), $imageName);
		$file = fopen(public_path('images/') . $imageName, 'r');
		$response = Http::attach(
			'image',
			$file,
			$imageName
		)->post('http://127.0.0.1:8001/api/storePostAPI', [
			'user_id' => $request->user_id,
			'cat_id' => $request->cat_id,
			'title' => $request->title,
			'content' => $request->content,
			'image' => $imageName
		]);

		$response = (json_decode($response, true));
		if ($response['success']) {
			$image_path = public_path('images/' . $imageName);
			if (file_exists($image_path)) {
				unlink($image_path);
			}
			return redirect('/post');
		}
	}

	public function show($id)
	{
		$response = Http::get('http://127.0.0.1:8001/api/showPostAPI/' . $id);
		$response = (json_decode($response, false));
		$post = $response->data;
		return view('post/postform', ['row' => $post, 'action' => 'detail']);
	}

	public function edit($id)
	{
		$response = Http::get('http://127.0.0.1:8001/api/showPostAPI/' . $id);
		$response = (json_decode($response, false));
		$post = $response->data;

		$response = Http::get('http://127.0.0.1:8001/api/getCategories');
		$response = (json_decode($response, true));
		$rows = $response['data'];

		return view('post/postform', ['row' => $post, 'action' => 'update', 'categories' => $rows]);
	}

	public function update(Request $request)
	{
		if ($request->image != NULL) {
			$imageName = time() . '.' . $request->image->extension();
			$request->image->move(public_path('images'), $imageName);
			$file = fopen(public_path('images/') . $imageName, 'r');
			$response = Http::attach(
				'image',
				$file,
				$imageName
			)->post('http://127.0.0.1:8001/api/updatePostAPI', [
				'id' => $request->id,
				'user_id' => $request->user_id,
				'cat_id' => $request->cat_id,
				'title' => $request->title,
				'content' => $request->content,
				'image' => $imageName
			]);

			$image_path = public_path('images/' . $imageName);
			if (file_exists($image_path)) {
				unlink($image_path);
			}
		} else {
			$response = Http::post('http://127.0.0.1:8001/api/updatePostAPI', [
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
		$response = (json_decode($response, false));
		$post = $response->data;
		return view('post/postform', ['row' => $post, 'action' => 'delete']);
	}

	public function destroy($id)
	{
		$response = Http::post('http://127.0.0.1:8001/api/destroyPostAPI/' . $id);
		$response = (json_decode($response, false));
		return redirect('/post');
	}
}
