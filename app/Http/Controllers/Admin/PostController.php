<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Mail\NewPostCreated;
use App\Mail\PostUpdatedAdminMessage;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderByDesc('id')->get();
        //dd($posts);
        // per far vedere solo i post dell'utente
        $post = Auth::user()->posts;
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        //dd($categories);
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        //dd($request->all());

        // Validate data and tag
        $val_data = $request->validated();

        // Gererate the slug
        $slug = Post::generateSlug($request->title);
        $val_data['slug'] = $slug;

        // assegniamo un post all'utente
        $val_data['user_id'] = Auth::id();

        /* Opzione in plain PHP */
        // array_key_exists('cover_image', $request->all())

        //Verifichiamo se è contenuto un file nella cover_image
        if ($request->hasFile('cover_image')) {
            //valida il file
            $request->validate([
                'cover_image' => 'nullable|image|max:500'
            ]);
            //Lo salviamo nel filesystem e recuperiamo il percorso
            $path = Storage::put('post_image', $request->cover_image);

            //ddd($path);

            //Passiamo il percorso nell'array con i dati validati
            $val_data['cover_image'] = $path;
        }
        //dd($path);
        // Creare la resource
        $new_post = Post::create($val_data);
        $new_post->tags()->attach($request->tags);

        //invia mail usando l'istanza dell'utente
        Mail::to($request->user())->send(new NewPostCreated($new_post));

        // Redirect to a get route
        return redirect()->route('admin.posts.index')->with('message', 'Post Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {


        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        /*Validation*/
        $val_data = $request->validated();


        // Gererate the slug
        $slug = Post::generateSlug($request->title);

        $val_data['slug'] = $slug;

        // Update 
        if ($request->hasFile('cover_image')) {
            //Valida il file
            $request->validate([
                'cover_image' => 'nullable|image|max:500'
            ]);

            // elimina la vecchia foto 
            Storage::delete($post->cover_image);

            //Lo salviamo nel filesystem e recupero il percorso / path
            $path = Storage::put('post_image', $request->cover_image);
            //ddd($path);

            //Passiamo il percorso/path all'array con i dati validati
            $val_data['cover_image'] = $path;
        }


        // update the resource
        $post->update($val_data);

        //Sync tags
        $post->tags()->sync($request->tags);

        Mail::to('admin@boolpress.it')->send(new PostUpdatedAdminMessage($post));
        
        // redirect to get route
        return redirect()->route('admin.posts.index')->with('message', "$post->title updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {

        //dd($post);
        Storage::delete($post->cover_image);
        //dd($post);
        $post->delete();
        return redirect()->route('admin.posts.index')->with('message', "$post->title deleted successfully");
    }
}
