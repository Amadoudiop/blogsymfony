<?php

namespace AppBundle\Service;

class FileHandler
{
    public function upload($file, $directory, $type="article")
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        if( ($type == "article")
            && ((filesize($file) > 375000) || ((getimagesize($file)[0]/getimagesize($file)[1])!=1)) ){

                return false;
        }elseif ( ($type == "slide")
                && ( (filesize($file) > 375000) || (getimagesize($file)[1] !=198 ) || (getimagesize($file)[0] != 694)) ) {

            return false;
        }
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