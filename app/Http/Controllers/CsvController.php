<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Response;
use File;



class CsvController extends Controller
{
    //
    public function get_csv(){

        $users = User::get();

        // these are the headers for the csv file.
        $headers = array(
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Disposition' => 'attachment; filename=download.csv',
            'Expires' => '0',
            'Pragma' => 'public',
        );


        //I am storing the csv file in public >> files folder. So that why I am creating files folder
        if (!File::exists(public_path()."/files")) {
            File::makeDirectory(public_path() . "/files");
        }

        //creating the download file
        $filename =  public_path("files/download.csv");
        $handle = fopen($filename, 'w');

        //adding the first row
        fputcsv($handle, [
            "Name",
            "Email",
        ]);

        //adding the data from the array
        foreach ($users as $each_user) {
            fputcsv($handle, [
                $each_user->name,
                $each_user->email,
            ]);

        }
        fclose($handle);

        //download command
        return Response::download($filename, "download.csv", $headers);
    }
}
