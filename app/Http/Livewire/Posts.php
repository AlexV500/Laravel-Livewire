<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Post;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination;

    public $search;
    public $postId,$title,$description,$gambar,$tanggal;
    public $isOpen = 0;

    // Merender View
    public function render()
    {
        $searchParams = '%'.$this->search.'%';
        return view('livewire.posts', [
            'posts' => Post::where('title','like',$searchParams)->latest()->paginate(5)
        ]);
    }
    // end render

    // Menjalankan Modal Create
    public function showModal(){
        $this->isOpen = true;
    }

    public function hideModal(){
        $this->isOpen = false;
    }
    // end Modal

    // Nambah Data
    public function store(){
        $this->validate(
            [
                'title' => 'required',
                'description' => 'required',
                // 'gambar' => 'required',
                'tanggal' => 'required',
            ]
        );

        Post::updateOrCreate(['id' => $this->postId],[
            'title' => $this->title,
            'description' => $this->description,
            'gambar' => $this->gambar,
            'tanggal' => $this->tanggal,
        ]);

        $this->hideModal();

        session()->flash('info', $this->postId ? 'Post Update Successfully' : 'Post Created Successfully');

        $this->postId = '';
        $this->title = '';
        $this->description = '';
        $this->gambar = '';
        $this->tanggal = '';
    }

    public function edit($id){
        $post = Post::findOrfail($id);
        $this->postId = $id;
        $this->title = $post->title;
        $this->description = $post->description;
        $this->gambar = $post->gambar;
        $this->tanggal = $post->tanggal;

        $this->showModal();
    }

    public function delete($id){
        Post::find($id)->delete();
        session()->flash('delete','Post Delete Successfully');
    }
}
