<?php
/**
 * Created by PhpStorm.
 * User: Adit
 * Date: 30/10/2017
 * Time: 10.38
 */

class ScanFile
{
    /**
     * @var string
     * Path which directory will be scanned
     */
    private $path;

    /**
     * @var string
     * Path which extension will be scanned
     */
    private $ext;

    /**
     * @var int
     * Directory deepness scan
     */
    private $deep;

    /**
     * @var array
     * List of all file and it's md5
     */
    public $listFile;

    /**
     * @var array
     * Count distinct file result
     */
    public $distinctValue;

    /**
     * @var int
     * Number of previewed character
     */
    public $preview;

    /**
     * @var int
     * Total file processed
     */
    public $total_file;

    CONST NEW_LINE = "\n";
    CONST RED_TEXT = "";//"\033[31m";
    CONST WHITE_TEXT = "";//"\033[0m";
    CONST BLUE_TEXT = "";//"\033[34m";
    CONST PURPLE_TEXT = "";//"\033[35m";
    CONST GREEN_TEXT = "";//"\033[32m";

    public function __construct($path = ".", $ext = "*", $deep = -1, $preview = 50)
    {
        $this->path = $path;
        $this->ext = $ext;
        $this->deep = $deep;
        $this->preview = $preview;
    }

    /**
     * Process file scanning and compare the data
     */
    public function process()
    {
        $this->listFile = $this->read();
        $this->compare_hash();
    }

    /**
     * @return array
     * Compare file by it hash value and count the recurrence
     */
    public function compare_hash()
    {
        $this->distinctValue = [];

        foreach ($this->listFile as $file => $hash) {
            if (array_key_exists($hash, $this->distinctValue)) {
                $this->distinctValue[$hash] = $this->distinctValue[$hash] + 1;
            } else {
                $this->distinctValue[$hash] = 1;
            }
        }
        return $this->distinctValue;
    }

    /**
     * Print the highest recurrent file
     */
    public function get_highest_count_file()
    {
        $tempArray = $this->distinctValue;
        if (count($tempArray) > 0) {
            arsort($tempArray);
            $hash = "";
            $count = 0;
            foreach ($tempArray as $h => $c) {
                $hash = $h;
                $count = $c;
                break;
            }
            echo self::PURPLE_TEXT . "=========================================================================" . self::NEW_LINE . self::WHITE_TEXT;
            echo self::PURPLE_TEXT . "-------------------------Highest Recurrent File--------------------------" . self::NEW_LINE . self::WHITE_TEXT;
            $file = array_search($hash, $this->listFile);

            if (($this->preview > 0)) {
                // Get file content limit to first $preview character
                $content = file_get_contents($file, false, null, 0, $this->preview);
            } else {
                $content = file_get_contents($file);
            }
            // Display file content as one liner
            echo self::BLUE_TEXT . str_replace(array("\r", "\n"), '', $content) . ": " . self::GREEN_TEXT . $count . self::WHITE_TEXT;
            echo self::PURPLE_TEXT . self::NEW_LINE . "----------------------------------End------------------------------------" . self::NEW_LINE . self::WHITE_TEXT;
            echo self::PURPLE_TEXT . "=========================================================================" . self::NEW_LINE . self::NEW_LINE . self::WHITE_TEXT;
        } else {
            echo self::RED_TEXT . "No file found" . self::NEW_LINE . self::WHITE_TEXT;
        }
    }

    /**
     * Print file content and it's recurrence
     */
    public function describe_file()
    {
        if (count($this->listFile) > 0) {
            echo self::PURPLE_TEXT . "=========================================================================" . self::NEW_LINE . self::WHITE_TEXT;
            echo self::PURPLE_TEXT . "------------------------------Count Content------------------------------" . self::NEW_LINE . self::WHITE_TEXT;
            foreach ($this->distinctValue as $hash => $count) {
                $file = array_search($hash, $this->listFile);

                if (($this->preview > 0)) {
                    // Get file content limit to first $preview character
                    $content = file_get_contents($file, false, null, 0, $this->preview);
                } else {
                    $content = file_get_contents($file);
                }

                // Display file content as one liner
                echo self::BLUE_TEXT . str_replace(array("\r", "\n"), '', $content) . ": " . self::GREEN_TEXT . $count . self::WHITE_TEXT . "\n\n";
            }
            echo self::PURPLE_TEXT . "--------------------------------End Count--------------------------------" . self::NEW_LINE . self::WHITE_TEXT;
            echo self::PURPLE_TEXT . "=========================================================================" . self::NEW_LINE . self::NEW_LINE . self::WHITE_TEXT;
        } else {
            echo self::RED_TEXT . "No file found" . self::NEW_LINE . self::WHITE_TEXT;
        }
    }

    /**
     * @param $directory
     * @param $level
     * @return array
     * Scan directory and generate it's md5 hash
     */
    private function read($directory = "", $level = 0)
    {
        if ($level == 0) {
            $this->total_file = 0;
        }
        // initiate for the first processed path from root scanned directory
        if (empty($directory)) {
            $directory = $this->path;
        }

        // list directory content
        $dirs = scandir($directory);
        $scan_file = [];

        foreach($dirs as $idx => $dir) {
            // eliminate scan current folder again and parent folder
            if (!in_array($dir, [".", ".."])) {
                $file = $directory . (empty($directory) ? "" : "/") . $dir;
                // check if it is a directory
                if (is_dir($file)) {
                    // limit directory scanning deep
                    if ($level < $this->deep || $this->deep == -1) {
                        $scan_file = array_merge($scan_file, $this->read($file, $level + 1));
                    }
                } else {
                    // filter checked file by extension
                    if (preg_match("/^.*\." . $this->ext . "$/", $file)) {
                        // get hash file for comparison
                        $scan_file[$file] = sha1_file($file, false);
                        $this->total_file++;
                    }
                }
            }
        }

        return $scan_file;
    }
}