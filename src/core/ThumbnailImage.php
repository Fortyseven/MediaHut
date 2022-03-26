?>
<?php

class ThumbnailImage
{
    private $file_hash;
    private $thumb_path;

    public function __construct($path, $extension)
    {
        $this->file_hash = sha1($path);
        $this->thumb_path = THUMBS_ROOT . DIRECTORY_SEPARATOR . $this->file_hash . ".jpg";

        if (!file_exists($this->thumb_path)) {
            $this->rebuildThumbnail($path, $extension);
        }
    }

    private function rebuildThumbnail($path, $extension)
    {
        if (THUMBS_ROOT) {
            # the `imagecreatefrom*` family seems to have a hate-on for single quotes
            $sanitized_path = html_entity_decode($path, ENT_QUOTES);

            switch ($extension) {
                case 'webp':
                    $image_src = imagecreatefromwebp($sanitized_path);
                    break;
                case 'png':
                    $image_src = imagecreatefrompng($sanitized_path);
                    break;
                # assume jpeg otherwise
                case 'jpg':
                case 'jpeg':
                default:
                    $image_src = imagecreatefromjpeg($sanitized_path);
                    break;
            }

            if ($image_src) {

                $image_width = imagesx($image_src);
                $image_height = imagesy($image_src);

                $thumb_height = floor($image_height * (THUMB_WIDTH / $image_width));

                $image_dest = imagecreatetruecolor(THUMB_WIDTH, $thumb_height);

                imagecopyresampled(
                    $image_dest, $image_src,
                    0, 0, 0, 0,
                    THUMB_WIDTH, $thumb_height,
                    $image_width, $image_height);

                imagejpeg($image_dest, $this->thumb_path);

                imagedestroy($image_src);
                imagedestroy($image_dest);
            } else {
                # TODO: the image failed to open, so just serve the
                #       original image for now. But we need a way
                #       to report the failure.
                $this->thumb_path = $path;
            }
        } else {
            # serve the original file if thumbs are disabled
            $this->thumb_path = $path;
        }
    }

    public function getRenderer()
    {
        return "<img src='$this->thumb_path'/>";
    }
}