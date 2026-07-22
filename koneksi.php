<?php
$con = mysqli_connect("localhost", "root", "", "florist_pos");
$appname = 'Florist';

function uploadFile($file, $destinationDir)
{
    if (!is_dir($destinationDir)) {
        mkdir($destinationDir, 0777, true);
    }

    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

    $newFileName = uniqid() . '.' . $fileExtension;

    $destinationPath = $destinationDir . $newFileName;

    if (move_uploaded_file($file['tmp_name'], $destinationPath)) {
        return [
            'success' => true,
            'file_name' => $newFileName,
            'file_path' => $destinationPath
        ];
    } else {
        return [
            'success' => false,
            'error' => 'File upload failed'
        ];
    }
}

