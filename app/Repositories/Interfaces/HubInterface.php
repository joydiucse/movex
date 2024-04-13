<?php

namespace App\Repositories\Interfaces;

interface HubInterface{

    public function paginate();

    public function allUsers();

    public function store($request);

    public function get($id);

    public function delete($id);

    public function update($request);
}
