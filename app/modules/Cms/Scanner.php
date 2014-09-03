<?php
 /**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Cms;

class Scanner
{

    public function search()
    {
        $phrases = array();
        $files = $this->rsearch(APPLICATION_PATH, "/.*\.(volt|php|phtml|^volt.php)$/");
        if ($files) {
            foreach ($files as $file) {
                $contents = file_get_contents($file);
                $pattern = "/translate\('(.*)'\)/";
                $matchesCount = preg_match_all($pattern, $contents, $matches);
                if ($matchesCount) {
                    foreach ($matches[1] as $match) {
                        if (!in_array($match, $phrases)) {
                            $phrases[] = $match;
                        }
                    }
                }
            }
        }
        return $phrases;
    }

    private function rsearch($folder, $pattern)
    {
        $dir = new \RecursiveDirectoryIterator($folder);
        $ite = new \RecursiveIteratorIterator($dir);
        $files = new \RegexIterator($ite, $pattern, \RegexIterator::GET_MATCH);
        $fileList = array();
        foreach ($files as $file) {
            $fileList = array_merge($fileList, $file);
        }
        return $fileList;
    }

} 
