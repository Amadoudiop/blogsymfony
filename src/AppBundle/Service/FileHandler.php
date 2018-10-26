<?php

namespace AppBundle\Service;

class FileHandler
{
    public function upload($file, $directory)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move(
            $directory,
            $fileName
        );

        return
            [
            "name" => $fileName
            ];
    }
}