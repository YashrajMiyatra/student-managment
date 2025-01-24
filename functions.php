function createThumbnail($sourcePath, $thumbPath, $thumbWidth, $thumbHeight) {
    list($width, $height) = getimagesize($sourcePath);
    $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
    $source = imagecreatefromjpeg($sourcePath);
    
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);
    imagejpeg($thumb, $thumbPath);
    
    imagedestroy($thumb);
    imagedestroy($source);
}
