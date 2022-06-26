<?php

$ROOT = $_POST['ROOT'];
$IMAGESIZE = $_POST['IMAGESIZE'];
$IMAGEQUA = $_POST['IMAGEQUA'];

if (is_array($_FILES)) {
    // echo $IMAGESIZE;
    if (is_uploaded_file($_FILES['image_file']['tmp_name'])) {

        //the new width of the resized image, in pixels.
        $max_size = $IMAGESIZE; // 
        //the image -> variables
        $image_type = $_FILES['image_file']['type'];
        $file_name = $_FILES['image_file']['name'];
        $file_size = $_FILES['image_file']['size'];
        $sourcePath = $_FILES['image_file']['tmp_name'];

        $image_size_info = getimagesize($sourcePath); //get image size

        if ($image_size_info) {
            $image_width = $image_size_info[0]; //image width
            $image_height = $image_size_info[1]; //image height
        }

        //so, whats the file's extension?
        $image_info = pathinfo($file_name);
        $image_extension = strtolower($image_info["extension"]); //image extension
        $image_name_only = strtolower($image_info["filename"]); //file name only, no extension
        //create a random name for new image (Eg: fileName_293749.jpg) ;
        $new_file_name = $image_name_only . '_' . rand(1, 999999999) . '.' . $image_extension;

        $targetPath = $ROOT . "/template/nowah/img/" . $new_file_name;
        $uploadedPath = "/template/nowah/img/" . $new_file_name;
        //keep image type

        switch ($image_type) {
            case 'image/png':
                $image_res = imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $image_res = imagecreatefromgif($sourcePath);
                break;
            case 'image/jpeg': case 'image/pjpeg':
                $image_res = imagecreatefromjpeg($sourcePath);
                break;
            default:
                $image_res = false;
        }

        //calculate the image ratio
        if ($image_width > $max_size || $image_height > $max_size) {
            $image_scale = min($max_size / $image_width, $max_size / $image_height);
            $new_width = ceil($image_scale * $image_width);
            $new_height = ceil($image_scale * $image_height);
            //function for resize image.
            $new_canvas = imagecreatetruecolor($new_width, $new_height); //Create a new true color image

            switch (strtolower($image_type)) {
                case 'image/jpeg':
                    imagecopyresized($new_canvas, $image_res, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
                    break;
                case 'image/png':
                    imagealphablending($new_canvas, FALSE);
                    imagesavealpha($new_canvas, TRUE);
                    imagecopyresized($new_canvas, $image_res, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
                    break;
                case 'image/gif':
                    imagecopyresized($new_canvas, $image_res, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
                    $background = imagecolorallocate($new_canvas, 0, 0, 0);
                    imagecolortransparent($new_canvas, $background);
                    break;
                default: break;
            }

            //the resizing is going on here!
            //  ImageJpeg($new_canvas, $targetPath, $IMAGEQUA);
            //  echo '<br>' . $image_scale . '-' . $new_height . '-' . $new_width;
        } else {
            $new_canvas = imagecreatetruecolor($image_width, $image_height);

            switch (strtolower($image_type)) {
                case 'image/jpeg':
                    imagecopyresized($new_canvas, $image_res, 0, 0, 0, 0, $image_width, $image_height, $image_width, $image_height);
                    break;
                case 'image/png':
                    imagealphablending($new_canvas, FALSE);
                    imagesavealpha($new_canvas, TRUE);
                    imagecopyresized($new_canvas, $image_res, 0, 0, 0, 0, $image_width, $image_height, $image_width, $image_height);
                    break;
                case 'image/gif':
                    imagecopyresized($new_canvas, $image_res, 0, 0, 0, 0, $image_width, $image_height, $image_width, $image_height);
                    $background = imagecolorallocate($new_canvas, 0, 0, 0);
                    imagecolortransparent($new_canvas, $background);
                    break;
                default: break;
            }
        }
        //finally, save the image
        switch (strtolower($image_type)) {  //determine mime type
            case 'image/png':

                imagepng($new_canvas, $targetPath);
                echo '<img width="100%" src="' . $uploadedPath . '" class="upload-preview" />'
                . '<input name="uploadedPath" type="hidden" value="' . $uploadedPath . '">';
                ImageDestroy($new_canvas);
                ImageDestroy($image_res);
                break;
            case 'image/gif':

                imagegif($new_canvas, $targetPath);
                echo '<img width="100%" src="' . $uploadedPath . '" class="upload-preview" />'
                . '<input name="uploadedPath" type="hidden" value="' . $uploadedPath . '">';
                ImageDestroy($new_canvas);
                ImageDestroy($image_res);
                break;
            case 'image/jpeg': case 'image/pjpeg':
                imagejpeg($new_canvas, $targetPath, $IMAGEQUA);
                echo '<img width="100%" src="' . $uploadedPath . '" class="upload-preview" />'
                . '<input name="uploadedPath" type="hidden" value="' . $uploadedPath . '">';
                ImageDestroy($new_canvas);
                ImageDestroy($image_res);
                break;
            default: break;
        }
    }
}
?>