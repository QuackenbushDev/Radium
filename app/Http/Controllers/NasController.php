<?php namespace App\Http\Controllers;

use App\Nas;

class NasController extends Controller {
    public function index() {
        $nasList = Nas::paginate()
            ->all();

        return view()->make('pages.nas.index', ['nasList' => $nasList]);
    }

    public function show($id) {
        $nas = Nas::find($id);

        return view()->make('pages.nas.show', ['nas' => $nas]);
    }

    public function edit($id) {
        $nas = Nas::find($id);

        return view()->make('pages.nas.edit', ['nas' => $nas]);
    }

    public function update($id) {

    }

    public function create() {
        return view()->make('pages.nas.create');
    }

    public function save() {

    }
}