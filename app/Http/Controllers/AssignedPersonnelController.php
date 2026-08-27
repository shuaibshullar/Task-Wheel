<?php

namespace App\Http\Controllers;

use App\Models\User as Person;

class AssignedPersonnelController extends Controller
{
    public function assignees(?array $names): ?array
    {
        $persons = Person::getAllByNamesKey();

        $ids = [];

        if ($names === null) return null;
        foreach ($names as $name)
        {

            $normal_name = $name;
            $name = strtolower($name);

            if ( isset($persons[$name]) )
            {

                $id = $persons[$name];

                $ids[] = $id;
                $this->modify($id, $normal_name);


            } else {

                $id = $this->add($name);
                if ($id) $ids[] = $id;

            }
        }

        return empty($ids) ? null : $ids;
    }

    private function add(string $name): ?int
    {
        return Person::add($name)?->id;
    }

    private function modify(int $id, string $name)
    {
        return Person::changeName($id, $name);
    }
}
