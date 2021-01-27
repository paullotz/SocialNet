<?php
include 'model/Singlefile.class.php';

class Verzeichnis {

    public $path;

    public function __construct($path) {
        $this->path = $path;
    }

    // https://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
    function gen_uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    public function uploadProfilePicture($was): int
    {
        //$targetfile = $this->path . basename();
        $newfilename = "ppicture.png";
        $targetfile = $this->path . basename($newfilename);

        // Check file extension
        $info = pathinfo($targetfile);
        // Check if file exists - no overwriting
            if (move_uploaded_file($was['controlFile']['tmp_name'], $targetfile)) {
                return 1;
            }

        return 0;
    }

    public function uploadPostPicture($was): string
    {
        //$newfilename = rand(1000, 5000) . ".png";
        $newfilename = $this->gen_uuid() . ".png";
        $targetfile = $this->path . basename($newfilename);

        // Check file extension
        $info = pathinfo($targetfile);
        // Check if file exists - no overwriting
        if (move_uploaded_file($was['controlFile']['tmp_name'], $targetfile)) {
            return $newfilename;
        }

        return 0;
    }

    public function getAllFiles($path) {
        $data = [];
        $x = 0;
        $files = glob($path . '*.*');

        foreach ($files as $file) {
            $data[] = new SingleFile();
            $data[$x]->id = $x;
            $filename = pathinfo($file);
            $data[$x]->filename = $filename["basename"];
            $data[$x]->path = $path . $file;
            $data[$x]->datatype = filetype($file);

            $x++;
        }
        return $data;
    }

    public function getAllFolders($path) {
        $data = [];
        $x = 0;

        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {
                if ('.' != $entry && '..' != $entry) {
                    if (is_dir($path . '/' . $entry)) {
                        $data[] = new SingleFile();
                        $data[$x]->id = $x;
                        $data[$x]->filename = $entry;
                        $data[$x]->path = $path . $entry;
                        $data[$x]->datatype = "folder";
                        
                        $x++;
                    }   
                }
            }
            closedir($handle);
        }

        return $data;
    }
    
    public function getAllFF($path) {
        $ff = array();
        $folders = $this->getAllFolders($path);
        $files = $this->getAllFiles($path);
        
        $ff = array_merge($folders, $files);
        
        return $ff;
    }

    public function createFolder($foldername) {
        if (!(file_exists($this->path . '/' . $foldername))) {
            mkdir($this->path . '/' . $foldername);
            return 1;
        }

        return 0;
    }

    public function deleteFF($ffpath) : int{
        if (file_exists($ffpath)) {
            if (is_dir($ffpath)) {
                // Mit RM Dir löschen
                if (rmdir($ffpath)) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                // Ist ein File und deswegen mit unlink löschen
                if (unlink($ffpath)) {
                    return 1;
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }

    public function deleteProfilePicture($filepath) : bool {
        if (file_exists($filepath)) {
            if (unlink($filepath)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function deletePostPicture($filepath) : bool {
        if (file_exists($filepath)) {
            if (unlink($filepath)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
