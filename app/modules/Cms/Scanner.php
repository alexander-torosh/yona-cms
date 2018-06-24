<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Cms;

class Scanner
{

    /**
     * @return array $phrases
     */
    public function search()
    {
        $phrases = [];
        $files_pattern = "/.*\\.(volt|php|phtml)$/";
        $files_plugins = $this->rsearch(APPLICATION_PATH . '/plugins', $files_pattern);
        $files_modules = $this->rsearch(APPLICATION_PATH . '/modules', $files_pattern);
        $files_views = $this->rsearch(APPLICATION_PATH . '/views', $files_pattern);
        $files = array_merge($files_plugins, $files_views, $files_modules);
        if (!empty($files)) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    $contents = file_get_contents($file);
                    $pattern = "/translate\('(.+?)'(?:.*)\)/";
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
        }
        return $phrases;
    }

    /**
     * @param string $folder
     * @param string $pattern
     * @return array $fileList
     */
    private function rsearch($folder, $pattern)
    {
        $dir = new \RecursiveDirectoryIterator($folder);
        $ite = new \RecursiveIteratorIterator($dir);
        $files = new \RegexIterator($ite, $pattern, \RegexIterator::GET_MATCH);
        $fileList = [];
        foreach ($files as $file) {
            $fileList = array_merge($fileList, $file);
        }
        return $fileList;
    }

} 
