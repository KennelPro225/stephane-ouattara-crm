<?php

namespace App\Services;

use App\Models\Programme;

class ProgrammeService
{
    public function __construct(private ImageService $images) {}

    public function create(array $data, $image = null): Programme
    {
        if ($image) {
            $data['image_path'] = $this->images->store($image, 'programmes');
        }
        unset($data['image']);

        return Programme::create($data);
    }

    public function update(Programme $programme, array $data, $image = null): Programme
    {
        if ($image) {
            $data['image_path'] = $this->images->replace($programme->image_path, $image, 'programmes');
        }
        unset($data['image']);

        $programme->update($data);

        return $programme;
    }

    public function delete(Programme $programme): void
    {
        $this->images->delete($programme->image_path);
        $programme->delete();
    }
}
