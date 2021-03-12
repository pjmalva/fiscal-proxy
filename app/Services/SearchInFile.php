<?php

namespace App\Services;

class SearchInFile
{
    private $fileName;

    public function __construct(string $fileName, string $extension="csv")
    {
        $this->fileName = storage_path("app/consult/$fileName.$extension");
    }

    public function find($search, $count=10)
    {
        $file = fopen($this->fileName, 'r');
        $itemsFound = [];
        $itemsFoundCount = 0;

        while(!feof($file)) {
            $item = fgets($file);
            if(strpos($item, $search) !== FALSE) {
                $item = trim($item);
                $itemArray = explode("|", $item);
                $itemsFound[] = [
                    "code" => $itemArray[0],
                    "description" => $itemArray[1]
                ];
                $itemsFoundCount++;
            }

            if($itemsFoundCount == $count) {
                break;
            }
        }

        fclose($file);

        return $itemsFound;
    }
}
