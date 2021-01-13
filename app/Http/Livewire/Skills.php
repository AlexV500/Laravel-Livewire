<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Skill;
use Livewire\WithPagination;

class Skills extends Component
{
    use WithPagination;

    public $search;
    public $skillId,$skill;
    public $isOpen = 0;

    // Merender View
    public function render()
    {
        $searchParams = '%'.$this->search.'%';
        return view('livewire.skills.skills', [
            'skills' => Skill::where('skill','like',$searchParams)->latest()->paginate(5)
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
                'skill' => 'required',
            ]
        );

        Skill::updateOrCreate(['id' => $this->skillId],[
            'skill' => $this->skill,
        ]);

        $this->hideModal();

        session()->flash('info', $this->skillId ? 'Skill Update Successfully' : 'Skill Created Successfully');

        $this->skillId = '';
        $this->skill = '';
    }

    public function edit($id){
        $post = Skill::findOrfail($id);
        $this->skillId = $id;
        $this->skill = $post->skill;

        $this->showModal();
    }

    public function delete($id){
        Skill::find($id)->delete();
        session()->flash('delete','Skill Delete Successfully');
    }
}
