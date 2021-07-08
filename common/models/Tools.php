<?php

namespace common\models;

class Tools
{
    public static function show_size($f, $format = true)
    {
        if ($format) {
            $size = self::show_size($f, false);
            if ($size <= 1024) return $size . ' bytes';
            else if ($size <= 1024 * 1024) return round($size / (1024), 2) . ' Kb';
            else if ($size <= 1024 * 1024 * 1024) return round($size / (1024 * 1024), 2) . ' Mb';
            else if ($size <= 1024 * 1024 * 1024 * 1024) return round($size / (1024 * 1024 * 1024), 2) . ' Gb';
            else if ($size <= 1024 * 1024 * 1024 * 1024 * 1024) return round($size / (1024 * 1024 * 1024 * 1024), 2) . ' Tb'; //:)))
            else return round($size / (1024 * 1024 * 1024 * 1024 * 1024), 2) . ' Pb'; // ;-)
        } else {
            if (is_file($f)) return filesize($f);
            $size = 0;
            $dh = opendir($f);
            while (($file = readdir($dh)) !== false) {
                if ($file == '.' || $file == '..') continue;
                if (is_file($f . '/' . $file)) $size += filesize($f . '/' . $file);
                else $size += self::show_size($f . '/' . $file, false);
            }
            closedir($dh);
            return $size + filesize($f); // +filesize($f) for *nix directories
        }
    }

    public static function removeTimeout()
    {
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
    }
}