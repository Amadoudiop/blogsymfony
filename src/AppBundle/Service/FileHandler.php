<?php

namespace AppBundle\Service;

class FileHandler
{
    public function upload($file, $directory)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        if (filesize($file) > 375000){

            return false;

        }else{
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
}